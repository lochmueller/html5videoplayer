<?php

namespace HVP\Html5videoplayer\Controller;

use HVP\Html5videoplayer\Domain\Repository\VideoRepository;
use HVP\Html5videoplayer\Event\DetailActionVariablesEvent;
use HVP\Html5videoplayer\Event\ListActionVariablesEvent;
use HVP\Html5videoplayer\Event\OverviewActionVariablesEvent;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Http\PropagateResponseException;
use TYPO3\CMS\Core\Page\AssetCollector;
use TYPO3\CMS\Core\Page\PageRenderer;
use HVP\Html5videoplayer\Div;
use HVP\Html5videoplayer\Domain\Model\Video;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\FrontendConfigurationManager;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\CMS\Extbase\Http\ForwardResponse;


class VideoplayerController extends ActionController
{
    /**
     * The current Video JS Version
     */
    public const VIDEO_JS_VERSION = '6.2.8';

    /**
     * Check if the header is included
     *
     * @var Boolean
     */
    static protected $includeHeader = false;

    /**
     * Configuration
     *
     * @var array
     */
    protected $configuration = [];

    public function __construct(
        protected AssetCollector $assetCollector,
        protected VideoRepository $videoRepository,
    )
    {
    }

    /**
     * Init the actions
     *
     * @return void
     */
    public function initializeAction(): void
    {
        $this->configuration = $this->settings['videoplayer'];

        // Check Xhtml Cleaning
        /** @var FrontendConfigurationManager $feConfigManager */
        $feConfigManager = GeneralUtility::makeInstance(FrontendConfigurationManager::class);
        $typoScript = $feConfigManager->getTypoScriptSetup();
        if (isset($typoScript['config.']['xhtml_cleaning']) && in_array(trim($typoScript['config.']['xhtml_cleaning']),
                [
                    'all',
                    'cached',
                    'output'
                ])
        ) {
            die('HTML5 Video Player: You have enabled the xhtml_cleaning in your configuration. This will destroy the serialze field in the flash fallback. Please disable the xhtml_cleaning-feature (current value: "' . trim($typoScript['config.']['xhtml_cleaning']) . '") in the TYPO3 TypoScript configuration.');
        }

        /* Check Static include */
        if (!@is_array($typoScript['plugin.']['tx_html5videoplayer.']['view.'])) {
            die('HTML5 Video Player: You have to include the static extension Template of the html5videoplayer.');
        }
    }

    public function listAction(): ResponseInterface
    {
        $this->loadHeaderData();

        $variables = [
            'videos' => $this->getCurrentVideos()
        ];

        $event = $this->eventDispatcher->dispatch(new ListActionVariablesEvent($variables));

        $this->view->assignMultiple($event->variables);
        return $this->htmlResponse();
    }

    /**
     * Get the current videos
     *
     * @return array
     */
    protected function getCurrentVideos(): array
    {
        $contentObject = $this->configurationManager->getContentObject();
        $contentElement = $contentObject->data;

        if (isset($this->settings['videoUids']['_typoScriptNodeValue'])) {
            $videoUids = $contentObject->cObjGetSingle(
                $this->settings['videoUids']['_typoScriptNodeValue'],
                $this->settings['videoUids']
            );
        }
        $videos = [];
        if (!empty($videoUids) && $this->settings['videoUids']) { // TypoScript
            $videos = $this->videoRepository->findByUids(GeneralUtility::trimExplode(',', $videoUids, true));
        } elseif (isset($contentElement['_LOCALIZED_UID'])) { // Content Element, localized
            $videos = $this->videoRepository->findByUids($this->getVideoIdsByContentUid($contentElement['_LOCALIZED_UID']));
        } elseif (isset($contentElement['uid'])) { // Content Element
            $videos = $this->videoRepository->findByUids($this->getVideoIdsByContentUid($contentElement['uid']));
        } elseif (isset($contentElement[0]) && !is_array(isset($contentElement[0]))) { // Fluid cObject data
            $videos = $this->videoRepository->findByUids(GeneralUtility::trimExplode(',', $contentElement[0], true));
        } elseif (trim($this->configuration['videos']) != '') { // TypoScript
            $videos = $this->videoRepository->findByUids(GeneralUtility::trimExplode(',',
                $this->configuration['videos'], true));
        }

        return $videos;
    }

    public function overviewAction(): ResponseInterface
    {
        $videos = $this->getCurrentVideos();

        /* Skip Ovewview */
        if (isset($this->settings['skipOverview']) && $this->settings['skipOverview'] && count($videos)) {
            $ident = 0;
            if ($this->settings['skipOverview'] === 'random') {
                $ident = random_int(0, count($videos) - 1);
            }

            $arguments = ['video' => $videos[$ident]];
            $uri = $this->uriBuilder->reset()
                ->setCreateAbsoluteUri(true)
                ->uriFor('detail', $arguments);
            if ($this->settings['skipOverviewMode'] == 'forward') {
                $this->getTyposcriptFrontendController()
                    ->set_no_cache('HTML5VideoPlayer is in forward mode in the overview');
                return (new ForwardResponse('detail'))->withArguments($arguments);
            } else {
                $responseFactory = GeneralUtility::makeInstance(ResponseFactoryInterface::class);
                $response = $responseFactory->createResponse()->withAddedHeader('location', $uri);
                throw new PropagateResponseException($response);
            }
        }

        $variables = [
            'videos' => $videos
        ];

        $event = $this->eventDispatcher->dispatch(new OverviewActionVariablesEvent($variables));

        $this->view->assignMultiple($event->variables);
        return $this->htmlResponse();
    }

    public function detailAction(Video $video): ResponseInterface
    {
        $this->loadHeaderData();
        $videos = $this->getCurrentVideos();

        $videoPosition = (int)$this->settings['activePosition'];
        if ($videoPosition && sizeof($videos) >= $videoPosition) {
            foreach ($videos as $id => $v) {
                /** @var $v Video */
                if ($video->getUid() === $v->getUid()) {
                    $moveRight = $videoPosition - ($id + 1);
                    $videos = $this->arrayMoveRight($videos, $moveRight);
                    break;
                }
            }
        }

        $variables = [
            'videos' => $videos,
            'currentVideo' => $video
        ];

        $event = $this->eventDispatcher->dispatch(new DetailActionVariablesEvent($variables));

        $this->view->assignMultiple($event->variables);
        return $this->htmlResponse();
    }

    /**
     * Move the Array elements by the given number
     *
     * @param array $array
     * @param integer $move
     * @return array
     */
    protected function arrayMoveRight($array, $move): array
    {
        if ($move < 0) {
            for (; $move < 0; $move++) {
                $array[] = array_shift($array);
            }
        } elseif ($move > 0) {
            for (; $move > 0; $move--) {
                array_unshift($array, array_pop($array));
            }
        }
        return $array;
    }

    /**
     * Load the headerData
     *
     * @return void
     */
    protected function loadHeaderData(): void
    {
        if (!self::$includeHeader && $this->settings['skipHtmlHeaderInformation'] != 1) {
            $folder = $this->settings['resourceFolder'];

            $css = $folder . 'video-js-' . self::VIDEO_JS_VERSION . '/video-js.min.css';
            $javaScript = $folder . 'video-js-' . self::VIDEO_JS_VERSION . '/video.min.js';
            $javaScriptYouTube = $folder . 'videojs-youtube-2.6.0/dist/Youtube.min.js';
            $javaScriptVimeo = $folder . 'videojs-vimeo-master-2017-09-11/dist/videojs-vimeo.min.js';

            if (isset($this->settings['videoJsCdn']) && $this->settings['videoJsCdn']) {
                $css = '//vjs.zencdn.net/' . self::VIDEO_JS_VERSION . '/video-js.css';
                $javaScript = '//vjs.zencdn.net/' . self::VIDEO_JS_VERSION . '/video.js';
            }

            $this->assetCollector->addJavaScript('html5videoplayer_javascript', $javaScript);
            $this->assetCollector->addJavaScript('html5videoplayer_javascript_youtube', $javaScriptYouTube);
            $this->assetCollector->addStyleSheet('html5videoplayer_stylesheet', $css);
            if (Div::featureEnable('vimeo')) {
                $this->assetCollector->addJavaScript('html5videoplayer_javascript_vimeo', $javaScriptVimeo);
            }
        }

        self::$includeHeader = true;
    }

    /**
     * Get the video IDs by the given Content uid
     *
     * @param integer $uid
     * @return array
     */
    protected function getVideoIdsByContentUid($uid): array
    {
        $uids = [];
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_html5videoplayer_video_content');
        $where = [
            $queryBuilder->expr()->eq('content_uid', (int)$uid)
        ];
        $rows = (array)$queryBuilder->select('video_uid')
            ->from('tx_html5videoplayer_video_content')
            ->where(...$where)
            ->orderBy('sorting')
            ->executeQuery()
            ->fetchAllAssociative();
        foreach ($rows as $row) {
            $uids[] = (int)$row['video_uid'];
        }
        return $uids;
    }

    /**
     * TypoScript Frontend controller
     *
     * @return TyposcriptFrontendController
     */
    protected function getTyposcriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}

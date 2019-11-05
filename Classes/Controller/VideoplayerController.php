<?php
/**
 * Html5VideoPlayer Controller
 *
 * @category   Extension
 * @package    Html5videoplayer
 * @subpackage Controller
 * @author     Tim Lochmüller <tim@fruit-lab.de>
 */

namespace HVP\Html5videoplayer\Controller;

use HVP\Html5videoplayer\Div;
use HVP\Html5videoplayer\Domain\Model\Video;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Extbase\Configuration\FrontendConfigurationManager;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * Abstract Command Controller
 *
 * @package    Html5videoplayer
 * @subpackage Controller
 * @author     Tim Lochmüller <tim@fruit-lab.de>
 */
class VideoplayerController extends ActionController
{

    /**
     * The current Video JS Version
     */
    const VIDEO_JS_VERSION = '6.2.8';

    /**
     * The video repository
     *
     * @var \HVP\Html5videoplayer\Domain\Repository\VideoRepository
     * @inject
     */
    protected $videoRepository;

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

    /**
     * Init the actions
     */
    public function initializeAction()
    {
        $this->configuration = $this->settings['videoplayer'];

        // Check Xhtml Cleaning
        /** @var FrontendConfigurationManager $feConfigManager */
        $feConfigManager = GeneralUtility::makeInstance(FrontendConfigurationManager::class);
        $typoScript = $feConfigManager->getTypoScriptSetup();
        if (isset($typoScript['config.']['xhtml_cleaning']) && in_array(trim($typoScript['config.']['xhtml_cleaning']), [
                'all',
                'cached',
                'output'
            ])
        ) {
            die('HTML5 Video Player: You have enabled the xhtml_cleaning in your configuration. This will destroy the serialze field in the flash fallback. Please disable the xhtml_cleaning-feature (current value: "' . trim($typoScript['config.']['xhtml_cleaning']) . '") in the TYPO3 TypoScript configuration.');
        }

        // Check Static include
        if (!@is_array($typoScript['plugin.']['tx_html5videoplayer.']['view.'])) {
            die('HTML5 Video Player: You have to include the static extension Template of the html5videoplayer.');
        }
    }

    /**
     * controll the list action
     */
    public function listAction()
    {
        $this->loadHeaderData();

        $variables = [
            'videos' => $this->getCurrentVideos()
        ];

        $variables = $this->getSignalSlotDispatcher()
            ->dispatch(__CLASS__, __METHOD__, $variables);
        $this->view->assignMultiple($variables);
    }

    /**
     * Get the current videos
     *
     * @return array
     */
    protected function getCurrentVideos()
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
            $videos = $this->videoRepository->findByUids(GeneralUtility::trimExplode(',', $this->configuration['videos'], true));
        }

        return $videos;
    }

    /**
     * Render the overview action
     */
    public function overviewAction()
    {
        $videos = $this->getCurrentVideos();

        // Skip Ovewview
        if (isset($this->settings['skipOverview']) && $this->settings['skipOverview']) {
            if (sizeof($videos)) {
                $ident = 0;
                if ($this->settings['skipOverview'] == 'random') {
                    $ident = rand(0, sizeof($videos) - 1);
                }

                $arguments = ['video' => $videos[$ident]];
                $uri = $this->uriBuilder->reset()
                    ->setCreateAbsoluteUri(true)
                    ->uriFor('detail', $arguments);
                if ($this->settings['skipOverviewMode'] == 'forward') {
                    $this->getTyposcriptFrontendController()
                        ->set_no_cache('HTML5VideoPlayer is in forward mode in the overview');
                    $this->forward('detail', null, null, $arguments);
                } else {
                    HttpUtility::redirect($uri);
                }
            }
        }

        $variables = [
            'videos' => $videos
        ];

        $variables = $this->getSignalSlotDispatcher()
            ->dispatch(__CLASS__, __METHOD__, $variables);
        $this->view->assignMultiple($variables);
    }

    /**
     * Render the detail action
     *
     * @param Video $video
     */
    public function detailAction(Video $video)
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
            'videos'       => $videos,
            'currentVideo' => $video
        ];

        $variables = $this->getSignalSlotDispatcher()
            ->dispatch(__CLASS__, __METHOD__, $variables);
        $this->view->assignMultiple($variables);
    }

    /**
     * Get a signal slot dispatcher
     *
     * @return Dispatcher
     */
    protected function getSignalSlotDispatcher()
    {
        return $this->objectManager->get(Dispatcher::class);
    }

    /**
     * Move the Array elements by the given number
     *
     * @param array   $array
     * @param integer $move
     *
     * @return array
     */
    protected function arrayMoveRight($array, $move)
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
     */
    protected function loadHeaderData()
    {
        if (!self::$includeHeader && $this->settings['skipHtmlHeaderInformation'] != 1) {
            $folder = $this->getResourceFolder();

            $css = $folder . 'video-js-' . self::VIDEO_JS_VERSION . '/video-js.min.css';
            $javaScript = $folder . 'video-js-' . self::VIDEO_JS_VERSION . '/video.min.js';

            if (isset($this->settings['videoJsCdn']) && $this->settings['videoJsCdn']) {
                $css = '//vjs.zencdn.net/' . self::VIDEO_JS_VERSION . '/video-js.css';
                $javaScript = '//vjs.zencdn.net/' . self::VIDEO_JS_VERSION . '/video.js';
            }

            $this->addHeader('<link href="' . $css . '" type="text/css" rel="stylesheet" media="screen" />');
            $this->addHeader('<script src="' . $javaScript . '" type="text/javascript"></script>');
            $this->addHeader('<script src="' . $folder . 'videojs-youtube-2.6.0/dist/Youtube.min.js" type="text/javascript"></script>');
            if (Div::featureEnable('vimeo')) {
                $this->addHeader('<script src="' . $folder . 'videojs-vimeo-master-2017-09-11/dist/videojs-vimeo.min.js" type="text/javascript"></script>');
            }
        }

        self::$includeHeader = true;
    }

    /**
     * Get the resource folder
     *
     * @return string
     */
    protected function getResourceFolder()
    {
        $folder = $this->settings['resourceFolder'];

        if (substr($folder, 0, 4) == 'EXT:') {
            list($extKey, $local) = explode('/', substr($folder, 4), 2);
            $folder = '';
            if (strcmp($extKey, '') && ExtensionManagementUtility::isLoaded($extKey) && strcmp($local, '')) {
                $folder = ExtensionManagementUtility::siteRelPath($extKey) . $local;
            }
        }
        return $folder;
    }

    /**
     * Add a HTML header
     *
     * @param string $header
     */
    protected function addHeader($header)
    {
        /** @var \TYPO3\CMS\Extbase\Mvc\Web\Response $response */
        $response = &$this->response;
        $response->addAdditionalHeaderData($header);
    }

    /**
     * Get the video IDs by the given Content uid
     *
     * @param integer $uid
     *
     * @return array
     */
    protected function getVideoIdsByContentUid($uid)
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
            ->execute()
            ->fetchAll();
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
    protected function getTyposcriptFrontendController()
    {
        return $GLOBALS['TSFE'];
    }
}

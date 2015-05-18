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
use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * Abstract Command Controller
 *
 * @package    Html5videoplayer
 * @subpackage Controller
 * @author     Tim Lochmüller <tim@fruit-lab.de>
 */
class VideoplayerController extends ActionController {

	/**
	 * The current Video JS Version
	 */
	const VIDEO_JS_VERSION = '4.12.6';

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
	static protected $includeHeader = FALSE;

	/**
	 * Configuration
	 *
	 * @var array
	 */
	protected $configuration = array();

	/**
	 * Init the actions
	 */
	public function initializeAction() {
		$this->configuration = $this->settings['videoplayer'];

		// Check Xhtml Cleaning
		/** @var \\TYPO3\CMS\Extbase\Configuration\FrontendConfigurationManager $feConfigManager */
		$feConfigManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Configuration\\FrontendConfigurationManager');
		$typoScript = $feConfigManager->getTypoScriptSetup();
		if (isset($typoScript['config.']['xhtml_cleaning']) && in_array(trim($typoScript['config.']['xhtml_cleaning']), array(
				'all',
				'cached',
				'output'
			))
		) {
			die('HTML5 Video Player: You have enabled the xhtml_cleaning in your configuration. This will destroy the serialze field in the flash fallback. Please disable the xhtml_cleaning-feature (current value: "' . trim($ts['config.']['xhtml_cleaning']) . '") in the TYPO3 TypoScript configuration.');
		}

		// Check Static include
		if (!@is_array($typoScript['plugin.']['tx_html5videoplayer.']['view.'])) {
			die('HTML5 Video Player: You have to include the static extension Template of the html5videoplayer.');
		}
	}

	/**
	 * controll the list action
	 */
	public function listAction() {
		$this->loadHeaderData();

		$variables = array(
			'videos' => $this->getCurrentVideos()
		);

		$variables = $this->getSignalSlotDispatcher()
			->dispatch(__CLASS__, __METHOD__, $variables);
		$this->view->assignMultiple($variables);
	}

	/**
	 * Get the current videos
	 *
	 * @return array
	 */
	protected function getCurrentVideos() {
		$contentObject = $this->configurationManager->getContentObject();
		$contentElement = $contentObject->data;

		if (isset($this->settings['videoUids']['_typoScriptNodeValue'])) {
			$videoUids = $contentObject->cObjGetSingle($this->settings['videoUids']['_typoScriptNodeValue'], $this->settings['videoUids']);
		}
		$videos = array();
		if (!empty($videoUids) && $this->settings['videoUids']) { // TypoScript
			$videos = $this->videoRepository->findByUids(GeneralUtility::trimExplode(',', $videoUids, TRUE));
		} elseif (isset($contentElement['uid'])) { // Content Element
			$videos = $this->videoRepository->findByUids($this->getVideoIdsByContentUid($contentElement['uid']));
		} else if (isset($contentElement[0]) && !is_array(isset($contentElement[0]))) { // Fluid cObject data
			$videos = $this->videoRepository->findByUids(GeneralUtility::trimExplode(',', $contentElement[0], TRUE));
		} else if (trim($this->configuration['videos']) != '') { // TypoScript
			$videos = $this->videoRepository->findByUids(GeneralUtility::trimExplode(',', $this->configuration['videos'], TRUE));
		}

		return $videos;
	}

	/**
	 * Render the overview action
	 */
	public function overviewAction() {
		$videos = $this->getCurrentVideos();

		// Skip Ovewview
		if (isset($this->settings['skipOverview']) && $this->settings['skipOverview']) {
			if (sizeof($videos)) {
				$ident = 0;
				if ($this->settings['skipOverview'] == 'random') {
					$ident = rand(0, sizeof($videos) - 1);
				}

				$arguments = array('video' => $videos[$ident]);
				$uri = $this->uriBuilder->reset()
					->setCreateAbsoluteUri(TRUE)
					->uriFor('detail', $arguments);
				if ($this->settings['skipOverviewMode'] == 'forward') {
					$this->getTyposcriptFrontendController()
						->set_no_cache('HTML5VideoPlayer is in forward mode in the overview');
					$this->forward('detail', NULL, NULL, $arguments);
				} else {
					HttpUtility::redirect($uri);
				}
			}
		}

		$variables = array(
			'videos' => $videos
		);

		$variables = $this->getSignalSlotDispatcher()
			->dispatch(__CLASS__, __METHOD__, $variables);
		$this->view->assignMultiple($variables);
	}

	/**
	 * Render the detail action
	 *
	 * @param Video $video
	 */
	public function detailAction(Video $video) {
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

		$variables = array(
			'videos'       => $videos,
			'currentVideo' => $video
		);

		$variables = $this->getSignalSlotDispatcher()
			->dispatch(__CLASS__, __METHOD__, $variables);
		$this->view->assignMultiple($variables);
	}

	/**
	 * Get a signal slot dispatcher
	 *
	 * @return \TYPO3\CMS\Extbase\SignalSlot\Dispatcher
	 */
	protected function getSignalSlotDispatcher() {
		return $this->objectManager->get('TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher');
	}

	/**
	 * Move the Array elements by the given number
	 *
	 * @param array   $array
	 * @param integer $move
	 *
	 * @return array
	 */
	protected function arrayMoveRight($array, $move) {
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
	protected function loadHeaderData() {
		if (!self::$includeHeader && $this->settings['skipHtmlHeaderInformation'] != 1) {
			$folder = $this->getResourceFolder();

			$css = $folder . 'video-js-' . self::VIDEO_JS_VERSION . '/video-js.min.css';
			$javaScript = $folder . 'video-js-' . self::VIDEO_JS_VERSION . '/video.js';
			// Added the SWF to the locally loaded data
			$swf = '<script>videojs.options.flash.swf = "' . $folder . 'video-js-' . self::VIDEO_JS_VERSION . '/video-js.swf"</script>';

			if (isset($this->settings['videoJsCdn']) && $this->settings['videoJsCdn']) {
				$css = 'http://vjs.zencdn.net/' . self::VIDEO_JS_VERSION . '/video-js.css';
				$javaScript = 'http://vjs.zencdn.net/' . self::VIDEO_JS_VERSION . '/video.js';
				$swf = FALSE;
			}

			$this->addHeader('<link href="' . $css . '" type="text/css" rel="stylesheet" media="screen" />');
			$this->addHeader('<script src="' . $javaScript . '" type="text/javascript"></script>');
			$this->addHeader('<script src="' . $folder . 'videojs-youtube-1.2.11/dist/vjs.youtube.js" type="text/javascript"></script>');
			if (Div::featureEnable('vimeo')) {
				$this->addHeader('<script src="' . $folder . 'videojs-vimeo-master/src/media.vimeo.js" type="text/javascript"></script>');
			}
			if ($swf) {
				$this->addHeader($swf);
			}
		}

		self::$includeHeader = TRUE;
	}

	/**
	 * Get the resource folder
	 *
	 * @return string
	 */
	protected function getResourceFolder() {
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
	protected function addHeader($header) {
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
	protected function getVideoIdsByContentUid($uid) {
		$uids = array();
		$res = $this->getDatabase()
			->exec_SELECTquery('video_uid', 'tx_html5videoplayer_video_content', 'content_uid=' . intval($uid), '', 'sorting');
		while ($row = $this->getDatabase()
			->sql_fetch_assoc($res)) {
			$uids[] = (int)$row['video_uid'];
		}
		return $uids;
	}

	/**
	 * Database object
	 *
	 * @return DatabaseConnection
	 */
	protected function getDatabase() {
		return $GLOBALS['TYPO3_DB'];
	}

	/**
	 * TypoScript Frontend controller
	 *
	 * @return TyposcriptFrontendController
	 */
	protected function getTyposcriptFrontendController() {
		return $GLOBALS['TSFE'];
	}

}
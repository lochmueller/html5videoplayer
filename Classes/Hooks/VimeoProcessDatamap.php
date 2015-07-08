<?php

namespace HVP\Html5videoplayer\Hooks;


use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class VimeoProcessDatamap
 *
 * @package HVP\Html5videoplayer\Hooks
 */
class VimeoProcessDatamap {

	/**
	 * @var string
	 */
	protected $defaultUploadFolder = '1:/';

	/**
	 * @param $status
	 * @param $table
	 * @param $id
	 * @param $fieldArray
	 * @param $self
	 */
	function processDatamap_postProcessFieldArray($status, $table, $id, &$fieldArray, &$self) {

		if ($table == 'tx_html5videoplayer_domain_model_video') {
			$dataBeforeChange = array();
			//Fetch previously saved data to compare with
			$vimeoUrl = $fieldArray['vimeo'];
			if ($status == 'update') {
				$dataBeforeChange = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow('*', 'tx_html5videoplayer_domain_model_video', 'uid=' . (int)$id);
				if ($vimeoUrl == '') {
					$vimeoUrl = $dataBeforeChange['vimeo'];
				}
			}

			if (($status == 'update' || $status == 'new') && $vimeoUrl != '' && GeneralUtility::isValidUrl($vimeoUrl)) {
				if (preg_match('/https?:\\/\\/(?:www\\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|)(\d+)(?:$|\/|\?)/i', $vimeoUrl, $matches)) {
					$videoId = $matches[3];
					$videoData = unserialize(GeneralUtility::getUrl('http://vimeo.com/api/v2/video/' . $videoId . '.php'));

					if (is_array($videoData)) {
						//We're only interested in index zero.
						$videoData = $videoData[0];

						if ((!isset($fieldArray['title']) && $dataBeforeChange['title'] == '') || $fieldArray['title'] == '') {
							$fieldArray['title'] = $videoData['title'];
						}

						if ((!isset($fieldArray['description']) && $dataBeforeChange['description'] == '')) {
							$fieldArray['description'] = $videoData['description'];
						}

						if ((!isset($fieldArray['posterimage']) && $dataBeforeChange['posterimage'] == '') || $fieldArray['posterimage'] == '') {
							$resourceFactory = ResourceFactory::getInstance();
							$folder = $resourceFactory->retrieveFileOrFolderObject($this->getUploadFolder());
							$thumbnailData = GeneralUtility::getUrl($videoData['thumbnail_large']);
							$file = $folder->createFile(basename($videoData['thumbnail_large']) . '.jpg');
							$file->setContents($thumbnailData);
							$fieldArray['posterimage'] = 'file:' . $file->getUid();
						}
					}
				}
			}
		}
	}

	/**
	 * Get the upload folder
	 * Use the Signal to override the default folder
	 *
	 * @return string
	 */
	protected function getUploadFolder() {
		/** @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher $dispatcher */
		$dispatcher = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher');
		$arguments = array(
			$this->defaultUploadFolder,
		);
		$arguments = $dispatcher->dispatch(__CLASS__, __METHOD__, $arguments);
		return $arguments[0];
	}

} 
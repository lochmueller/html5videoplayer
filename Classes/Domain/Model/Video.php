<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c)  Tim Lochmueller
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */

namespace HVP\Html5videoplayer\Domain\Model;

use HVP\Html5videoplayer\Div;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\MediaWizard\MediaWizardProviderManager;

/**
 * Report Object
 *
 * @author     Tim Lochmueller
 */
class Video extends AbstractEntity {

	/**
	 * The title of the Video
	 *
	 * @var string
	 * @validate StringLength(minimum = 1)
	 */
	protected $title;

	/**
	 * Vimeo Source Url
	 *
	 * @var string
	 */
	protected $vimeo;

	/**
	 * The description
	 *
	 * @var string
	 */
	protected $description;

	/**
	 * The posterimage of the Video
	 *
	 * @var string
	 */
	protected $posterimage;

	/**
	 * The mp4source of the Video
	 *
	 * @var string
	 */
	protected $mp4source;

	/**
	 * The webmsource of the Video
	 *
	 * @var string
	 */
	protected $webmsource;

	/**
	 * The oggsource of the Video
	 *
	 * @var string
	 */
	protected $oggsource;

	/**
	 * The height of the Video
	 *
	 * @var string
	 */
	protected $height;

	/**
	 * The width of the Video
	 *
	 * @var string
	 */
	protected $width;

	/**
	 * Show download links
	 *
	 * @var boolean
	 */
	protected $downloadlinks;

	/**
	 * Show the support link
	 *
	 * @var boolean
	 */
	protected $supportvideojs;

	/**
	 * Preload the video
	 *
	 * @var string
	 */
	protected $preloadvideo;

	/**
	 * Autoplay the video
	 *
	 * @var boolean
	 */
	protected $autoplayvideo;

	/**
	 * Loop the video
	 *
	 * @var boolean
	 */
	protected $loopvideo;

	/**
	 *
	 * @var boolean
	 */
	protected $controlsvideo;

	/**
	 *
	 * @var string
	 */
	protected $youtube;

	/**
	 * Returns the $title
	 *
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Set the title of the Video
	 *
	 * @param string $title the $title to set
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * Returns the $posterimage
	 *
	 * @return string
	 */
	public function getPosterimage() {
		return $this->retrieveMediaUrl($this->posterimage);
	}

	/**
	 * Set the posterimage of the Video
	 *
	 * @param string $posterimage the $posterimage to set
	 */
	public function setPosterimage($posterimage) {
		$this->posterimage = $posterimage;
	}

	/**
	 * Returns the $mp4source
	 *
	 * @return string
	 */
	public function getMp4source() {
		return $this->retrieveMediaUrl($this->mp4source);
	}

	/**
	 * Set the mp4source of the Video
	 *
	 * @param string $mp4source the $mp4source to set
	 */
	public function setMp4source($mp4source) {
		$this->mp4source = $mp4source;
	}

	/**
	 * Returns the $webmsource
	 *
	 * @return string
	 */
	public function getWebmsource() {
		return $this->retrieveMediaUrl($this->webmsource);
	}

	/**
	 * Set the webmsource of the Video
	 *
	 * @param string $webmsource the $webmsource to set
	 */
	public function setWebmsource($webmsource) {
		$this->webmsource = $webmsource;
	}

	/**
	 * Returns the $oggsource
	 *
	 * @return string
	 */
	public function getOggsource() {
		return $this->retrieveMediaUrl($this->oggsource);
	}

	/**
	 * Set the oggsource of the Video
	 *
	 * @param string $oggsource the $oggsource to set
	 */
	public function setOggsource($oggsource) {
		$this->oggsource = $oggsource;
	}

	/**
	 * Returns the $height
	 *
	 * @return string
	 */
	public function getHeight() {
		return $this->height;
	}

	/**
	 * Set the height of the Video
	 *
	 * @param string $height the $height to set
	 */
	public function setHeight($height) {
		$this->height = $height;
	}

	/**
	 * Returns the $width
	 *
	 * @return string
	 */
	public function getWidth() {
		return $this->width;
	}

	/**
	 * Set the width of the Video
	 *
	 * @param string $width the $width to set
	 */
	public function setWidth($width) {
		$this->width = $width;
	}

	/**
	 * Returns the $downloadlinks
	 *
	 * @return string
	 */
	public function getDownloadlinks() {
		return $this->downloadlinks;
	}

	/**
	 * Set the downloadlinks of the Video
	 *
	 * @param boolean $downloadlinks the $downloadlinks to set
	 */
	public function setDownloadlinks($downloadlinks) {
		$this->downloadlinks = $downloadlinks;
	}

	/**
	 * Returns the $supportvideojs
	 *
	 * @return string
	 */
	public function getSupportvideojs() {
		return $this->supportvideojs;
	}

	/**
	 * Set the supportvideojs of the Video
	 *
	 * @param boolean $supportvideojs the $supportvideojs to set
	 */
	public function setSupportvideojs($supportvideojs) {
		$this->supportvideojs = $supportvideojs;
	}

	/**
	 * Returns the $preloadvideo
	 *
	 * @return string
	 */
	public function getPreloadvideo() {
		return $this->preloadvideo;
	}

	/**
	 * Set the preloadvideo of the Video
	 *
	 * @param string $preloadvideo the $preloadvideo to set
	 */
	public function setPreloadvideo($preloadvideo) {
		$this->preloadvideo = $preloadvideo;
	}

	/**
	 * Returns the $autoplayvideo
	 *
	 * @return string
	 */
	public function getAutoplayvideo() {
		return $this->autoplayvideo;
	}

	/**
	 * Set the autoplayvideo of the Video
	 *
	 * @param boolean $autoplayvideo the $autoplayvideo to set
	 */
	public function setAutoplayvideo($autoplayvideo) {
		$this->autoplayvideo = $autoplayvideo;
	}

	/**
	 *
	 * @return boolean
	 */
	public function getControlsvideo() {
		return (boolean)$this->controlsvideo;
	}

	/**
	 *
	 * @param boolean $controlsvideo
	 */
	public function setControlsvideo($controlsvideo) {
		$this->controlsvideo = $controlsvideo;
	}

	/**
	 *
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 *
	 * @param string $description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * @param boolean $loopvideo
	 */
	public function setLoopvideo($loopvideo) {
		$this->loopvideo = $loopvideo;
	}

	/**
	 * @return boolean
	 */
	public function getLoopvideo() {
		return (boolean)$this->loopvideo;
	}

	/**
	 * Resolves the URL of an file
	 *
	 * @param $media
	 *
	 * @return null|string
	 */
	protected function retrieveMediaUrl($media) {
		$returnValue = NULL;

		/** @var $mediaWizard \TYPO3\CMS\Frontend\MediaWizard\MediaWizardProviderInterface */
		$mediaWizard = MediaWizardProviderManager::getValidMediaWizardProvider($media);

		// Get the path relative to the page currently outputted
		if (substr($media, 0, 5) === "file:") {
			$fileUid = substr($media, 5);

			if (MathUtility::canBeInterpretedAsInteger($fileUid)) {
				$fileObject = ResourceFactory::getInstance()
					->getFileObject($fileUid);

				if ($fileObject instanceof FileInterface) {
					$returnValue = $fileObject->getPublicUrl();
				}
			}
		} elseif (is_file(PATH_site . $media)) {
			$returnValue = $GLOBALS['TSFE']->tmpl->getFileName($media);
		} elseif ($mediaWizard !== NULL) {
			$cObj = new ContentObjectRenderer();
			$returnValue = $cObj->typoLink_URL(array(
				'parameter' => $mediaWizard->rewriteUrl($media)
			));
		} elseif (GeneralUtility::isValidUrl($media)) {
			$returnValue = $media;
		}

		return $returnValue;
	}

	/**
	 * @param string $youtube
	 */
	public function setYoutube($youtube) {
		$this->youtube = $youtube;
	}

	/**
	 * @return string
	 */
	public function getYoutube() {
		return $this->youtube;
	}

	/**
	 * @param string $vimeo
	 */
	public function setVimeo($vimeo) {
		$this->vimeo = $vimeo;
	}

	/**
	 * @return string
	 */
	public function getVimeo() {
		return $this->vimeo;
	}

	/**
	 * @return int|string
	 */
	public function getMinWidth() {
		$width = $this->getWidth();
		if (trim($width) !== '' && (int)$width !== 0) {
			return $width;
		}

		$default = Div::getConfigurationValue('generalMinWith');
		return (int)$default ? : 200;
	}

	/**
	 * @return int|string
	 */
	public function getMinHeight() {
		$height = $this->getHeight();
		if (trim($height) !== '' && (int)$height !== 0) {
			return $height;
		}

		$default = Div::getConfigurationValue('generalMinHeight');
		return (int)$default ? : 150;
	}

}
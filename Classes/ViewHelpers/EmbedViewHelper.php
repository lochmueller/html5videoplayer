<?php

namespace HVP\Html5videoplayer\ViewHelpers;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Generate the Embed Tag
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @api
 * @scope   prototype
 */
class EmbedViewHelper extends AbstractViewHelper {

	/**
	 * Just render everything.
	 *
	 * @param string  $value
	 * @param integer $videowidth
	 * @param integer $videoheight
	 * @param string  $src
	 * @param string  $imgfallback
	 * @param boolean $autoplay
	 *
	 * @return string
	 */
	public function render($value, $videowidth, $videoheight, $src, $imgfallback = NULL, $autoplay = FALSE) {

		if ($imgfallback != NULL) {
			$configuration['playlist'] = array('###IMAGE###');
		}
		$configuration['playlist'][] = array(
			'url'           => '###URL###',
			'autoPlay'      => $autoplay,
			'autoBuffering' => TRUE,
		);

		$json = json_encode($configuration);

		if (substr($value, 0, strlen('http')) !== "http") {
			$value = GeneralUtility::getIndpEnv('TYPO3_SITE_URL') . $value;
		}
		$json = str_replace('###URL###', $value, $json);
		$json = str_replace('###IMAGE###', $imgfallback, $json);

		return '<embed type="application/x-shockwave-flash" width="' . $videowidth . '" height="' . $videoheight . '" src="' . $src . '" flashvars=\'config=' . $json . '\' allowfullscreen="true" />';
	}
}
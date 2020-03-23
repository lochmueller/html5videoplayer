<?php

namespace HVP\Html5videoplayer\ViewHelpers;

use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Generate the flash param
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @api
 * @scope   prototype
 */
class FlashparamViewHelper extends AbstractViewHelper
{

    /**
     * Just render everything.
     *
     * @param string  $value
     * @param string  $imgfallback
     * @param boolean $autoplay
     *
     * @return string
     */
    public function render($value, $imgfallback = "", $autoplay = false)
    {
        if ($imgfallback != "") {
            $configuration['playlist'] = ['###IMAGE###'];
        }
        $configuration['playlist'][] = [
            'url'           => '###URL###',
            'autoPlay'      => $autoplay,
            'autoBuffering' => true,
        ];

        if (substr($value, 0, strlen('http')) != "http") {
            $value = GeneralUtility::getIndpEnv('TYPO3_SITE_URL') . $value;
        }

        if (substr($imgfallback, 0, strlen('http')) !== "http") {
            $imgfallback = GeneralUtility::getIndpEnv('TYPO3_SITE_URL') . $imgfallback;
        }

        $json = json_encode($configuration);
        $json = str_replace('###IMAGE###', $imgfallback, $json);
        $json = str_replace('###URL###', $value, $json);


        return '<param name="flashvars" value=\'config=' . $json . '\' />';
    }
}

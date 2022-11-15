<?php

namespace HVP\Html5videoplayer\ViewHelpers;

use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Generate the Embed Tag
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @api
 * @scope   prototype
 */
class EmbedViewHelper extends AbstractViewHelper
{

    /**
     * Just render everything.
     *
     *
     * @return string
     */
    public function render()
    {
        $configuration = [];
        $value = $this->arguments['value'];
        $videowidth = $this->arguments['videowidth'];
        $videoheight = $this->arguments['videoheight'];
        $src = $this->arguments['src'];
        $imgfallback = $this->arguments['imgfallback'];
        $autoplay = $this->arguments['autoplay'];
        if ($imgfallback != null) {
            $configuration['playlist'] = ['###IMAGE###'];
        }
        $configuration['playlist'][] = [
            'url'           => '###URL###',
            'autoPlay'      => $autoplay,
            'autoBuffering' => true,
        ];
        $json = json_encode($configuration);
        if (substr($value, 0, strlen('http')) !== "http") {
            $value = GeneralUtility::getIndpEnv('TYPO3_SITE_URL') . $value;
        }
        $json = str_replace('###URL###', $value, $json);
        $json = str_replace('###IMAGE###', $imgfallback, $json);
        return '<embed type="application/x-shockwave-flash" width="' . $videowidth . '" height="' . $videoheight . '" src="' . $src . '" flashvars=\'config=' . $json . '\' allowfullscreen="true" />';
    }

    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('value', 'string', '', true);
        $this->registerArgument('videowidth', 'integer', '', true);
        $this->registerArgument('videoheight', 'integer', '', true);
        $this->registerArgument('src', 'string', '', true);
        $this->registerArgument('imgfallback', 'string', '', false);
        $this->registerArgument('autoplay', 'boolean', '', false, false);
    }
}

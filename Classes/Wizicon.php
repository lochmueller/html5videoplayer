<?php

namespace HVP\Html5videoplayer;

use \TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use \TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class Wizicon
{

    /**
     * @var string
     */
    protected $extkey = 'html5videoplayer';

    /**
     * @var string
     */
    protected $extParamName = 'html5videoplayer_pivideoplayer';

    /**
     * Processing the wizard items array
     *
     * @param array $wizardItems : The wizard items
     * @return array Modified array with wizard items
     */
    public function proc($wizardItems): array
    {
        $aKey = 'plugins_tx_html5videoplayer_pi1';

        $wizardItems[$aKey] = [
            'iconIdentifier' => 'html5videoplayer_icon',
            'title' => LocalizationUtility::translate('list_title', $this->extkey),
            'description' => LocalizationUtility::translate('list_plus_wiz_description', $this->extkey),
            'params' => '&defVals[tt_content][CType]=list&defVals[tt_content][list_type]=' . $this->extParamName
        ];

        return $wizardItems;
    }

}

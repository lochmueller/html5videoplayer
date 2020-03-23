<?php

namespace HVP\Html5videoplayer;

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2010 Tim Lochmueller <webmaster@fruit-lab.de>
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

use \TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use \TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * Class that adds the wizard icon.
 *
 * @author        Tim Lochmueller <webmaster@fruit-lab.de>
 * @package       TYPO3
 * @subpackage    Tx_Html5videoplayer
 */
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

<?php

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use HVP\Html5videoplayer\Div;

defined('TYPO3_MODE') || die();

(static function ($extKey = 'html5videoplayer') {
    ExtensionUtility::registerPlugin($extKey, 'PiVideoplayer', 'Videoplayer');

    ExtensionManagementUtility::addPiFlexFormValue(
        $extKey . '_pivideoplayer',
        'FILE:EXT:' . $extKey . '/Configuration/FlexForm/Video.xml'
    );

    /* column */
    $GLOBALS['TCA']['tt_content']['columns']['tx_' . $extKey . '_videos'] = [
        'exclude' => false,
        'label' => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xlf:videos',
        'config' => [
            'type' => 'inline',
            'foreign_table' => 'tx_html5videoplayer_video_content',
            'foreign_field' => 'content_uid',
            'foreign_label' => 'video_uid',
            'foreign_sortby' => 'sorting',
            'foreign_selector' => 'video_uid',
            'foreign_unique' => 'video_uid',
            'maxitems' => '100',
            'appearance' => [
                'collapseAll' => false, // working RTE in TYPO3 > 7.4?!?!
                'expandSingle' => true,
                'useCombination' => 1,
                'useSortable' => true,
                'enabledControls' => [
                    'info' => true,
                    'new' => true,
                    'dragdrop' => true,
                    'sort' => true,
                    'hide' => true,
                    'delete' => true,
                    'localize' => true,
                ],
            ],
        ],
    ];

    $storageId = Div::getGeneralStorageFolder();
    if ($storageId) {
        unset($GLOBALS['TCA']['tt_content']['columns']['tx_html5videoplayer_videos']['config']['foreign_selector']);
        unset($GLOBALS['TCA']['tt_content']['columns']['tx_html5videoplayer_videos']['config']['foreign_unique']);
    }

    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$extKey . '_pivideoplayer'] = 'layout,select_key,pages,recursive';
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$extKey . '_pivideoplayer'] = 'pi_flexform,tx_html5videoplayer_videos';
})();

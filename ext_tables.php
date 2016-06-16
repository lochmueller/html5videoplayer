<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    'html5videoplayer_pivideoplayer',
    'FILE:EXT:html5videoplayer/Configuration/FlexForm/Video.xml'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_html5videoplayer_domain_model_video');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_html5videoplayer_video_content');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'html5videoplayer',
    'Configuration/TypoScript/Videoplayer',
    'HTML5 Video Player'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin('html5videoplayer', 'PiVideoplayer', 'Videoplayer');

if (TYPO3_MODE == 'BE') {
    $GLOBALS['TBE_MODULES_EXT']['xMOD_db_new_content_el']['addElClasses'][\HVP\Html5videoplayer\Wizicon::class] = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('html5videoplayer') . 'Classes/Wizicon.php';
}

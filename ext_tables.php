<?php /** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3_MODE') || die();

(static function ($extKey = 'html5videoplayer') {

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_html5videoplayer_domain_model_video');
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_html5videoplayer_video_content');

    if (TYPO3_MODE === 'BE') {
        $GLOBALS['TBE_MODULES_EXT']['xMOD_db_new_content_el']['addElClasses'][\HVP\Html5videoplayer\Wizicon::class] = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($extKey) . 'Classes/Wizicon.php';
    }
})();

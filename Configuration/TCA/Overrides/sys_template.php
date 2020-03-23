<?php /** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3_MODE') || die();

(static function ($extKey = 'html5videoplayer') {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        $extKey,
        'Configuration/TypoScript/Videoplayer',
        'HTML5 Video Player'
    );
})();

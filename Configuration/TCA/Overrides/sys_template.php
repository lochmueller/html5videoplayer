<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || defined('TYPO3_MODE') || die();

(static function ($extKey = 'html5videoplayer') {
    ExtensionManagementUtility::addStaticFile(
        $extKey,
        'Configuration/TypoScript/Videoplayer',
        'HTML5 Video Player'
    );
})();

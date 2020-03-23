<?php /** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3_MODE') || die();

(static function ($extKey = 'html5videoplayer') {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin('HVP.html5videoplayer', 'PiVideoplayer', [
        'Videoplayer' => 'list,overview,detail',
    ], [
        'Videoplayer' => 'overview'
    ]);

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][$extKey . '_div'] = \HVP\Html5videoplayer\Div::class;
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][$extKey . '_vimeo'] = \HVP\Html5videoplayer\Hooks\VimeoProcessDatamap::class;

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['list_type_Info']['html5videoplayer_pivideoplayer'][$extKey] = \HVP\Html5videoplayer\Hooks\CmsLayout::class . '->getExtensionSummary';

    /* Register content element icons */
    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
    $bmpIcons = [
        'html5videoplayer_icon' => 'EXT:' . $extKey . '/Resources/Public/Icons/Video.png',
    ];
    foreach ($bmpIcons as $identifier => $source) {
        $iconRegistry->registerIcon(
            $identifier,
            \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
            [
                'source' => $source,
            ]
        );
    }
})();

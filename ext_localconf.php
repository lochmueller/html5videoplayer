<?php

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin('HVP.html5videoplayer', 'PiVideoplayer', [
	'Videoplayer' => 'list,overview,detail',
], [
	'Videoplayer' => 'overview'
]);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['html5videoplayer_div'] = 'HVP\\Html5videoplayer\\Div';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['html5videoplayer_vimeo'] = 'HVP\\html5videoplayer\\Hooks\\VimeoProcessDatamap';

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['list_type_Info']['html5videoplayer_pivideoplayer']['html5videoplayer'] = 'HVP\\Html5videoplayer\\Hooks\\CmsLayout->getExtensionSummary';
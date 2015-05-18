<?php

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}
return array(
	'ctrl'      => array(
		'title'     => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:video_relation',
		'hideTable' => TRUE,
		'sortby'    => 'sorting',
		'iconfile'  => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('html5videoplayer') . 'Resources/Public/Icons/VideoContent.png',
	),
	'interface' => array(
		'showRecordFieldList' => 'content_uid,video_uid'
	),
	'columns'   => array(
		'content_uid' => Array(
			'label'  => 'CC',
			'config' => Array(
				'type'          => 'select',
				'foreign_table' => 'tt_content',
				'size'          => 1,
				'minitems'      => 0,
				'maxitems'      => 1,
			),
		),
		'video_uid'   => Array(
			'label'  => 'Vid',
			'config' => Array(
				'type'                => 'select',
				'foreign_table'       => 'tx_html5videoplayer_domain_model_video',
				'foreign_table_where' => ' AND sys_language_uid IN (0,-1)',
				'size'                => 1,
				'minitems'            => 0,
				'maxitems'            => 1,
			),
		),
	),
	'types'     => array(
		'0' => array('showitem' => 'video_uid,content_uid')
	),
	'palettes'  => array()
);
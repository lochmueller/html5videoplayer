<?php

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

$tca = array(
	'ctrl'      => array(
		'title'                    => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:videos',
		'label'                    => 'title',
		'tstamp'                   => 'tstamp',
		'crdate'                   => 'crdate',
		'cruser_id'                => 'cruser_id',
		'languageField'            => 'sys_language_uid',
		'transOrigPointerField'    => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'sortby'                   => 'sorting',
		'delete'                   => 'deleted',
		'dividers2tabs'            => 1,
		'enablecolumns'            => array(
			'disabled'  => 'hidden',
			'starttime' => 'starttime',
			'endtime'   => 'endtime',
			'fe_group'  => 'fe_group',
		),
		'iconfile'                 => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('html5videoplayer') . 'Resources/Public/Icons/Video.png',
	),
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,starttime,endtime,fe_group,title,posterimage,mp4source,webmsource,oggsource,height,width,downloadlinks,supportvideojs,preloadvideo,autoplayvideo,loopvideo'
	),
	'columns'   => array(
		'sys_language_uid' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.language',
			'config'  => array(
				'type'                => 'select',
				'foreign_table'       => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items'               => array(
					array(
						'LLL:EXT:lang/locallang_general.xml:LGL.allLanguages',
						-1
					),
					array(
						'LLL:EXT:lang/locallang_general.xml:LGL.default_value',
						0
					)
				)
			)
		),
		'l10n_parent'      => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude'     => 1,
			'label'       => 'LLL:EXT:lang/locallang_general.xml:LGL.l18n_parent',
			'config'      => array(
				'type'                => 'select',
				'items'               => array(
					array(
						'',
						0
					),
				),
				'foreign_table'       => 'tx_html5videoplayer_domain_model_video',
				'foreign_table_where' => 'AND tx_html5videoplayer_domain_model_video.pid=###CURRENT_PID### AND tx_html5videoplayer_domain_model_video.sys_language_uid IN (-1,0)',
			)
		),
		'l10n_diffsource'  => array(
			'config' => array(
				'type' => 'passthrough'
			)
		),
		'hidden'           => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array(
				'type'    => 'check',
				'default' => '0'
			)
		),
		'starttime'        => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.starttime',
			'config'  => array(
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				'eval'     => 'date',
				'default'  => '0',
				'checkbox' => '0'
			)
		),
		'endtime'          => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.endtime',
			'config'  => array(
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				'eval'     => 'date',
				'checkbox' => '0',
				'default'  => '0',
				'range'    => array(
					'upper' => mktime(3, 14, 7, 1, 19, 2038),
					'lower' => mktime(0, 0, 0, date('m') - 1, date('d'), date('Y'))
				)
			)
		),
		'fe_group'         => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.fe_group',
			'config'  => array(
				'type'          => 'select',
				'items'         => array(
					array(
						'',
						0
					),
					array(
						'LLL:EXT:lang/locallang_general.xml:LGL.hide_at_login',
						-1
					),
					array(
						'LLL:EXT:lang/locallang_general.xml:LGL.any_login',
						-2
					),
					array(
						'LLL:EXT:lang/locallang_general.xml:LGL.usergroups',
						'--div--'
					)
				),
				'foreign_table' => 'fe_groups'
			)
		),
		'title'            => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:tx_html5videoplayer_domain_model_video.title',
			'config'  => array(
				'type' => 'input',
				'size' => '30',
				// 'eval' => 'required',
			)
		),
		'posterimage'      => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:tx_html5videoplayer_domain_model_video.posterimage',
			'config'  => array(
				'type'     => 'input',
				'size'     => '100',
				'max'      => '255',
				'checkbox' => '',
				'eval'     => 'trim',
				'wizards'  => array(
					'_PADDING' => 2,
					'link'     => array(
						'type'         => 'popup',
						'title'        => 'Link',
						'icon'         => 'link_popup.gif',
 						'module' => array(
 							'name' => 'wizard_element_browser',
 							'urlParameters' => array(
 								'mode' => 'wizard',
 								'act' => 'file'
 							)
 						),
						'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
					)
				)
			)
		),
		'mp4source'        => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:tx_html5videoplayer_domain_model_video.mp4source',
			'config'  => array(
				'type'     => 'input',
				'size'     => '100',
				'max'      => '255',
				'checkbox' => '',
				'eval'     => 'trim',
				'wizards'  => array(
					'_PADDING' => 2,
					'link'     => array(
						'type'         => 'popup',
						'title'        => 'Link',
						'icon'         => 'link_popup.gif',
						'module' => array(
							'name' => 'wizard_element_browser',
							'urlParameters' => array(
								'mode' => 'wizard',
								'act' => 'file'
							)
						),
						'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
					)
				)
			)
		),
		'webmsource'       => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:tx_html5videoplayer_domain_model_video.webmsource',
			'config'  => array(
				'type'     => 'input',
				'size'     => '100',
				'max'      => '255',
				'checkbox' => '',
				'eval'     => 'trim',
				'wizards'  => array(
					'_PADDING' => 2,
					'link'     => array(
						'type'         => 'popup',
						'title'        => 'Link',
						'icon'         => 'link_popup.gif',
						'module' => array(
							'name' => 'wizard_element_browser',
							'urlParameters' => array(
								'mode' => 'wizard',
								'act' => 'file'
							)
						),
						'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
					)
				)
			)
		),
		'youtube'          => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:tx_html5videoplayer_domain_model_video.youtube',
			'config'  => array(
				'type'     => 'input',
				'size'     => '100',
				'max'      => '255',
				'checkbox' => '',
				'eval'     => 'trim',
				'wizards'  => array(
					'_PADDING' => 2,
					'link'     => array(
						'type'         => 'popup',
						'title'        => 'Link',
						'icon'         => 'link_popup.gif',
						'module' => array(
							'name' => 'wizard_element_browser',
							'urlParameters' => array(
								'mode' => 'wizard',
								'act' => 'file'
							)
						),
						'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
					)
				)
			)
		),
		'oggsource'        => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:tx_html5videoplayer_domain_model_video.oggsource',
			'config'  => array(
				'type'     => 'input',
				'size'     => '100',
				'max'      => '255',
				'checkbox' => '',
				'eval'     => 'trim',
				'wizards'  => array(
					'_PADDING' => 2,
					'link'     => array(
						'type'         => 'popup',
						'title'        => 'Link',
						'icon'         => 'link_popup.gif',
						'module' => array(
							'name' => 'wizard_element_browser',
							'urlParameters' => array(
								'mode' => 'wizard',
								'act' => 'file'
							)
						),
						'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
					)
				)
			)
		),
		'height'           => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:tx_html5videoplayer_domain_model_video.height',
			'config'  => array(
				'type' => 'input',
				'size' => '10',
			)
		),
		'width'            => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:tx_html5videoplayer_domain_model_video.width',
			'config'  => array(
				'type' => 'input',
				'size' => '10',
			)
		),
		'downloadlinks'    => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:tx_html5videoplayer_domain_model_video.downloadlinks',
			'config'  => array(
				'type'    => 'check',
				'default' => 0,
			)
		),
		'supportvideojs'   => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:tx_html5videoplayer_domain_model_video.supportvideojs',
			'config'  => array(
				'type'    => 'check',
				'default' => 0,
			)
		),
		'preloadvideo'     => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:tx_html5videoplayer_domain_model_video.preloadvideo',
			'config'  => array(
				'type'  => 'select',
				'items' => array(
					array(
						'Auto',
						"auto"
					),
					array(
						'metadata',
						"metadata"
					),
					array(
						'None',
						"none"
					),
				),
			)
		),
		'autoplayvideo'    => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:tx_html5videoplayer_domain_model_video.autoplayvideo',
			'config'  => array(
				'type' => 'check',
			)
		),
		'loopvideo'        => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:tx_html5videoplayer_domain_model_video.loopvideo',
			'config'  => array(
				'type' => 'check',
			)
		),
		'controlsvideo'    => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:tx_html5videoplayer_domain_model_video.controlsvideo',
			'config'  => array(
				'type'    => 'check',
				'default' => 1,
			)
		),
		'description'      => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:description',
			'config'  => array(
				'type'    => 'text',
				'cols'    => '48',
				'rows'    => '5',
				'softref' => 'typolink_tag,images,email[subst],url'
			)
		),
		'vimeo'            => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:tx_html5videoplayer_domain_model_video.vimeo',
			'config'  => array(
				'type'     => 'input',
				'size'     => '100',
				'max'      => '255',
				'checkbox' => '',
				'eval'     => 'trim',
				'wizards'  => array(
					'_PADDING' => 2,
					'link'     => array(
						'type'         => 'popup',
						'title'        => 'Link',
						'icon'         => 'link_popup.gif',
						'module' => array(
							'name' => 'wizard_element_browser',
							'urlParameters' => array(
								'mode' => 'wizard',
								'act' => 'file'
							)
						),
						'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
					)
				)
			),
		),
	),
	'types'     => array(
		'0' => array('showitem' => '--div--;LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:video,title;;1,width, height,posterimage, mp4source, webmsource, oggsource, youtube,--div--;LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:configurations, downloadlinks, supportvideojs, preloadvideo, autoplayvideo, loopvideo, controlsvideo, --div--;LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:description, description;;;richtext:rte_transform[flag=rte_enabled|mode=ts_css]')

	),
	'palettes'  => array(
		'1' => array('showitem' => 'hidden,sys_language_uid, l10n_parent, l10n_diffsource,starttime, endtime, fe_group'),

	)
);

if (\HVP\Html5videoplayer\Div::featureEnable('vimeo')) {
	$tca['types']['0'] = array('showitem' => '--div--;LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:video,title;;1,width, height,posterimage, mp4source, webmsource, oggsource, youtube,vimeo,--div--;LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:configurations, downloadlinks, supportvideojs, preloadvideo, autoplayvideo, loopvideo, controlsvideo, --div--;LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:description, description;;;richtext:rte_transform[flag=rte_enabled|mode=ts_css]');
}

return $tca;

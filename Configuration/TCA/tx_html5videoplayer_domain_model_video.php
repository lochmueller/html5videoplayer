<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$tca = [
    'ctrl'      => [
        'title'                    => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:videos',
        'label'                    => 'title',
        'tstamp'                   => 'tstamp',
        'crdate'                   => 'crdate',
        'cruser_id'                => 'cruser_id',
        'languageField'            => 'sys_language_uid',
        'transOrigPointerTable'    => '',
        'transOrigPointerField'    => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'sortby'                   => 'sorting',
        'delete'                   => 'deleted',
        'dividers2tabs'            => 1,
        'enablecolumns'            => [
            'disabled'  => 'hidden',
            'starttime' => 'starttime',
            'endtime'   => 'endtime',
            'fe_group'  => 'fe_group',
        ],
        'iconfile'                 => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('html5videoplayer') . 'Resources/Public/Icons/Video.png',
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,starttime,endtime,fe_group,title,posterimage,mp4source,webmsource,oggsource,height,width,downloadlinks,supportvideojs,preloadvideo,autoplayvideo, mutevideo,loopvideo'
    ],
    'columns'   => [
        'sys_language_uid' => [
            'exclude' => 1,
            'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.language',
            'config'  => [
                'type'       => 'select',
                'special'    => 'languages',
                'renderType' => 'selectSingle',
            ]
        ],
        'l10n_parent'      => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude'     => 1,
            'label'       => 'LLL:EXT:lang/locallang_general.xml:LGL.l18n_parent',
            'config'      => [
                'type'                => 'select',
                'renderType'          => 'selectSingle',
                'items'               => [
                    [
                        '',
                        0
                    ],
                ],
                'foreign_table'       => 'tx_html5videoplayer_domain_model_video',
                'foreign_table_where' => 'AND tx_html5videoplayer_domain_model_video.pid=###CURRENT_PID### AND tx_html5videoplayer_domain_model_video.sys_language_uid IN (-1,0)',
            ]
        ],
        'l10n_diffsource'  => [
            'config' => [
                'type' => 'passthrough'
            ]
        ],
        'hidden'           => [
            'exclude' => 1,
            'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
            'config'  => [
                'type'    => 'check',
                'default' => '0'
            ]
        ],
        'starttime'        => [
            'exclude' => 1,
            'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.starttime',
            'config'  => [
                'type'     => 'input',
                'size'     => '8',
                'max'      => '20',
                'eval'     => 'date',
                'default'  => '0',
                'checkbox' => '0'
            ]
        ],
        'endtime'          => [
            'exclude' => 1,
            'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.endtime',
            'config'  => [
                'type'     => 'input',
                'size'     => '8',
                'max'      => '20',
                'eval'     => 'date',
                'checkbox' => '0',
                'default'  => '0',
                'range'    => [
                    'upper' => mktime(3, 14, 7, 1, 19, 2038),
                    'lower' => mktime(0, 0, 0, date('m') - 1, date('d'), date('Y'))
                ]
            ]
        ],
        'fe_group' => $GLOBALS['TCA']['tt_content']['columns']['fe_group'],
        'title'            => [
            'exclude' => 0,
            'label'   => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:tx_html5videoplayer_domain_model_video.title',
            'config'  => [
                'type' => 'input',
                'size' => '30',
                // 'eval' => 'required',
            ]
        ],
        'posterimage'      => [
            'exclude' => 1,
            'label'   => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:tx_html5videoplayer_domain_model_video.posterimage',
            'config'  => [
                'type'     => 'input',
                'size'     => '100',
                'max'      => '255',
                'checkbox' => '',
                'eval'     => 'trim',
                'wizards'  => [
                    '_PADDING' => 2,
                    'link'     => [
                        'type'         => 'popup',
                        'title'        => 'Link',
                        'icon'         => 'link_popup.gif',
                        'module'       => [
                            'name'          => 'wizard_element_browser',
                            'urlParameters' => [
                                'mode' => 'wizard',
                                'act'  => 'file'
                            ]
                        ],
                        'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
                    ]
                ]
            ]
        ],
        'mp4source'        => [
            'exclude' => 1,
            'label'   => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:tx_html5videoplayer_domain_model_video.mp4source',
            'config'  => [
                'type'     => 'input',
                'size'     => '100',
                'max'      => '255',
                'checkbox' => '',
                'eval'     => 'trim',
                'wizards'  => [
                    '_PADDING' => 2,
                    'link'     => [
                        'type'         => 'popup',
                        'title'        => 'Link',
                        'icon'         => 'link_popup.gif',
                        'module'       => [
                            'name'          => 'wizard_element_browser',
                            'urlParameters' => [
                                'mode' => 'wizard',
                                'act'  => 'file'
                            ]
                        ],
                        'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
                    ]
                ]
            ]
        ],
        'webmsource'       => [
            'exclude' => 1,
            'label'   => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:tx_html5videoplayer_domain_model_video.webmsource',
            'config'  => [
                'type'     => 'input',
                'size'     => '100',
                'max'      => '255',
                'checkbox' => '',
                'eval'     => 'trim',
                'wizards'  => [
                    '_PADDING' => 2,
                    'link'     => [
                        'type'         => 'popup',
                        'title'        => 'Link',
                        'icon'         => 'link_popup.gif',
                        'module'       => [
                            'name'          => 'wizard_element_browser',
                            'urlParameters' => [
                                'mode' => 'wizard',
                                'act'  => 'file'
                            ]
                        ],
                        'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
                    ]
                ]
            ]
        ],
        'youtube'          => [
            'exclude' => 1,
            'label'   => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:tx_html5videoplayer_domain_model_video.youtube',
            'config'  => [
                'type'     => 'input',
                'size'     => '100',
                'max'      => '255',
                'checkbox' => '',
                'eval'     => 'trim',
                'wizards'  => [
                    '_PADDING' => 2,
                    'link'     => [
                        'type'         => 'popup',
                        'title'        => 'Link',
                        'icon'         => 'link_popup.gif',
                        'module'       => [
                            'name'          => 'wizard_element_browser',
                            'urlParameters' => [
                                'mode' => 'wizard',
                                'act'  => 'file'
                            ]
                        ],
                        'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
                    ]
                ]
            ]
        ],
        'oggsource'        => [
            'exclude' => 1,
            'label'   => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:tx_html5videoplayer_domain_model_video.oggsource',
            'config'  => [
                'type'     => 'input',
                'size'     => '100',
                'max'      => '255',
                'checkbox' => '',
                'eval'     => 'trim',
                'wizards'  => [
                    '_PADDING' => 2,
                    'link'     => [
                        'type'         => 'popup',
                        'title'        => 'Link',
                        'icon'         => 'link_popup.gif',
                        'module'       => [
                            'name'          => 'wizard_element_browser',
                            'urlParameters' => [
                                'mode' => 'wizard',
                                'act'  => 'file'
                            ]
                        ],
                        'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
                    ]
                ]
            ]
        ],
        'height'           => [
            'exclude' => 1,
            'label'   => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:tx_html5videoplayer_domain_model_video.height',
            'config'  => [
                'type' => 'input',
                'size' => '10',
            ]
        ],
        'width'            => [
            'exclude' => 1,
            'label'   => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:tx_html5videoplayer_domain_model_video.width',
            'config'  => [
                'type' => 'input',
                'size' => '10',
            ]
        ],
        'downloadlinks'    => [
            'exclude' => 1,
            'label'   => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:tx_html5videoplayer_domain_model_video.downloadlinks',
            'config'  => [
                'type'    => 'check',
                'default' => 0,
            ]
        ],
        'supportvideojs'   => [
            'exclude' => 1,
            'label'   => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:tx_html5videoplayer_domain_model_video.supportvideojs',
            'config'  => [
                'type'    => 'check',
                'default' => 0,
            ]
        ],
        'preloadvideo'     => [
            'exclude' => 1,
            'label'   => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:tx_html5videoplayer_domain_model_video.preloadvideo',
            'config'  => [
                'type'       => 'select',
                'renderType' => 'selectSingle',
                'items'      => [
                    [
                        'Auto',
                        "auto"
                    ],
                    [
                        'metadata',
                        "metadata"
                    ],
                    [
                        'None',
                        "none"
                    ],
                ],
            ]
        ],
        'autoplayvideo'    => [
            'exclude' => 1,
            'label'   => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:tx_html5videoplayer_domain_model_video.autoplayvideo',
            'config'  => [
                'type' => 'check',
            ]
        ],
        'mutevideo'    => [
            'exclude' => 1,
            'label'   => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:tx_html5videoplayer_domain_model_video.mutevideo',
            'config'  => [
                'type' => 'check',
            ]
        ],
        'loopvideo'        => [
            'exclude' => 1,
            'label'   => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:tx_html5videoplayer_domain_model_video.loopvideo',
            'config'  => [
                'type' => 'check',
            ]
        ],
        'video_starttime'  => [
            'exclude' => 1,
            'label'   => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:tx_html5videoplayer_domain_model_video.video_starttime',
            'config'  => [
                'type' => 'input',
                'eval' => 'int'
            ]
        ],
        'controlsvideo'    => [
            'exclude' => 1,
            'label'   => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:tx_html5videoplayer_domain_model_video.controlsvideo',
            'config'  => [
                'type'    => 'check',
                'default' => 1,
            ]
        ],
        'description'      => [
            'exclude' => 1,
            'label'   => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:description',
            'config'  => [
                'type'    => 'text',
                'cols'    => '48',
                'rows'    => '5',
                'softref' => 'typolink_tag,images,email[subst],url'
            ]
        ],
        'vimeo'            => [
            'exclude' => 1,
            'label'   => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:tx_html5videoplayer_domain_model_video.vimeo',
            'config'  => [
                'type'     => 'input',
                'size'     => '100',
                'max'      => '255',
                'checkbox' => '',
                'eval'     => 'trim',
                'wizards'  => [
                    '_PADDING' => 2,
                    'link'     => [
                        'type'         => 'popup',
                        'title'        => 'Link',
                        'icon'         => 'link_popup.gif',
                        'module'       => [
                            'name'          => 'wizard_element_browser',
                            'urlParameters' => [
                                'mode' => 'wizard',
                                'act'  => 'file'
                            ]
                        ],
                        'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
                    ]
                ]
            ],
        ],
    ],
    'types'     => [
        '0' => ['showitem' => '--div--;LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:video,title;;1,width, height,posterimage, mp4source, webmsource, oggsource, youtube,--div--;LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:configurations, downloadlinks, supportvideojs, preloadvideo, autoplayvideo, mutevideo, loopvideo, video_starttime, controlsvideo, --div--;LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:description, description;;;richtext:rte_transform[flag=rte_enabled|mode=ts_css]']

    ],
    'palettes'  => [
        '1' => ['showitem' => 'hidden,sys_language_uid, l10n_parent, l10n_diffsource,starttime, endtime, fe_group'],

    ]
];

if (\HVP\Html5videoplayer\Div::featureEnable('vimeo')) {
    $tca['types']['0'] = ['showitem' => '--div--;LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:video,title;;1,width, height,posterimage, mp4source, webmsource, oggsource, youtube,vimeo,--div--;LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:configurations, downloadlinks, supportvideojs, preloadvideo, autoplayvideo, mutevideo, loopvideo, video_starttime, controlsvideo, --div--;LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xml:description, description;;;richtext:rte_transform[flag=rte_enabled|mode=ts_css]'];
}

return $tca;

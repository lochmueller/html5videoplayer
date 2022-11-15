<?php

use HVP\Html5videoplayer\Div;

$tca = [
    'ctrl' => [
        'title' => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xlf:videos',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'languageField' => 'sys_language_uid',
        'transOrigPointerTable' => '',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'sortby' => 'sorting',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
            'fe_group' => 'fe_group',
        ],
        'iconfile' => 'EXT:html5videoplayer/Resources/Public/Icons/Video.png',
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,starttime,endtime,fe_group,title,posterimage,mp4source,webmsource,oggsource,height,width,downloadlinks,supportvideojs,preloadvideo,autoplayvideo, mutevideo,loopvideo'
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'sys_language',
                'foreign_table_where' => 'ORDER BY sys_language.title',
                'items' => [
                    ['LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages', -1],
                    ['LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.default_value', 0]
                ],
            ]
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        '',
                        0
                    ],
                ],
                'foreign_table' => 'tx_html5videoplayer_domain_model_video',
                'foreign_table_where' => 'AND tx_html5videoplayer_domain_model_video.pid=###CURRENT_PID### AND tx_html5videoplayer_domain_model_video.sys_language_uid IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
                'default' => ''
            ]
        ],
        'l10n_source' => [
            'config' => [
                'type' => 'passthrough'
            ]
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'default' => 0,
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'invertStateDisplay' => true
                    ],
                ],
            ]
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ]
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038)
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ]
        ],
        'fe_group' => $GLOBALS['TCA']['tt_content']['columns']['fe_group'],
        'title' => [
            'exclude' => false,
            'label' => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xlf:tx_html5videoplayer_domain_model_video.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
            ]
        ],
        'posterimage' => [
            'exclude' => true,
            'label' => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xlf:tx_html5videoplayer_domain_model_video.posterimage',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
                'size' => 100,
                'eval' => 'trim',
                'fieldControl' => [
                    'linkPopup' => [
                        'options' => [
                            'blindLinkOptions' => 'mail,page,spec,url,folder',
                            'blindLinkFields' => 'class,params,target,title',
                            'allowedExtensions' => 'jpg,png,jpeg,gif',
                        ],
                    ],
                ],
            ]
        ],
        'mp4source' => [
            'exclude' => true,
            'label' => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xlf:tx_html5videoplayer_domain_model_video.mp4source',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
                'size' => 100,
                'eval' => 'trim',
                'fieldControl' => [
                    'linkPopup' => [
                        'options' => [
                            'blindLinkOptions' => 'mail,page,spec,url,folder',
                            'blindLinkFields' => 'class,params,target,title',
                            'allowedExtensions' => 'mp4',
                        ],
                    ],
                ],
            ]
        ],
        'webmsource' => [
            'exclude' => true,
            'label' => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xlf:tx_html5videoplayer_domain_model_video.webmsource',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
                'size' => 100,
                'eval' => 'trim',
                'fieldControl' => [
                    'linkPopup' => [
                        'options' => [
                            'blindLinkOptions' => 'mail,page,spec,url,folder',
                            'blindLinkFields' => 'class,params,target,title',
                            'allowedExtensions' => 'webm',
                        ],
                    ],
                ],
            ]
        ],
        'oggsource' => [
            'exclude' => true,
            'label' => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xlf:tx_html5videoplayer_domain_model_video.oggsource',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
                'size' => 100,
                'eval' => 'trim',
                'fieldControl' => [
                    'linkPopup' => [
                        'options' => [
                            'blindLinkOptions' => 'mail,page,spec,url,folder',
                            'blindLinkFields' => 'class,params,target,title',
                            'allowedExtensions' => 'ogg,ogv',
                        ],
                    ],
                ],
            ]
        ],
        'youtube' => [
            'exclude' => true,
            'label' => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xlf:tx_html5videoplayer_domain_model_video.youtube',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
                'size' => 100,
                'eval' => 'trim',
                'fieldControl' => [
                    'linkPopup' => [
                        'options' => [
                            'blindLinkOptions' => 'mail,page,spec,file,folder',
                            'blindLinkFields' => 'class,params,target,title',
                        ],
                    ],
                ],
            ]
        ],
        'height' => [
            'exclude' => true,
            'label' => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xlf:tx_html5videoplayer_domain_model_video.height',
            'config' => [
                'type' => 'input',
                'size' => 10,
            ]
        ],
        'width' => [
            'exclude' => true,
            'label' => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xlf:tx_html5videoplayer_domain_model_video.width',
            'config' => [
                'type' => 'input',
                'size' => 10,
            ]
        ],
        'downloadlinks' => [
            'exclude' => true,
            'label' => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xlf:tx_html5videoplayer_domain_model_video.downloadlinks',
            'config' => [
                'type' => 'check',
                'default' => 0,
            ]
        ],
        'supportvideojs' => [
            'exclude' => true,
            'label' => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xlf:tx_html5videoplayer_domain_model_video.supportvideojs',
            'config' => [
                'type' => 'check',
                'default' => 0,
            ]
        ],
        'preloadvideo' => [
            'exclude' => true,
            'label' => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xlf:tx_html5videoplayer_domain_model_video.preloadvideo',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        'Auto',
                        'auto'
                    ],
                    [
                        'metadata',
                        'metadata'
                    ],
                    [
                        'None',
                        'none'
                    ],
                ],
            ]
        ],
        'autoplayvideo' => [
            'exclude' => true,
            'label' => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xlf:tx_html5videoplayer_domain_model_video.autoplayvideo',
            'config' => [
                'type' => 'check',
            ]
        ],
        'mutevideo' => [
            'exclude' => true,
            'label' => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xlf:tx_html5videoplayer_domain_model_video.mutevideo',
            'config' => [
                'type' => 'check',
            ]
        ],
        'loopvideo' => [
            'exclude' => true,
            'label' => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xlf:tx_html5videoplayer_domain_model_video.loopvideo',
            'config' => [
                'type' => 'check',
            ]
        ],
        'video_starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xlf:tx_html5videoplayer_domain_model_video.video_starttime',
            'config' => [
                'type' => 'input',
                'eval' => 'int'
            ]
        ],
        'controlsvideo' => [
            'exclude' => true,
            'label' => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xlf:tx_html5videoplayer_domain_model_video.controlsvideo',
            'config' => [
                'type' => 'check',
                'default' => 1,
            ]
        ],
        'description' => [
            'exclude' => true,
            'label' => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xlf:description',
            'config' => [
                'type' => 'text',
                'cols' => 48,
                'rows' => 5,
                'enableRichtext' => true,
            ]
        ],
        'vimeo' => [
            'exclude' => true,
            'label' => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xlf:tx_html5videoplayer_domain_model_video.vimeo',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
                'size' => 100,
                'eval' => 'trim',
                'fieldControl' => [
                    'linkPopup' => [
                        'options' => [
                            'blindLinkOptions' => 'mail,page,spec,file,folder',
                            'blindLinkFields' => 'class,params,target,title',
                        ],
                    ],
                ],
            ],
        ],
    ],
    'types' => [
        '0' => ['showitem' => '--div--;LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xlf:video,title,--palette--;;1,width, height,posterimage, mp4source, webmsource, oggsource, youtube,--div--;LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xlf:configurations, downloadlinks, supportvideojs, preloadvideo, autoplayvideo, mutevideo, loopvideo, video_starttime, controlsvideo, --div--;LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xlf:description, description']

    ],
    'palettes' => [
        '1' => ['showitem' => 'hidden,sys_language_uid,l10n_parent,l10n_diffsource,starttime,endtime,fe_group'],
    ]
];

if (Div::featureEnable('vimeo')) {
    $tca['types']['0'] = ['showitem' => '--div--;LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xlf:video,title,--palette--;;1,width, height,posterimage, mp4source, webmsource, oggsource, youtube,vimeo,--div--;LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xlf:configurations, downloadlinks, supportvideojs, preloadvideo, autoplayvideo, mutevideo, loopvideo, video_starttime, controlsvideo, --div--;LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xlf:description, description'];
}

return $tca;

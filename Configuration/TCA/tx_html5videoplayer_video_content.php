<?php

return [
    'ctrl' => [
        'label' => 'Video/Content Relation',
        'title' => 'LLL:EXT:html5videoplayer/Resources/Private/Language/locallang.xlf:video_relation',
        'hideTable' => true,
        'sortby' => 'sorting',
        'iconfile' => 'EXT:html5videoplayer/Resources/Public/Icons/VideoContent.png',
    ],
    'columns' => [
        'content_uid' => [
            'label' => 'Content Element',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingleBox',
                'foreign_table' => 'tt_content',
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
        'video_uid' => [
            'label' => 'Video',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_html5videoplayer_domain_model_video',
                'foreign_table_where' => ' AND tx_html5videoplayer_domain_model_video.sys_language_uid IN (0,-1)',
                'size' => 3,
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
    ],
    'types' => [
        '0' => ['showitem' => 'video_uid,content_uid']
    ],
    'palettes' => []
];

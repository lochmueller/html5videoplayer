<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "html5videoplayer".
 * Auto generated 18-05-2015 11:08
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = [
    'title' => 'HTML5 Video Player',
    'description' => 'A video extension for TYPO3 built on the VideoJS HTML5 video player library. Allows you to embed video in your website using HTML5 with Flash fallback support for non-HTML5 browsers. Work on VideoJS 4.12.6 and support YouTube and Vimeo video in the same style.',
    'category' => 'plugin',
    'version' => '11.1.0',
    'state' => 'stable',
    'author' => 'Tim Lochmueller',
    'author_email' => 'tim@fruit-lab.de',
    'constraints' => [
        'depends' => [
            'php' => '8.1.0-8.4.0',
            'typo3' => '12.4.0-12.4.99'
        ],
    ],
];

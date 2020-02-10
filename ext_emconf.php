<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'PageSpeed Insights',
    'description' => 'Check the performance of your pages in TYPO3 with PageSpeed Insights.',
    'category' => 'fe',
    'author' => 'Richard Haeser',
    'author_email' => 'richardhaeser@gmail.com',
    'author_company' => '',
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'version' => '2.0.0-dev',
    'constraints' => [
        'depends' => [
            'typo3' => '9.5.0 - 10.4.99'
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];

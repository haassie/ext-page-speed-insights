<?php
$llPrefix = 'LLL:EXT:page_speed_insights/Resources/Private/Language/locallang_tca.xlf:';

return [
    'ctrl' => [
        'title' => $llPrefix . 'tx_pagespeedinsights_results.title',
        'label' => 'date',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'languageField' => 'sys_language_uid',
        'sortby' => 'tstamp desc',
        'iconfile' => 'EXT:page_speed_insights/Resources/Public/Icons/Extension.png',
        'delete' => 'deleted',
        'versioningWS' => true,
    ],
    'columns' => [
        'date' => [
            'exclude' => true,
            'label' => $llPrefix . 'tx_pagespeedinsights_results.date',
            'config' => [
                'type' => 'input',
                'eval' => 'trim'
            ]
        ],
        'mobile_score_performance' => [
            'exclude' => true,
            'label' => $llPrefix . 'tx_pagespeedinsights_results.mobile_score_performance',
            'config' => [
                'type' => 'input',
                'eval' => 'trim'
            ]
        ],
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
                'default' => 0,
                'fieldWizard' => [
                    'selectIcons' => [
                        'disabled' => false,
                    ],
                ],
            ]
        ],
    ],
    'palettes' => [
        'general' => [
            'showitem' => 'date'
        ],
        'mobile' => [
            'showitem' => 'mobile_score_performance'
        ],
    ],
    'types' => [
        '0' => [
            'showitem' => '
                --div--;Results,
                    --palette;;general,
                    --palette;;mobile,
                --div--;Visibility, sys_language_uid,
            '
        ]
    ],
];

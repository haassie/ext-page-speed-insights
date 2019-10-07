<?php
$llPrefix = 'LLL:EXT:page_speed_insights/Resources/Private/Language/locallang_tca.xlf:';

return [
    'ctrl' => [
        'title' => $llPrefix . 'tx_pagespeedinsights_results.title',
        'label' => 'date',
        'label_alt' => 'strategy',
        'label_alt_force' => true,
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
                'renderType' => 'inputDateTime',
                'readOnly' => true,
                'eval' => 'datetime,trim'
            ]
        ],
        'strategy' => [
            'exclude' => true,
            'label' => $llPrefix . 'tx_pagespeedinsights_results.strategy',
            'config' => [
                'type' => 'input',
                'readOnly' => true,
                'eval' => 'trim'
            ]
        ],
        'performance_score' => [
            'exclude' => true,
            'label' => $llPrefix . 'tx_pagespeedinsights_results.performance_score',
            'config' => [
                'type' => 'input',
                'readOnly' => true,
                'eval' => 'trim'
            ]
        ],
        'seo_score' => [
            'exclude' => true,
            'label' => $llPrefix . 'tx_pagespeedinsights_results.seo_score',
            'config' => [
                'type' => 'input',
                'readOnly' => true,
                'eval' => 'trim'
            ]
        ],
        'pwa_score' => [
            'exclude' => true,
            'label' => $llPrefix . 'tx_pagespeedinsights_results.pwa_score',
            'config' => [
                'type' => 'input',
                'readOnly' => true,
                'eval' => 'trim'
            ]
        ],
        'accessibility_score' => [
            'exclude' => true,
            'label' => $llPrefix . 'tx_pagespeedinsights_results.accessibility_score',
            'config' => [
                'type' => 'input',
                'readOnly' => true,
                'eval' => 'trim'
            ]
        ],
        'bestpractices_score' => [
            'exclude' => true,
            'label' => $llPrefix . 'tx_pagespeedinsights_results.bestpractices_score',
            'config' => [
                'type' => 'input',
                'readOnly' => true,
                'eval' => 'trim'
            ]
        ],
        'reference' => [
            'exclude' => true,
            'label' => $llPrefix . 'tx_pagespeedinsights_results.reference',
            'config' => [
                'type' => 'input',
                'readOnly' => true,
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
            'showitem' => 'date, reference, strategy'
        ],
        'score' => [
            'showitem' => 'performance_score, seo_score, accessibility_score, bestpractices_score, pwa_score'
        ],
    ],
    'types' => [
        '0' => [
            'showitem' => '
                --div--;' . $llPrefix . 'tx_pagespeedinsights_results.tab.results,
                    --palette--;;general,
                    --palette--;;score,
                --div--;' . $llPrefix . 'tx_pagespeedinsights_results.tab.language,
                    sys_language_uid,
            '
        ]
    ],
];

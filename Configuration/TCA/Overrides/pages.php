<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

$llPrefix = 'LLL:EXT:page_speed_insights/Resources/Private/Language/locallang_tca.xlf:';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'pages',
    [
        'tx_pagespeedinsights_check' => [
            'label' => $llPrefix . 'pages.tx_pagespeedinsights_check',
            'exclude' => true,
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle'
            ]
        ],
        'tx_pagespeedinsights_results' => [
            'label' => $llPrefix . 'pages.tx_pagespeedinsights_results',
            'exclude' => true,
            'config' => [
                'type' => 'text',
                'renderType' => 'pageSpeedInsightsHistory'
            ]
        ]
    ]
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette('pages', 'pagespeedinsights', 'tx_pagespeedinsights_check, --linebreak--, tx_pagespeedinsights_results');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'pages',
    '
    --div--;PageSpeed Insights,--palette--;;pagespeedinsights',
    '',
    'after: lastUpdated'
);

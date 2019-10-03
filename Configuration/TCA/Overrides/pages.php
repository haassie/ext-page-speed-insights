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
    ]
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'pages',
    '
    --div--;PageSpeed Insights, tx_pagespeedinsights_check',
    '',
    'after: lastUpdated'
);

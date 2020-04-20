<?php
return [
    'dashboardPreset-lighthouse' => [
        'title' => 'LLL:EXT:page_speed_insights/Resources/Private/Language/locallang.xlf:dashboardPresets.psi.title',
        'description' => 'LLL:EXT:page_speed_insights/Resources/Private/Language/locallang.xlf:dashboardPresets.psi.description',
        'iconIdentifier' => 'content-dashboard',
        'defaultWidgets' => [
            'lighthousePerformance',
            'lighthouseSeo',
            'lighthouseBestPractices',
            'lighthouseAccessibility',
            'lighthousePwa',
            'lighthouseScoreHistory'
        ],
        'showInWizard' => true
    ],
];

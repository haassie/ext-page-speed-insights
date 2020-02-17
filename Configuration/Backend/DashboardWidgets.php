<?php
return [
    'lighthouse-performance' => [
        'class' => \Haassie\PageSpeedInsights\Widgets\LighthousePerformanceScoreWidget::class,
        'widgetGroups' => ['lighthouse'],
    ],
    'lighthouse-accessibility' => [
        'class' => \Haassie\PageSpeedInsights\Widgets\LighthouseAccessibilityScoreWidget::class,
        'widgetGroups' => ['lighthouse'],
    ],
    'lighthouse-pwa' => [
        'class' => \Haassie\PageSpeedInsights\Widgets\LighthousePwaScoreWidget::class,
        'widgetGroups' => ['lighthouse'],
    ],
    'lighthouse-bestpractices' => [
        'class' => \Haassie\PageSpeedInsights\Widgets\LighthouseBestPracticesScoreWidget::class,
        'widgetGroups' => ['lighthouse'],
    ],
    'lighthouse-seo' => [
        'class' => \Haassie\PageSpeedInsights\Widgets\LighthouseSeoScoreWidget::class,
        'widgetGroups' => ['lighthouse'],
    ],
    'lighthouse-history' => [
        'class' => \Haassie\PageSpeedInsights\Widgets\LighthouseScoreHistoryWidget::class,
        'widgetGroups' => ['lighthouse'],
    ],
];

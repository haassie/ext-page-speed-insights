<?php

use FriendsOfTYPO3\Dashboard\Registry\WidgetRegistry;
use Haassie\PageSpeedInsights\Widgets\LighthouseAccessibilityScoreWidget;
use Haassie\PageSpeedInsights\Widgets\LighthouseBestPracticesScoreWidget;
use Haassie\PageSpeedInsights\Widgets\LighthousePerformanceScoreWidget;
use Haassie\PageSpeedInsights\Widgets\LighthousePwaScoreWidget;
use Haassie\PageSpeedInsights\Widgets\LighthouseScoreHistoryWidget;
use Haassie\PageSpeedInsights\Widgets\LighthouseSeoScoreWidget;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

defined('TYPO3_MODE') or die();

if (ExtensionManagementUtility::isLoaded('dashboard')) {
    $widgetRegistry = GeneralUtility::makeInstance(WidgetRegistry::class);
    $widgetRegistry->registerWidget('lighthousePerformanceScore', LighthousePerformanceScoreWidget::class);
    $widgetRegistry->registerWidget('lighthouseSeoScore', LighthouseSeoScoreWidget::class);
    $widgetRegistry->registerWidget('lighthouseBestPracticesScore', LighthouseBestPracticesScoreWidget::class);
    $widgetRegistry->registerWidget('lighthousePwaScore', LighthousePwaScoreWidget::class);
    $widgetRegistry->registerWidget('lighthouseAccessibilityScore', LighthouseAccessibilityScoreWidget::class);
    $widgetRegistry->registerWidget('LighthouseScoreHistoryWidget', LighthouseScoreHistoryWidget::class);
}

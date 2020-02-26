<?php
declare(strict_types=1);

namespace Haassie\PageSpeedInsights\Widgets;

/**
 * Class SysLogErrorsWidget
 */
class LighthouseAccessibilityScoreWidget extends AbstractLighthouseDoughnutWidget
{
    protected $title = 'LLL:EXT:page_speed_insights/Resources/Private/Language/locallang.xlf:widgets.accessibility.title';
    protected $description = 'LLL:EXT:page_speed_insights/Resources/Private/Language/locallang.xlf:widgets.accessibility.description';
    protected $fieldToUse = 'accessibility_score';
}

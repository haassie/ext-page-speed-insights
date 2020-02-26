<?php
declare(strict_types=1);

namespace Haassie\PageSpeedInsights\Widgets;

/**
 * Class SysLogErrorsWidget
 */
class LighthousePerformanceScoreWidget extends AbstractLighthouseDoughnutWidget
{
    protected $title = 'LLL:EXT:page_speed_insights/Resources/Private/Language/locallang.xlf:widgets.performance.title';
    protected $description = 'LLL:EXT:page_speed_insights/Resources/Private/Language/locallang.xlf:widgets.performance.description';
    protected $fieldToUse = 'performance_score';
}

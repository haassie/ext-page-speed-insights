<?php
declare(strict_types=1);

namespace Haassie\PageSpeedInsights\Widgets;

/**
 * Class SysLogErrorsWidget
 */
class LighthousePerformanceScoreWidget extends AbstractLighthouseDoughnutWidget
{
    protected $title = 'Performance score';
    protected $fieldToUse = 'performance_score';
}

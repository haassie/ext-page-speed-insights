<?php
declare(strict_types=1);

namespace Haassie\PageSpeedInsights\Widgets;

/**
 * Class SysLogErrorsWidget
 */
class LighthouseAccessibilityScoreWidget extends AbstractLighthouseDoughnutWidget
{
    protected $title = 'Accessibility score';
    protected $fieldToUse = 'accessibility_score';
}

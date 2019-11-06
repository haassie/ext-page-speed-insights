<?php
declare(strict_types=1);

namespace Haassie\PageSpeedInsights\Widgets;

/**
 * Class SysLogErrorsWidget
 */
class LighthouseBestPracticesScoreWidget extends AbstractLighthouseDoughnutWidget
{
    protected $title = 'Best Practices score';
    protected $fieldToUse = 'bestpractices_score';
}

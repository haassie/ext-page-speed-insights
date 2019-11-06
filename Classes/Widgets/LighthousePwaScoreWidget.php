<?php
declare(strict_types=1);

namespace Haassie\PageSpeedInsights\Widgets;

/**
 * Class SysLogErrorsWidget
 */
class LighthousePwaScoreWidget extends AbstractLighthouseDoughnutWidget
{
    protected $title = 'PWA score';
    protected $fieldToUse = 'pwa_score';
}

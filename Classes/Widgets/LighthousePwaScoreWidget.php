<?php
declare(strict_types=1);

namespace Haassie\PageSpeedInsights\Widgets;

/**
 * Class SysLogErrorsWidget
 */
class LighthousePwaScoreWidget extends AbstractLighthouseDoughnutWidget
{
    protected $title = 'LLL:EXT:page_speed_insights/Resources/Private/Language/locallang.xlf:widgets.pwa.title';
    protected $description = 'LLL:EXT:page_speed_insights/Resources/Private/Language/locallang.xlf:widgets.pwa.description';
    protected $fieldToUse = 'pwa_score';
}

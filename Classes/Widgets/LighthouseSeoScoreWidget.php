<?php
declare(strict_types=1);

namespace Haassie\PageSpeedInsights\Widgets;

/**
 * Class SysLogErrorsWidget
 */
class LighthouseSeoScoreWidget extends AbstractLighthouseDoughnutWidget
{
    protected $title = 'LLL:EXT:page_speed_insights/Resources/Private/Language/locallang.xlf:widgets.history.title';
    protected $description = 'LLL:EXT:page_speed_insights/Resources/Private/Language/locallang.xlf:widgets.history.description';
    protected $fieldToUse = 'seo_score';
}

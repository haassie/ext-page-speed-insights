<?php
declare(strict_types=1);

namespace Haassie\PageSpeedInsights\Widgets;

/**
 * Class SysLogErrorsWidget
 */
class LighthouseBestPracticesScoreWidget extends AbstractLighthouseDoughnutWidget
{
    protected $title = 'LLL:EXT:page_speed_insights/Resources/Private/Language/locallang.xlf:widgets.bestPractices.title';
    protected $description = 'LLL:EXT:page_speed_insights/Resources/Private/Language/locallang.xlf:widgets.bestPractices.description';
    protected $fieldToUse = 'bestpractices_score';
}

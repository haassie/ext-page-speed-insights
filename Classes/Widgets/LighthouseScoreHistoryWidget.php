<?php
declare(strict_types=1);

namespace Haassie\PageSpeedInsights\Widgets;

use Haassie\PageSpeedInsights\Utility\PageSpeedInsightsUtility;

class LighthouseScoreHistoryWidget extends AbstractLineChartWidget
{
    /**
     * @var string
     */
    protected $title = 'LLL:EXT:page_speed_insights/Resources/Private/Language/locallang.xlf:widgets.history.title';
    protected $description = 'LLL:EXT:page_speed_insights/Resources/Private/Language/locallang.xlf:widgets.history.description';

    /**
     * @var int
     */
    protected $width = 4;

    /**
     * @var int
     */
    protected $height = 4;

    /**
     * @var string
     */
    protected $strategyToShow = 'mobile';

    protected function prepareChartData(): void
    {
        $this->chartData = PageSpeedInsightsUtility::getChartData(
            31,
            1,
            0,
            $this->strategyToShow,
            $this->chartColors[0],
            $this->chartColors[1],
            $this->chartColors[2],
            $this->chartColors[3],
            $this->chartColors[4]
        );
    }

    protected function prepareData(): void
    {
        $this->chartOptions['legend']['display'] = true;
        $this->chartOptions['legend']['labels']['boxWidth'] = 20;
    }
}

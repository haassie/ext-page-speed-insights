<?php
declare(strict_types=1);

namespace Haassie\PageSpeedInsights\Widgets;

use FriendsOfTYPO3\Dashboard\Widgets\AbstractLineChartWidget;
use Haassie\PageSpeedInsights\Utility\PageSpeedInsightsUtility;

class LighthouseScoreHistoryWidget extends AbstractLineChartWidget
{
    /**
     * @var string
     */
    protected $title = 'Lighthouse History';

    /**
     * @var int
     */
    protected $width = 4;

    /**
     * @var int
     */
    protected $height = 4;

    public function __construct()
    {
        AbstractLineChartWidget::__construct();

        $this->chartOptions['legend']['display'] = true;
        $this->chartOptions['legend']['labels']['boxWidth'] = 20;
    }
    public function prepareData(): void
    {
    }

    protected function prepareChartData(): void
    {
        parent::prepareChartData();

        $this->chartData = PageSpeedInsightsUtility::getChartData(
            31,
            1,
            0,
            $this->chartColors[0],
            $this->chartColors[1],
            $this->chartColors[2],
            $this->chartColors[3],
            $this->chartColors[4]
        );
    }
}

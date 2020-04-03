<?php
declare(strict_types=1);

namespace Haassie\PageSpeedInsights\Widgets\Provider;

use Haassie\PageSpeedInsights\Utility\PageSpeedInsightsUtility;
use TYPO3\CMS\Dashboard\WidgetApi;
use TYPO3\CMS\Dashboard\Widgets\Interfaces\ChartDataProviderInterface;

class ScoreHistoryDataProvider implements ChartDataProviderInterface
{
    public function getChartData(): array
    {
        $score = PageSpeedInsightsUtility::getLastScore('accessibility_score', 0, 'mobile');

        $chartColors = WidgetApi::getDefaultChartColors();

        return PageSpeedInsightsUtility::getChartData(
            31,
            1,
            0,
            'mobile',
            $chartColors[0],
            $chartColors[1],
            $chartColors[2],
            $chartColors[3],
            $chartColors[4]
        );
    }
}

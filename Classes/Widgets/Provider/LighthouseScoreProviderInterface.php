<?php
declare(strict_types=1);

namespace Haassie\PageSpeedInsights\Widgets\Provider;

use Haassie\PageSpeedInsights\Utility\PageSpeedInsightsUtility;
use TYPO3\CMS\Dashboard\WidgetApi;
use TYPO3\CMS\Dashboard\Widgets\Interfaces\ChartDataProviderInterface;

interface LighthouseScoreProviderInterface
{
    public function getChartData(): array;
    public function getScore(): int;
    public function getMetaData(): array;
}

<?php
declare(strict_types=1);

namespace Haassie\PageSpeedInsights\Widgets\Provider;

use TYPO3\CMS\Dashboard\Widgets\Interfaces\ChartDataProviderInterface;

class AccessibilityScoreDataProvider implements ChartDataProviderInterface
{
    public function getChartData(): array
    {
        return [];
    }
}

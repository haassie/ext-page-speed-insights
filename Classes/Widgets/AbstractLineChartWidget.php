<?php
declare(strict_types=1);

namespace Haassie\PageSpeedInsights\Widgets;

use TYPO3\CMS\Dashboard\Widgets\AbstractChartWidget;

/**
 * The AbstractLineChartWidget class is the basic widget class for line charts.
 * Is it possible to extends this class for own widgets.
 * In your class you have to set $this->chartData with the data to display
 * More information can be found in the documentation.
 */
abstract class AbstractLineChartWidget extends AbstractChartWidget
{
    protected $chartType = 'line';

    protected $chartOptions = [
        'maintainAspectRatio' => false,
        'legend' => [
            'display' => true,
            'position' => 'bottom'
        ],
        'scales' => [
            'yAxes' => [
                [
                    'ticks' => [
                        'beginAtZero' => true
                    ]
                ]
            ],
            'xAxes' => [
                [
                    'ticks' => [
                        'maxTicksLimit' => 15
                    ]
                ]
            ]
        ]
    ];
}

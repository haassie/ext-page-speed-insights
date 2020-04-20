<?php
declare(strict_types=1);
namespace Haassie\PageSpeedInsights\Widgets;

use TYPO3\CMS\Dashboard\Widgets\AdditionalCssInterface;
use TYPO3\CMS\Dashboard\Widgets\ButtonProviderInterface;
use TYPO3\CMS\Dashboard\Widgets\ChartDataProviderInterface;
use TYPO3\CMS\Dashboard\Widgets\EventDataInterface;
use TYPO3\CMS\Dashboard\Widgets\RequireJsModuleInterface;
use TYPO3\CMS\Dashboard\Widgets\WidgetConfigurationInterface;
use TYPO3\CMS\Dashboard\Widgets\WidgetInterface;
use TYPO3\CMS\Fluid\View\StandaloneView;

class LighthouseScoreHistoryWidget implements WidgetInterface, EventDataInterface, AdditionalCssInterface, RequireJsModuleInterface
{
    /**
     * @var WidgetConfigurationInterface
     */
    private $configuration;

    /**
     * @var ChartDataProviderInterface
     */
    private $dataProvider;

    /**
     * @var StandaloneView
     */
    private $view;

    /**
     * @var ButtonProviderInterface|null
     */
    private $buttonProvider;

    /**
     * @var array
     */
    private $options;

    public function __construct(
        WidgetConfigurationInterface $configuration,
        ChartDataProviderInterface $dataProvider,
        StandaloneView $view,
        $buttonProvider = null,
        array $options = []
    ) {
        $this->configuration = $configuration;
        $this->dataProvider = $dataProvider;
        $this->view = $view;
        $this->options = $options;
        $this->buttonProvider = $buttonProvider;
    }

    public function renderWidgetContent(): string
    {
        $this->view->setTemplate('Widget/ChartWidget');
        $this->view->assignMultiple([
            'button' => $this->buttonProvider,
            'options' => $this->options,
            'configuration' => $this->configuration,
        ]);
        return $this->view->render();
    }

    public function getEventData(): array
    {
        return [
            'graphConfig' => [
                'type' => 'line',
                'options' => [
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
                ],
                'data' => $this->dataProvider->getChartData(),
            ],
        ];
    }

    public function getCssFiles(): array
    {
        return ['EXT:dashboard/Resources/Public/Css/Contrib/chart.css'];
    }

    public function getRequireJsModules(): array
    {
        return [
            'TYPO3/CMS/Dashboard/Contrib/chartjs',
            'TYPO3/CMS/Dashboard/ChartInitializer',
        ];
    }
}

<?php
declare(strict_types=1);
namespace Haassie\PageSpeedInsights\Widgets;

use Haassie\PageSpeedInsights\Widgets\Provider\LighthouseScoreProviderInterface;
use TYPO3\CMS\Dashboard\Utility\ButtonUtility;
use TYPO3\CMS\Dashboard\Widgets\Interfaces\AdditionalCssInterface;
use TYPO3\CMS\Dashboard\Widgets\Interfaces\ButtonProviderInterface;
use TYPO3\CMS\Dashboard\Widgets\Interfaces\EventDataInterface;
use TYPO3\CMS\Dashboard\Widgets\Interfaces\RequireJsModuleInterface;
use TYPO3\CMS\Dashboard\Widgets\Interfaces\WidgetConfigurationInterface;
use TYPO3\CMS\Dashboard\Widgets\Interfaces\WidgetInterface;
use TYPO3\CMS\Fluid\View\StandaloneView;

class LighthouseScoreWidget implements WidgetInterface, EventDataInterface, AdditionalCssInterface, RequireJsModuleInterface
{
    /**
     * @var WidgetConfigurationInterface
     */
    private $configuration;

    /**
     * @var LighthouseScoreProviderInterface
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
        LighthouseScoreProviderInterface $dataProvider,
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
        $this->view->setTemplate('Widget/LighthouseScoreWidget');
        $this->view->assignMultiple([
            'button' => ButtonUtility::generateButtonConfig($this->buttonProvider),
            'options' => $this->options,
            'configuration' => $this->configuration,
            'score' => $this->dataProvider->getScore(),
            'metadata' => $this->dataProvider->getMetaData()
        ]);
        return $this->view->render();
    }

    public function getEventData(): array
    {
        return [
            'graphConfig' => [
                'type' => 'doughnut',
                'options' => [
                    'maintainAspectRatio' => false,
                    'legend' => [
                        'display' => false
                    ],
                    'cutoutPercentage' => 60
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

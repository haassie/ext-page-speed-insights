<?php
declare(strict_types=1);

namespace Haassie\PageSpeedInsights\Widgets;

use FriendsOfTYPO3\Dashboard\Widgets\AbstractDoughnutChartWidget;

/**
 * Class SysLogErrorsWidget
 */
abstract class AbstractLighthouseDoughnutWidget extends AbstractDoughnutChartWidget
{
    /**
     * @var string
     */
    protected $title = '';

    protected $height = 4;

    protected $score = 0;

    protected $lastCheck = 0;

    protected function prepareChartData(): void
    {
        parent::prepareChartData();

        $this->chartData = $this->getChartData();
    }

    /**
     * @return string
     */
    public function renderWidgetContent(): string
    {
        $this->prepareData();
        $this->initializeView();

        $this->view->assign('title', $this->title);
        $this->view->assign('value', $this->score);
        $this->view->assign('lastCheck', $this->lastCheck);

        return $this->view->render();
    }
    /**
     * @return array
     */
    protected function getChartData(): array
    {
        $labels = ['', ''];
        $data = [$this->score, 100 - $this->score];

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'backgroundColor' => [$this->chartColors[0], '#fff'],
                    'data' => $data
                ]
            ],
        ];
    }
}

<?php
declare(strict_types=1);

namespace Haassie\PageSpeedInsights\Widgets;

use FriendsOfTYPO3\Dashboard\Widgets\AbstractLineChartWidget;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class LighthouseScoreHistoryWidget extends AbstractLineChartWidget
{
    /**
     * @var string
     */
    protected $title = 'Lighthouse History';

    /**
     * @var int
     */
    protected $width = 2;

    /**
     * @var int
     */
    protected $height = 2;

    public function __construct()
    {
        AbstractLineChartWidget::__construct();

        $this->chartOptions['legend']['display'] = true;
        $this->chartOptions['legend']['labels']['boxWidth'] = 20;
    }
    public function prepareData(): void
    {
    }

    /**
     *
     */
    protected function prepareChartData(): void
    {
        parent::prepareChartData();

        $this->chartData = $this->getChartData();
    }

    /**
     * @return array
     */
    protected function getChartData(): array
    {
        $period = 'lastMonth';

        $labels = [];
        $dataPerformance = [];
        $dataSeo = [];
        $dataAccessibility = [];
        $dataBestPractices = [];
        $dataPwa = [];

        if ($period === 'lastWeek') {
            for ($daysBefore=7; $daysBefore--; $daysBefore>0) {
                $labels[] = date('d-m-Y', strtotime('-' . $daysBefore . ' day'));
                $startPeriod = strtotime('-' . $daysBefore . ' day 0:00:00');
                $endPeriod =  strtotime('-' . $daysBefore . ' day 23:59:59');

                $dataPerformance[] = $this->getAverageScoreInPeriod('performance_score', $startPeriod, $endPeriod);
                $dataSeo[] = $this->getAverageScoreInPeriod('seo_score', $startPeriod, $endPeriod);
                $dataAccessibility[] = $this->getAverageScoreInPeriod('accessibility_score', $startPeriod, $endPeriod);
                $dataBestPractices[] = $this->getAverageScoreInPeriod('bestpractices_score', $startPeriod, $endPeriod);
                $dataPwa[] = $this->getAverageScoreInPeriod('pwa_score', $startPeriod, $endPeriod);
            }
        }

        if ($period === 'lastMonth') {
            for ($daysBefore=31; $daysBefore--; $daysBefore>0) {
                $labels[] = date('d-m-Y', strtotime('-' . $daysBefore . ' day'));
                $startPeriod = strtotime('-' . $daysBefore . ' day 0:00:00');
                $endPeriod =  strtotime('-' . $daysBefore . ' day 23:59:59');

                $dataPerformance[] = $this->getAverageScoreInPeriod('performance_score', $startPeriod, $endPeriod);
                $dataSeo[] = $this->getAverageScoreInPeriod('seo_score', $startPeriod, $endPeriod);
                $dataAccessibility[] = $this->getAverageScoreInPeriod('accessibility_score', $startPeriod, $endPeriod);
                $dataBestPractices[] = $this->getAverageScoreInPeriod('bestpractices_score', $startPeriod, $endPeriod);
                $dataPwa[] = $this->getAverageScoreInPeriod('pwa_score', $startPeriod, $endPeriod);
            }
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Performance',
                    'borderColor' => $this->chartColors[0],
                    'backgroundColor' => $this->chartColors[0],
                    'fill' => false,
                    'data' => $dataPerformance
                ],
                [
                    'label' => 'SEO',
                    'borderColor' => $this->chartColors[1],
                    'backgroundColor' => $this->chartColors[1],
                    'fill' => false,
                    'data' => $dataSeo
                ],
                [
                    'label' => 'Accessibility',
                    'borderColor' => $this->chartColors[2],
                    'backgroundColor' => $this->chartColors[2],
                    'fill' => false,
                    'data' => $dataAccessibility
                ],
                [
                    'label' => 'Best practices',
                    'borderColor' => $this->chartColors[3],
                    'backgroundColor' => $this->chartColors[3],
                    'fill' => false,
                    'data' => $dataBestPractices
                ],
                [
                    'label' => 'PWA',
                    'borderColor' => $this->chartColors[4],
                    'backgroundColor' => $this->chartColors[4],
                    'fill' => false,
                    'data' => $dataPwa
                ],
            ]
        ];
    }

    protected function getAverageScoreInPeriod(string $field, int $start, int $end): int
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_pagespeedinsights_results');
        $row = $queryBuilder
            ->select('tstamp')
            ->addSelectLiteral(
                $queryBuilder->expr()->avg($field, 'avg')
            )
            ->from('tx_pagespeedinsights_results')
            ->where(
                $queryBuilder->expr()->gte('tstamp', $start),
                $queryBuilder->expr()->lte('tstamp', $end)
            )
            ->execute()
            ->fetch();

        return (int)$row['avg'];
    }
}

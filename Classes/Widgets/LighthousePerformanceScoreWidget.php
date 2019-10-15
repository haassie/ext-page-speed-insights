<?php
declare(strict_types=1);

namespace Haassie\PageSpeedInsights\Widgets;


use Haassie\PageSpeedInsights\Utility\PageSpeedInsightsUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class SysLogErrorsWidget
 */
class LighthousePerformanceScoreWidget extends AbstractLighthouseDoughnutWidget
{
    /**
     * @var string
     */
    protected $title = 'Performance score';

    public function prepareData(): void
    {
        $lastRun = PageSpeedInsightsUtility::getLastRun();

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_pagespeedinsights_results');
        $data = $queryBuilder
            ->addSelectLiteral(
                $queryBuilder->expr()->avg('performance_score', 'avg_performance')
            )
            ->from('tx_pagespeedinsights_results')
            ->where(
                $queryBuilder->expr()->eq('reference', $queryBuilder->createNamedParameter($lastRun))
            )
            ->execute()
            ->fetch();

        list($mode, $tstamp) = GeneralUtility::trimExplode('-', $lastRun);

        $this->score = (int)$data['avg_performance'];
        $this->lastCheck = (int)$tstamp;
    }
}

<?php
declare(strict_types=1);

namespace Haassie\PageSpeedInsights\Widgets;


use Haassie\PageSpeedInsights\Utility\PageSpeedInsightsUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class SysLogErrorsWidget
 */
class LighthouseBestPracticesScoreWidget extends AbstractLighthouseDoughnutWidget
{
    /**
     * @var string
     */
    protected $title = 'Best Practices score';

    public function prepareData(): void
    {
        $lastRun = PageSpeedInsightsUtility::getLastRun();

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_pagespeedinsights_results');
        $data = $queryBuilder
            ->addSelectLiteral(
                $queryBuilder->expr()->avg('bestpractices_score', 'avg_bestpractices')
            )
            ->from('tx_pagespeedinsights_results')
            ->where(
                $queryBuilder->expr()->eq('reference', $queryBuilder->createNamedParameter($lastRun))
            )
            ->execute()
            ->fetch();

        list($mode, $tstamp) = GeneralUtility::trimExplode('-', $lastRun);

        $this->score = (int)$data['avg_bestpractices'];
        $this->lastCheck = (int)$tstamp;
    }
}

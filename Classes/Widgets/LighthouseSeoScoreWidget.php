<?php
declare(strict_types=1);

namespace Haassie\PageSpeedInsights\Widgets;


use Haassie\PageSpeedInsights\Utility\PageSpeedInsightsUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class SysLogErrorsWidget
 */
class LighthouseSeoScoreWidget extends AbstractLighthouseDoughnutWidget
{
    /**
     * @var string
     */
    protected $title = 'SEO score';

    public function prepareData(): void
    {
        $lastRun = PageSpeedInsightsUtility::getLastRun();

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_pagespeedinsights_results');
        $data = $queryBuilder
            ->select('tstamp')
            ->addSelectLiteral(
                $queryBuilder->expr()->avg('seo_score', 'avg_seo')
            )
            ->from('tx_pagespeedinsights_results')
            ->where(
                $queryBuilder->expr()->eq('reference', $queryBuilder->createNamedParameter($lastRun))
            )
            ->execute()
            ->fetch();

        $this->score = (int)$data['avg_seo'];
        $this->lastCheck = (int)$data['tstamp'];
    }
}

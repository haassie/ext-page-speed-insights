<?php
declare(strict_types=1);

namespace Haassie\PageSpeedInsights\Utility;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class PageSpeedInsightsUtility
{
    /**
     * @param string $url
     * @param string $strategy
     * @param array $categories
     * @param string $reference
     * @param int $pageId
     * @param int $languageId
     * @param int $pid
     * @param string $key
     * @return array
     */
    public static function checkUrl(string $url, string $strategy = 'mobile', array $categories = ['performance'], string $reference = '', int $pageId = 0, int $languageId = 0, int $pid = 0, string $key = ''): array
    {
        $result = self::getResult($url, $strategy, $categories, $reference, $pageId, $languageId, $pid, $key);
        self::saveResult($result);

        return $result;
    }

    /**
     * @param string $url
     * @param string $strategy
     * @param array $categories
     * @param string $reference
     * @param int $pageId
     * @param int $languageId
     * @param int $pid
     * @param string $key
     * @return array
     */
    protected static function getResult(string $url, string $strategy = 'mobile', array $categories = ['performance'], string $reference = '', int $pageId = 0, $languageId = 0, $pid = 0, string $key = ''): array
    {
        $categoryParameters = '&category=' . implode('&category=', $categories);
        $apiUrl = 'https://www.googleapis.com/pagespeedonline/v5/runPagespeed?strategy=' . $strategy . $categoryParameters . '&url=' . urlencode($url);
        if (!empty($key)) {
            $apiUrl .= '&key=' . $key;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $rawData = curl_exec($ch);
        $data = json_decode($rawData);
        $categoriesArray = (array)$data->lighthouseResult->categories;

        curl_close($ch);

        $returnData = [
            'date' => time(),
            'url' => $url,
            'pageId' => $pageId,
            'pid' => $pid,
            'languageId' => $languageId,
            'strategy' => $strategy,
            'reference' => $reference
        ];
        if (property_exists($data, 'error')) {
            $returnData['error']['message'] = (string)$data->error->message;
            return $returnData;
        }

        if (property_exists($categoriesArray['performance'], 'score')) {
            $returnData['performance']['score'] = (float)$categoriesArray['performance']->score * 100;
        }

        if (is_array($data->lighthouseResult->audits->diagnostics->details->items) && !empty($data->lighthouseResult->audits->diagnostics->details->items[0]->totalByteWeight)) {
            $returnData['performance']['totalBytes'] = $data->lighthouseResult->audits->diagnostics->details->items[0]->totalByteWeight;
        }

        if (property_exists($data->lighthouseResult->audits->interactive, 'displayValue')) {
            $returnData['performance']['time_to_interactive'] = (float)$data->lighthouseResult->audits->interactive->displayValue;
        }

        if (property_exists($categoriesArray['seo'], 'score')) {
            $returnData['seo']['score'] = $categoriesArray['seo']->score * 100;
        }

        if (property_exists($categoriesArray['accessibility'], 'score')) {
            $returnData['accessibility']['score'] = (float)$categoriesArray['accessibility']->score * 100;
        }

        if (property_exists($categoriesArray['best-practices'], 'score')) {
            $returnData['best-practices']['score'] = (float)$categoriesArray['best-practices']->score * 100;
        }

        if (property_exists($data->lighthouseResult->categories->pwa, 'score')) {
            $returnData['pwa']['score'] = (float)$data->lighthouseResult->categories->pwa->score * 100;
        }

        return $returnData;
    }

    /**
     * @param array $result
     */
    protected static function saveResult(array $result): void
    {
        if (!array_key_exists('error', $result)) {
            $queryBuilderResults = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tx_pagespeedinsights_results')->createQueryBuilder();
            $queryBuilderResults
                ->insert('tx_pagespeedinsights_results')
                ->values([
                    'pid' => (int)$result['pid'],
                    'sys_language_uid' => (int)$result['languageId'],
                    'page_id' => (int)$result['pageId'],
                    'tstamp' => time(),
                    'date' => $result['date'],
                    'url' => $result['url'],
                    'strategy' => $result['strategy'],
                    'reference' => $result['reference'],
                    'seo_score' => $result['seo']['score'],
                    'performance_score' => $result['performance']['score'],
                    'pwa_score' => $result['pwa']['score'],
                    'accessibility_score' => $result['accessibility']['score'],
                    'bestpractices_score' => $result['best-practices']['score'],
                ])
                ->execute();
            $logger = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Log\LogManager::class)->getLogger(__CLASS__);
            $logger->debug('PageSpeed Insights check of ' . $result['url'] . ' succeeded', $result);
        } else {
            $logger = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Log\LogManager::class)->getLogger(__CLASS__);
            $logger->error('PageSpeed Insights check of ' . $result['url'] . ' failed', $result);
        }
    }

    /**
     * @param int $pageId
     * @return array
     */
    public static function getPageAndLanguageId(int $pageId): array
    {
        $pid = $pageId;
        $pageRecord = BackendUtility::getRecord('pages', $pageId);
        $languageId = (int)$pageRecord['sys_language_uid'];

        if ($languageId > 0) {
            $pid = $pageRecord['l10n_parent'];
        }

        return ['pageId' => $pageId, 'languageId' => $languageId, 'pid' => $pid];
    }

    public static function getUrlForPage(int $pid, int $languageId): string
    {
        $rootLine = BackendUtility::BEgetRootLine($pid);

        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
        $site = $siteFinder->getSiteByPageId($pid, $rootLine);

        $additionalQueryParams = [];
        $additionalQueryParams['_language'] = $site->getLanguageById($languageId);

        return (string)$site->getRouter()->generateUri($pid, $additionalQueryParams);
    }

    public static function getLastRun($pageId = 0, $strategy = ''): string
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_pagespeedinsights_results');

        $constraints = [];
        if ($pageId > 0) {
            $constraints[] = $queryBuilder->expr()->eq('page_id', $pageId);
        }
        if (!empty($strategy)) {
            $constraints[] = $queryBuilder->expr()->eq('strategy', $queryBuilder->createNamedParameter($strategy));
        }
        $data = $queryBuilder
            ->select('reference')
            ->from('tx_pagespeedinsights_results')
            ->where(...$constraints)
            ->orderBy('tstamp', 'DESC')
            ->setMaxResults(1)
            ->execute()
            ->fetch();

        return (string)$data['reference'];
    }

    /**
     * @param int $daysInPastToStartFrom
     * @param int $daysPerStep
     * @param int $pageId 0 = all pages
     * @param string $strategy
     * @param string $chartColor1
     * @param string $chartColor2
     * @param string $chartColor3
     * @param string $chartColor4
     * @param string $chartColor5
     * @param string $labelFormat
     * @return array
     */
    public static function getChartData($daysInPastToStartFrom, $daysPerStep, $pageId = 0, $strategy = '', $chartColor1 = '', $chartColor2 = '', $chartColor3 = '', $chartColor4 = '', $chartColor5 = '', $labelFormat = 'd-m-Y'): array
    {
        $labels = [];
        $dataPerformance = [];
        $dataSeo = [];
        $dataAccessibility = [];
        $dataBestPractices = [];
        $dataPwa = [];

        for ($daysBefore = $daysInPastToStartFrom; $daysBefore >= 0; $daysBefore-=$daysPerStep) {
            $labels[] = date($labelFormat, strtotime('-' . $daysBefore . ' day'));
            $startPeriod = strtotime('-' . $daysBefore . ' day 0:00:00');
            $endPeriod =  strtotime('-' . ($daysBefore - $daysPerStep + 1) . ' day 23:59:59');

            $dataPerformance[] = self::getAverageScoreInPeriod('performance_score', $startPeriod, $endPeriod, $pageId, $strategy);
            $dataSeo[] = self::getAverageScoreInPeriod('seo_score', $startPeriod, $endPeriod, $pageId, $strategy);
            $dataAccessibility[] = self::getAverageScoreInPeriod('accessibility_score', $startPeriod, $endPeriod, $pageId, $strategy);
            $dataBestPractices[] = self::getAverageScoreInPeriod('bestpractices_score', $startPeriod, $endPeriod, $pageId, $strategy);
            $dataPwa[] = self::getAverageScoreInPeriod('pwa_score', $startPeriod, $endPeriod, $pageId, $strategy);
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Performance',
                    'borderColor' => $chartColor1,
                    'backgroundColor' => $chartColor1,
                    'fill' => false,
                    'data' => $dataPerformance
                ],
                [
                    'label' => 'SEO',
                    'borderColor' => $chartColor2,
                    'backgroundColor' => $chartColor2,
                    'fill' => false,
                    'data' => $dataSeo
                ],
                [
                    'label' => 'Accessibility',
                    'borderColor' => $chartColor3,
                    'backgroundColor' => $chartColor3,
                    'fill' => false,
                    'data' => $dataAccessibility
                ],
                [
                    'label' => 'Best practices',
                    'borderColor' => $chartColor4,
                    'backgroundColor' => $chartColor4,
                    'fill' => false,
                    'data' => $dataBestPractices
                ],
                [
                    'label' => 'PWA',
                    'borderColor' => $chartColor5,
                    'backgroundColor' => $chartColor5,
                    'fill' => false,
                    'data' => $dataPwa
                ],
            ]
        ];
    }

    /**
     * @param string $field
     * @param int $start
     * @param int $end
     * @param int $pageId
     * @param string $strategy
     * @return int
     */
    protected static function getAverageScoreInPeriod(string $field, int $start, int $end, int $pageId = 0, $strategy = ''): int
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_pagespeedinsights_results');

        $conditions = [
            $queryBuilder->expr()->gte('tstamp', $start),
            $queryBuilder->expr()->lte('tstamp', $end)
        ];

        if (!empty($pageId)) {
            $conditions[] = $queryBuilder->expr()->eq('page_id', $pageId);
        }
        if (!empty($strategy)) {
            $conditions[] = $queryBuilder->expr()->eq('strategy', $queryBuilder->createNamedParameter($strategy));
        }

        $row = $queryBuilder
            ->addSelectLiteral(
                $queryBuilder->expr()->avg($field, 'avg')
            )
            ->from('tx_pagespeedinsights_results')
            ->where(...$conditions)
            ->execute()
            ->fetch();

        return (int)$row['avg'];
    }

    public static function getLastScore(string $field, int $pageId = 0, $strategy = ''): int
    {
        $lastRun = self::getLastRun($pageId, $strategy);

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_pagespeedinsights_results');
        $conditions = [
            $queryBuilder->expr()->eq('reference', $queryBuilder->createNamedParameter($lastRun))
        ];
        if (!empty($pageId)) {
            $conditions[] = $queryBuilder->expr()->eq('page_id', $pageId);
        }
        if (!empty($strategy)) {
            $conditions[] = $queryBuilder->expr()->eq('strategy', $queryBuilder->createNamedParameter($strategy));
        }

        $data = $queryBuilder
            ->addSelectLiteral(
                $queryBuilder->expr()->avg($field, 'avg')
            )
            ->from('tx_pagespeedinsights_results')
            ->where(...$conditions)
            ->execute()
            ->fetch();

        list($mode, $tstamp) = GeneralUtility::trimExplode('-', $lastRun);

        return (int)$data['avg'];
    }

    public static function getScoreRating($score): string
    {
        if ($score >= 90) {
            return 'good';
        }
        if ($score >= 50) {
            return 'ok';
        }
        return 'bad';
    }

    public static function getReportUrl($pageId, $languageId, $strategy = 'mobile'): string
    {
        $url = self::getUrlForPage($pageId, $languageId);
        return 'https://developers.google.com/speed/pagespeed/insights/?url=' . urlencode($url) . '&tab=' . $strategy;
    }
}

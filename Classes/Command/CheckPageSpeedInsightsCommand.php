<?php
declare(strict_types=1);

namespace Haassie\PageSpeedInsights\Command;

use Haassie\PageSpeedInsights\Utility\PageSpeedInsightsUtility;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class CheckPageSpeedInsightsCommand extends Command
{
    public function configure(): void
    {
        $this->setDescription('Check pages on PageSpeed Insight');
        $this->addArgument(
            'key',
            InputArgument::OPTIONAL,
            'Your API key of Google'
        );
        $this->setHelp('Help!');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $reference = 'command-' . time();

        $queryBuilderPages = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('pages')->createQueryBuilder();
        $result = $queryBuilderPages
            ->select('uid', 'slug')
            ->from('pages')
            ->where(
                $queryBuilderPages->expr()->eq('tx_pagespeedinsights_check', 1)
            )
            ->execute();

        while ($row = $result->fetch()) {
            [
                'pageId' => $pageId,
                'languageId' => $languageId,
                'pid' => $pid
            ] = PageSpeedInsightsUtility::getPageAndLanguageId($row['uid']);

            $url = PageSpeedInsightsUtility::getUrlForPage($pid, $languageId);

            $strategies = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['page_speed_insights']['strategies'] ?: ['performance', 'seo', 'accessibility', 'best-practices', 'pwa'];
            $pageSpeedInsightsResultsMobile = PageSpeedInsightsUtility::checkUrl($url, 'mobile', $strategies, $reference, $pageId, $languageId, $pid, (string)$input->getArgument('key'));
            $pageSpeedInsightsResultsDesktop = PageSpeedInsightsUtility::checkUrl($url, 'desktop', $strategies, $reference, $pageId, $languageId, $pid, (string)$input->getArgument('key'));
            $output->writeln($row['uid'] . ': ' . $row['slug'] . ': ' . $url . ' (' . implode(', ', $strategies) . ')');
        }

        return 0;
    }
}

<?php
declare(strict_types=1);

namespace Haassie\PageSpeedInsights\Command;

use Haassie\PageSpeedInsights\Utility\PageSpeedInsightsUtility;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class CheckPageSpeedInsightsCommand extends Command
{
    public function configure(): void
    {
        $this->setDescription('Check pages on PageSpeed Insight');
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
            $url = $this->getUrlForPage($row['uid']);
            $pageSpeedInsightsResultsMobile = PageSpeedInsightsUtility::checkUrl($url, 'mobile', ['performance', 'seo', 'accessibility', 'best-practices', 'pwa'], $reference, $row['uid']);
            $pageSpeedInsightsResultsDesktop = PageSpeedInsightsUtility::checkUrl($url, 'desktop', ['performance', 'seo', 'accessibility', 'best-practices', 'pwa'], $reference, $row['uid']);
            $output->writeln($row['uid'] . ': ' . $row['slug']. ': ' . $url);
        }
    }

    protected function getUrlForPage(int $pageId): string
    {
        return 'https://www.richardhaeser.com';
        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
        $site = $siteFinder->getSiteByPageId($pageId);
        return (string)$site->getRouter()->generateUri($pageId);
    }
}

<?php
declare(strict_types=1);

namespace Haassie\PageSpeedInsights\Hooks;

use Haassie\PageSpeedInsights\Utility\PageSpeedInsightsUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

class DrawHeaderHook
{
    /**
     * @var StandaloneView
     */
    protected $templateView;

    /**
     * @var PageRenderer
     */
    protected $pageRenderer;

    /**
     * @var string
     */
    protected $strategyToShow;

    /**
     * @param StandaloneView|null $templateView
     * @param PageRenderer|null $pageRenderer
     */
    public function __construct(StandaloneView $templateView = null, PageRenderer $pageRenderer = null)
    {
        $this->templateView = $templateView ?? GeneralUtility::makeInstance(StandaloneView::class);
        $this->pageRenderer = $pageRenderer ?? GeneralUtility::makeInstance(PageRenderer::class);

        $this->templateView->getRenderingContext()->getTemplatePaths()->fillDefaultsByPackageName('page_speed_insights');
        $this->templateView->setTemplate('DrawHeaderHook');

        $this->strategyToShow = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['page_speed_insights']['strategyToShow'] ?: 'mobile';
    }

    public function render(): string
    {
        $pageId = (int)$_GET['id'];
        $languageId = (int)BackendUtility::getModuleData(['language'], [], 'web_layout')['language'];
        $hasAccess = $GLOBALS['BE_USER']->check('non_exclude_fields', 'pages:tx_pagespeedinsights_results');
        $disableWarnings = (bool)($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['page_speed_insights']['disableWarnings'] ?? false);
        $disableInfo = (bool)($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['page_speed_insights']['disableInfo'] ?? false);

        if (!$hasAccess || $disableInfo) {
            return '';
        }

        [$mode, $lastRun] = GeneralUtility::trimExplode('-', PageSpeedInsightsUtility::getLastRun($pageId));
        if (empty($lastRun)) {
            return '';
        }

        $this->pageRenderer->addCssFile('EXT:page_speed_insights/Resources/Public/Css/DrawHeaderHook.css');

        $this->templateView->assignMultiple([
           'performanceScore' => PageSpeedInsightsUtility::getLastScore('performance_score', $pageId, $this->strategyToShow),
           'seoScore' => PageSpeedInsightsUtility::getLastScore('seo_score', $pageId, $this->strategyToShow),
           'accessibilityScore' => PageSpeedInsightsUtility::getLastScore('accessibility_score', $pageId, $this->strategyToShow),
           'bestPracticesScore' => PageSpeedInsightsUtility::getLastScore('bestpractices_score', $pageId, $this->strategyToShow),
           'pwaScore' => PageSpeedInsightsUtility::getLastScore('pwa_score', $pageId, $this->strategyToShow),
           'reportUrl' => PageSpeedInsightsUtility::getReportUrl($pageId, $languageId, $this->strategyToShow),
           'lastRun' => $lastRun,
           'strategy' => $this->strategyToShow,
           'disableWarnings' => $disableWarnings
        ]);

        return $this->templateView->render();
    }
}

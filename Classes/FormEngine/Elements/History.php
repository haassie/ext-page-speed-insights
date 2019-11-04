<?php
declare(strict_types=1);

namespace Haassie\PageSpeedInsights\FormEngine\Elements;

use Haassie\PageSpeedInsights\Utility\PageSpeedInsightsUtility;
use TYPO3\CMS\Backend\Form\AbstractNode;
use TYPO3\CMS\Backend\Form\NodeFactory;
use TYPO3\CMS\Core\Crypto\Random;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

class History extends AbstractNode
{
    /**
     * @var StandaloneView
     */
    protected $templateView;

    protected $chartColors = ['#f49702', '#a4276a', '#1a568f', '#4c7e3a', '#69bbb5'];

    protected $colorRed = '#ff4f42';
    protected $colorOrange = '#ffa600';
    protected $colorGreen = '#0cce6a';

    /**
     * History constructor.
     * @param NodeFactory $nodeFactory
     * @param array $data
     * @param StandaloneView|null $templateView
     */
    public function __construct(NodeFactory $nodeFactory, array $data, StandaloneView $templateView = null)
    {
        parent::__construct($nodeFactory, $data);

        $this->templateView = $templateView ?? GeneralUtility::makeInstance(StandaloneView::class);
        $this->templateView->getRenderingContext()->getTemplatePaths()->fillDefaultsByPackageName('page_speed_insights');
        $this->templateView->setTemplate('History');
    }

    public function render()
    {
        $pageId = 0;
        if (!empty($this->data['tableName']) && $this->data['tableName'] === 'pages' && !empty($this->data['vanillaUid'])) {
            $pageId = $this->data['vanillaUid'];
        }
        $resultArray = $this->initializeResultArray();

        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);

        $hash = GeneralUtility::makeInstance(Random::class)->generateRandomHexString(12);
        $dataYear = PageSpeedInsightsUtility::getChartData(
            356,
            7,
            $pageId,
            $this->chartColors[0],
            $this->chartColors[1],
            $this->chartColors[2],
            $this->chartColors[3],
            $this->chartColors[4]
        );

        $dataMonth = PageSpeedInsightsUtility::getChartData(
            31,
            1,
            $pageId,
            $this->chartColors[0],
            $this->chartColors[1],
            $this->chartColors[2],
            $this->chartColors[3],
            $this->chartColors[4]
        );

        $dataWeek = PageSpeedInsightsUtility::getChartData(
            7,
            1,
            $pageId,
            $this->chartColors[0],
            $this->chartColors[1],
            $this->chartColors[2],
            $this->chartColors[3],
            $this->chartColors[4]
        );

        $pageRenderer->loadRequireJsModule('TYPO3/CMS/PageSpeedInsights/History');
        $pageRenderer->loadRequireJsModule('TYPO3/CMS/PageSpeedInsights/HistorySelector');
        $pageRenderer->loadRequireJsModule('TYPO3/CMS/PageSpeedInsights/Scores');

        $pageRenderer->addCssFile('EXT:page_speed_insights/Resources/Public/Css/Style.css');

        list($mode, $tstamp) = GeneralUtility::trimExplode('-', PageSpeedInsightsUtility::getLastRun($pageId));

        $this->templateView->assignMultiple([
           'hash' => $hash,
           'period' => 'month',
           'lastRun' => $tstamp,
           'dataYear' => json_encode($dataYear),
           'dataMonth' => json_encode($dataMonth),
           'dataWeek' => json_encode($dataWeek),
           'scorePerformanceData' => json_encode($this->getScoreData($pageId, 'performance_score')),
           'scorePerformance' => PageSpeedInsightsUtility::getLastScore('performance_score', $pageId),
           'scoreSeoData' => json_encode($this->getScoreData($pageId, 'seo_score')),
           'scoreSeo' => PageSpeedInsightsUtility::getLastScore('seo_score', $pageId),
           'scoreAccessibilityData' => json_encode($this->getScoreData($pageId, 'accessibility_score')),
           'scoreAccessibility' => PageSpeedInsightsUtility::getLastScore('accessibility_score', $pageId),
           'scoreBestPracticeData' => json_encode($this->getScoreData($pageId, 'bestpractices_score')),
           'scoreBestPractice' => PageSpeedInsightsUtility::getLastScore('bestpractices_score', $pageId),
           'scorePwaData' => json_encode($this->getScoreData($pageId, 'pwa_score')),
           'scorePwa' => PageSpeedInsightsUtility::getLastScore('pwa_score', $pageId),
        ]);
        $resultArray['html'] = $this->templateView->render();

        return $resultArray;
    }

    protected function getScoreData($pageId, $field = 'performance_score'): array
    {
        $score = PageSpeedInsightsUtility::getLastScore($field, $pageId);
        return [
            'labels' => ['', ''],
            'datasets' => [
                [
                    'backgroundColor' => [$this->getColor($score), '#fafafa'],
                    'data' => [$score, 100 - $score],
                    'borderWidth' => 0
                ]
            ],
        ];
    }

    protected function getColor($score): string
    {
        if ($score >= 90) {
            return $this->colorGreen;
        }
        if ($score >= 50) {
            return $this->colorOrange;
        }
        return $this->colorRed;
    }
}

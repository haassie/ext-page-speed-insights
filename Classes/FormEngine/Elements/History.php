<?php
declare(strict_types=1);

namespace Haassie\PageSpeedInsights\FormEngine\Elements;

use Haassie\PageSpeedInsights\Utility\PageSpeedInsightsUtility;
use RichardHaeser\WebVitals\ResultOverview;
use TYPO3\CMS\Backend\Form\AbstractNode;
use TYPO3\CMS\Backend\Form\NodeFactory;
use TYPO3\CMS\Core\Crypto\Random;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
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
     * @var string
     */
    protected $strategyToShow;

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

        $this->strategyToShow = ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['page_speed_insights']['strategyToShow'] ?? '') ?: 'mobile';
    }

    public function render()
    {
        $pageId = 0;
        if (!empty($this->data['tableName']) && $this->data['tableName'] === 'pages' && !empty($this->data['vanillaUid'])) {
            $pageId = $this->data['vanillaUid'];
        }

        $languageId = 0;
        if (array_key_exists('0', (array)$this->data['databaseRow']['sys_language_uid']) &&
            $this->data['databaseRow']['sys_language_uid'][0]
        ) {
            $languageId = (int)$this->data['databaseRow']['sys_language_uid'][0];
        }

        $resultArray = $this->initializeResultArray();

        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);

        $hash = GeneralUtility::makeInstance(Random::class)->generateRandomHexString(12);
        $dataYear = PageSpeedInsightsUtility::getChartData(
            356,
            7,
            $pageId,
            $this->strategyToShow,
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
            $this->strategyToShow,
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
            $this->strategyToShow,
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

        $webvitals = '';
        if (ExtensionManagementUtility::isLoaded('webvitals')) {
            $webvitals = ResultOverview::render($pageId, $languageId);
        }
        $this->templateView->assignMultiple([
            'webvitals' => $webvitals,
            'hash' => $hash,
            'period' => 'month',
            'lastRun' => $tstamp,
            'reportUrl' => PageSpeedInsightsUtility::getReportUrl($pageId, $languageId, $this->strategyToShow),
            'dataYear' => json_encode($dataYear),
            'dataMonth' => json_encode($dataMonth),
            'dataWeek' => json_encode($dataWeek),
            'scorePerformanceData' => json_encode($this->getScoreData($pageId, 'performance_score', $this->strategyToShow)),
            'scorePerformance' => PageSpeedInsightsUtility::getLastScore('performance_score', $pageId, $this->strategyToShow),
            'scoreSeoData' => json_encode($this->getScoreData($pageId, 'seo_score', $this->strategyToShow)),
            'scoreSeo' => PageSpeedInsightsUtility::getLastScore('seo_score', $pageId, $this->strategyToShow),
            'scoreAccessibilityData' => json_encode($this->getScoreData($pageId, 'accessibility_score', $this->strategyToShow)),
            'scoreAccessibility' => PageSpeedInsightsUtility::getLastScore('accessibility_score', $pageId, $this->strategyToShow),
            'scoreBestPracticeData' => json_encode($this->getScoreData($pageId, 'bestpractices_score', $this->strategyToShow)),
            'scoreBestPractice' => PageSpeedInsightsUtility::getLastScore('bestpractices_score', $pageId, $this->strategyToShow),
            'scorePwaData' => json_encode($this->getScoreData($pageId, 'pwa_score', $this->strategyToShow)),
            'scorePwa' => PageSpeedInsightsUtility::getLastScore('pwa_score', $pageId, $this->strategyToShow),
        ]);
        $resultArray['html'] = $this->templateView->render();

        return $resultArray;
    }

    protected function getScoreData($pageId, $field = 'performance_score', $strategy = ''): array
    {
        $score = PageSpeedInsightsUtility::getLastScore($field, $pageId, $strategy);
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
        $scoreRating = PageSpeedInsightsUtility::getScoreRating($score);
        switch ($scoreRating) {
            case 'good':
                return $this->colorGreen;
                break;
            case 'ok':
                return $this->colorOrange;
                break;
            case 'bad':
            default:
                return $this->colorRed;
        }
    }
}

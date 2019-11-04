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
        $data = PageSpeedInsightsUtility::getChartData(
            356,
            7,
            $pageId,
            $this->chartColors[0],
            $this->chartColors[1],
            $this->chartColors[2],
            $this->chartColors[3],
            $this->chartColors[4]
        );

        $pageRenderer->loadRequireJsModule('TYPO3/CMS/PageSpeedInsights/History');

        $this->templateView->assignMultiple([
           'hash' => $hash,
           'data' => json_encode($data)
        ]);
        $resultArray['html'] = $this->templateView->render();

        return $resultArray;
    }
}

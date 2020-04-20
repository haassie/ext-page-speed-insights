<?php
declare(strict_types=1);

namespace Haassie\PageSpeedInsights\Widgets\Provider;

use Haassie\PageSpeedInsights\Utility\PageSpeedInsightsUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Dashboard\WidgetApi;

class ScoreDataProvider implements LighthouseScoreProviderInterface
{
    /**
     * @var int
     */
    protected $score;

    /**
     * @var array
     */
    protected $metadata;
    /**
     * @var string
     */
    private $field;
    /**
     * @var string
     */
    private $strategy;

    public function __construct(string $field = 'performance_score', string $strategy = 'mobile')
    {
        $this->field = $field;
        $this->strategy = $strategy;
        $this->initialize();
    }

    protected function initialize()
    {
        $this->score = PageSpeedInsightsUtility::getLastScore($this->field, 0, $this->strategy);

        [$mode, $lastRun] = GeneralUtility::trimExplode('-', PageSpeedInsightsUtility::getLastRun(0, $this->strategy));

        $this->metadata = [
            'lastRun' => $lastRun,
            'strategy' => $this->strategy,
        ];
    }

    public function getChartData(): array
    {
        $chartColors = WidgetApi::getDefaultChartColors();
        return [
            'labels' => [
                '',
                ''
            ],
            'datasets' => [
                [
                    'backgroundColor' => [$chartColors[0], '#fff'],
                    'data' => [$this->score, 100 - $this->score]
                ]
            ],
        ];
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function getMetaData(): array
    {
        return $this->metadata;
    }
}

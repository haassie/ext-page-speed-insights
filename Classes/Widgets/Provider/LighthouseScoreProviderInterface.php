<?php
declare(strict_types=1);

namespace Haassie\PageSpeedInsights\Widgets\Provider;

interface LighthouseScoreProviderInterface
{
    public function getChartData(): array;
    public function getScore(): int;
    public function getMetaData(): array;
}

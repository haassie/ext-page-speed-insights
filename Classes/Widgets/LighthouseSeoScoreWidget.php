<?php
declare(strict_types=1);

namespace Haassie\PageSpeedInsights\Widgets;

/**
 * Class SysLogErrorsWidget
 */
class LighthouseSeoScoreWidget extends AbstractLighthouseDoughnutWidget
{
    protected $title = 'SEO score';
    protected $fieldToUse = 'seo_score';
}

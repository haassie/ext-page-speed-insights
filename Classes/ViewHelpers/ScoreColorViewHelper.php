<?php
declare(strict_types=1);

namespace Haassie\PageSpeedInsights\ViewHelpers;

use Haassie\PageSpeedInsights\Utility\PageSpeedInsightsUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class ScoreColorViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('score', 'int', '', true);
    }

    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext): string
    {
        return PageSpeedInsightsUtility::getScoreRating($arguments['score']);
    }

    /**
     * Render the view helper
     *
     * @return string
     */
    public function render(): string
    {
        return self::renderStatic(
            $this->arguments,
            $this->buildRenderChildrenClosure(),
            $this->renderingContext
        );
    }
}

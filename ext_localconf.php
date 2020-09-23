<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

call_user_func(function () {
    if (TYPO3_MODE === 'BE') {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1572271289] = [
            'nodeName' => 'pageSpeedInsightsHistory',
            'priority' => 40,
            'class' => \Haassie\PageSpeedInsights\FormEngine\Elements\History::class,
        ];

        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/db_layout.php']['drawHeaderHook'][]
            = \Haassie\PageSpeedInsights\Hooks\DrawHeaderHook::class . '->render';
    }

    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('dashboard')) {
        // Add module configuration
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup(
            'module.tx_dashboard {
    view {
        templateRootPaths.1585898586 = EXT:page_speed_insights/Resources/Private/Templates/
        partialRootPaths.1585898586 = EXT:page_speed_insights/Resources/Private/Partials/
        layoutRootPaths.1585898586 = EXT:page_speed_insights/Resources/Private/Layouts/
    }
}'
        );
    }

    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['page_speed_insights']['strategies'] = [
        'performance',
        'seo',
        'accessibility',
        'best-practices',
        'pwa'
    ];
});

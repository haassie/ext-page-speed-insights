define(['jquery', 'TYPO3/CMS/PageSpeedInsights/History'], function ($, History) {
    'use strict';

    let HistorySelector = {
        selector: '.page_speed_insights_history_selector'
    };

    HistorySelector.initialize = function () {
        $(HistorySelector.selector).on('change', function() {
            window['pageSpeedInsightsHistoryConfig'].data = window['data_' + $(this).val() + '_' + $(this).data('hash')];
            window['pageSpeedInsightsHistory'].update();
        });
    };

    HistorySelector.initialize();
    return HistorySelector;
});

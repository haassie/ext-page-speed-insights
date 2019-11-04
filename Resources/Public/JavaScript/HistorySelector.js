define(['jquery', 'TYPO3/CMS/PageSpeedInsights/History'], function ($, History) {
    'use strict';

    let HistorySelector = {
        selector: '.page_speed_insights_history_selector'
    };

    HistorySelector.initialize = function () {
        $(HistorySelector.selector).on('change', function() {
            var _selector = '#history_' + $(this).data('hash');
            console.log('data_' + $(this).val() + '_' + $(this).data('hash'));
            $(_selector).data('var-name', 'data_' + $(this).val() + '_' + $(this).data('hash'));

            History.initialize();
        });
    };

    HistorySelector.initialize();
    return HistorySelector;
});

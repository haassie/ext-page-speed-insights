define(['jquery', 'TYPO3/CMS/PageSpeedInsights/Dist/Chart.min'], function ($, Chart) {
    'use strict';

    let Scores = {
        selector: '.page_speed_insights_score'
    };

    Scores.initialize = function () {
        $(Scores.selector).each(function() {
            var _canvas = $(this);
            var varName = _canvas.data('var-name');
            if (_canvas.length > 0) {
                var ctx = _canvas[0].getContext('2d');

                var myChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: window[varName],
                    options: {
                        legend: {
                            display: false
                        },
                        cutoutPercentage: 60
                    }
                });
            }
        });
    };


    Scores.initialize();
    return Scores;
});

define(['jquery', 'TYPO3/CMS/PageSpeedInsights/Dist/Chart.min'], function ($, Chart) {
    'use strict';

    let History = {
        selector: '.page_speed_insights_history'
    };

    History.initialize = function () {
        $(History.selector).each(function() {
            var _canvas = $(this);
            var varName = _canvas.data('var-name');
            if (_canvas.length > 0) {
                var ctx = _canvas[0].getContext('2d');

                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: window[varName],
                    options: {
                        legend: {
                            labels: {
                                boxWidth: 20
                            }
                        },
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    max: 100
                                }
                            }],
                            xAxes: [{
                                ticks: {
                                    maxTicksLimit: 15
                                }
                            }]
                        }
                    }
                });
            }
        });
    };


    History.initialize();
    return History;
});

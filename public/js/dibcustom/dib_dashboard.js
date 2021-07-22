/// <reference path="../directives/jquery.d.ts" />
/// <reference path="../directives/underscore.d.ts" />
/// <reference path="../directives/jqueryui.d.ts" />
var Dashboard = /** @class */ (function () {
    function Dashboard(options) {
        this.defaultSettings = {
            graphdata: '',
            labels: '',
            lineColors: '',
            ykeys: '',
            lineWidth: 0,
            fillOpacity: 0.4,
            pointSize: 0
        };
        this.monthNames = ["", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
        ];
        this.settings = $.extend(true, {}, this.defaultSettings, options);
    }
    Dashboard.prototype.initialSetting = function () {
        this.salesGraph();
    };
    Dashboard.prototype.salesGraph = function () {
        //lineColors: ['#a5d9c7'],
        var _that = this;
        "use strict";
        Morris.Area({
            element: 'leadsgraph',
            data: _that.settings.graphdata,
            lineColors: _that.settings.lineColors,
            xkey: 'y',
            ykeys: _that.settings.ykeys,
            parseTime: false,
            xLabelFormat: function (x) {
                var index = parseInt(x.src.y);
                return _that.monthNames[index];
            },
            xLabels: "month",
            labels: _that.settings.labels,
            pointSize: _that.settings.pointSize,
            lineWidth: _that.settings.lineWidth,
            fillOpacity: _that.settings.fillOpacity,
            resize: true,
            behaveLikeLine: true,
            gridLineColor: '#e0e0e0',
            hideHover: 'auto',
            smooth: false,
            pointStrokeColors: ['#b4becb', '#009efb']
        });
        //Count column graphs
        var sparklineLogin = function () {
            $('#sparklinedash').sparkline([0, 5, 6, 10, 9, 12, 4, 9], {
                type: 'bar',
                height: '30',
                barWidth: '4',
                resize: true,
                barSpacing: '5',
                barColor: '#4caf50'
            });
            $('#sparklinedash2').sparkline([0, 5, 6, 10, 9, 12, 4, 9], {
                type: 'bar',
                height: '30',
                barWidth: '4',
                resize: true,
                barSpacing: '5',
                barColor: '#9675ce'
            });
            $('#sparklinedash3').sparkline([0, 5, 6, 10, 9, 12, 4, 9], {
                type: 'bar',
                height: '30',
                barWidth: '4',
                resize: true,
                barSpacing: '5',
                barColor: '#03a9f3'
            });
            $('#sparklinedash4').sparkline([0, 5, 6, 10, 9, 12, 4, 9], {
                type: 'bar',
                height: '30',
                barWidth: '4',
                resize: true,
                barSpacing: '5',
                barColor: '#f96262'
            });
        };
        var sparkResize;
        $(window).resize(function (e) {
            clearTimeout(sparkResize);
            sparkResize = setTimeout(sparklineLogin, 500);
        });
        sparklineLogin();
    };
    return Dashboard;
}());

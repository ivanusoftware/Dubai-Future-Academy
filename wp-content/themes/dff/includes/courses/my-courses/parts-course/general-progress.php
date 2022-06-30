<?php

$mod          = dff_general_progress_mod();
$mod_result   = dff_general_progress_mod_result($future_user_id, $course_id);
$choose_сhart = get_field('choose_сhart', 'option');
?>
<div id="container-highcharts"></div>
<script>
    //  const $ = jQuery.noConflict();
    jQuery(document).ready(function() {
        var chartBg, color;

        if ( jQuery('html').hasClass('dark-mode')) {
            chartBg = '#000000';
            color = 'rgba(255, 255, 255, 0.5)';
            color2 = 'rgba(255, 255, 255, 0.2)';
            color3 = 'rgba(255, 255, 255)';
            plotBackgroundColor = 'rgba(0, 0, 0, .9)';
        } else {
            chartBg = '#f8f8f8';
            color = 'rgba(0, 0, 0, 0.5)';
            color2 = 'rgba(0, 0, 0, 0.2)';
            color3 = 'rgba(0, 0, 0)';
            plotBackgroundColor = 'rgba(255, 255, 255, .9)';
        }

        jQuery('[data-toggle-darkmode]').click( function() {
            if ( jQuery('html').hasClass('dark-mode')) {
                chartBg = '#f8f8f8';
                color = 'rgba(0, 0, 0, 0.5)';
                color2 = 'rgba(0, 0, 0, 0.2)';
                color3 = 'rgba(0, 0, 0)';
                plotBackgroundColor = 'rgba(255, 255, 255, .9)';
            } else {
                chartBg = '#000000';
                color = 'rgba(255, 255, 255, 0.5)';
                color2 = 'rgba(255, 255, 255, 0.2)';
                color3 = 'rgba(255, 255, 255)';
                plotBackgroundColor = 'rgba(0, 0, 0, .9)';
            }
            jQuery('#container-highcharts').highcharts().destroy();
            resetChart();
        })

        var resetChart = function(){

            const chart = Highcharts.chart('container-highcharts', {
                chart: {
                    // type: 'areaspline',
                    // type: 'column',
                    type: '<?php echo $choose_сhart; ?>',
                    backgroundColor: chartBg,
                    height: 270,
                    // width: 400
                    // borderWidth: 2,
                    // plotBackgroundColor: 'rgba(255, 255, 255, .9)',
                    // plotShadow: true,
                    // plotBorderWidth: 1
                },
                title: {
                    text: ''
                },

                // legend: {
                //     enabled: false,
                //     layout: 'vertical',
                //     align: 'left',
                //     verticalAlign: 'top',
                //     x: 150,
                //     y: 100,
                //     floating: true,
                //     borderWidth: 0,
                //     backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || '#f8f8f8'
                // },
                xAxis: {
                    categories: <?php echo $mod; ?>,
                    labels: {
                        enabled: true,
                        style: {
                            color: color,
                            cursor: 'default',
                            fontSize: '14px',
                            fontWeight: '400',
                            fontFamily: 'Neo Sans Pro, Helvetica, Arial, sans-serif'
                        }
                    },
                },
                yAxis: {
                    title: {
                        text: '<?php _e('Pass 80%', 'dff'); ?>',
                        style: {
                            color: color,
                            cursor: 'default',
                            fontSize: '14px',
                            fontWeight: '400',
                            fontFamily: 'Neo Sans Pro, Helvetica, Arial, sans-serif'
                        }
                    },
                    labels: {
                        enabled: true,
                        // format: '{value}%'
                        formatter: function() {
                            return this.value + "%";
                        },
                        style: {
                            color: color,
                            cursor: 'default',
                            fontSize: '14px',
                            fontWeight: '400',
                            fontFamily: 'Neo Sans Pro, Helvetica, Arial, sans-serif'
                        }
                    },
                    min: 0,
                    max: 100,
                    tickInterval: 20,
                },
                tooltip: {
                    shared: true,
                    valueSuffix: '%'
                },
                credits: {
                    enabled: false
                },
                // plotOptions: {
                //     areaspline: {
                //         fillOpacity: 0.5
                //     }
                // },
                plotOptions: {
                    series: {
                        // pointStart: Date.UTC(2020, 0, 1),
                        // pointInterval: 36e5, // one hour
                        relativeXValue: false
                    }
                },
                series: [{
                    showInLegend: false,
                    // name: 'John',
                    // data: [99, 79, 100, 92],
                    data: [<?php echo $mod_result; ?>],
                    plotBackgroundColor: plotBackgroundColor,
                    dataLabels: {
                        enabled: false,
                        color: 'red'
                    },
                    color: {
                        linearGradient: {
                            x1: 0,
                            x2: 0,
                            y1: 0,
                            y2: 1
                        },
                        stops: [
                            [0, color],
                            [1, color2]
                        ]
                    },
                    marker: {
                        lineColor: color3,
                        lineWidth: 0.5,
                        fillColor: color3,

                    },
                }],
                tooltip: {
                    // pointFormat: '{point.y}',
                    shared: true,
                    useHTML: true,
                    headerFormat: '<div><b>{point.key}: </b>',
                    pointFormat: ' {point.y}% {point.custom.extraInformation}',
                    footerFormat: '</div>',
                },
                cursor: 'pointer'
            });
        };

        resetChart();


    });
</script>
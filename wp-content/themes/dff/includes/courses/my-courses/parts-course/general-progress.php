<?php
$mod          = dff_general_progress_mod();
$mod_result   = dff_general_progress_mod_result($course_id);
$choose_сhart = get_field('choose_сhart', 'option');
?>
<div id="container-highcharts"></div>
<script>
    //  const $ = jQuery.noConflict();
    jQuery(document).ready(function() {
        const chart = Highcharts.chart('container-highcharts', {
            chart: {
                // type: 'areaspline',
                // type: 'column',
                type: '<?php echo $choose_сhart; ?>',
                backgroundColor: '#f8f8f8',
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
                        color: 'rgba(0, 0, 0, 0.5)',
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
                        color: 'rgba(0, 0, 0, 0.5)',
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
                        color: 'rgba(0, 0, 0, 0.5)',
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
                plotBackgroundColor: 'rgba(255, 255, 255, .9)',
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
                        [0, 'rgba(0, 0, 0, 0.5)'],
                        [1, 'rgba(0, 0, 0, 0.2)']
                    ]
                },
                marker: {
                    lineColor: '#000',
                    lineWidth: 0.5,
                    fillColor: '#000',

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
    });
</script>
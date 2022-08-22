<template>
    <LayoutDefault>
        <Line
            :chart-data="chartData"
            :chart-options="options"
            :width=800
            :height=800
        />
    </LayoutDefault>
</template>

<script>
    import { defineComponent } from 'vue'
    import Layout from '@/Layouts/LayoutGlobal.vue'
    import LayoutDefault from '@/Layouts/LayoutDefault.vue'
    import Link from '@/Components/Link.vue'
    import VueTableLite from 'vue3-table-lite'
    import { Line } from 'vue-chartjs'
    import { Chart as ChartJS,
        Title,
        Tooltip,
        Legend,
        LineElement,
        LinearScale,
        TimeSeriesScale,
        PointElement,
        CategoryScale,
        Plugin
    } from 'chart.js'
    import 'chartjs-adapter-moment';
    ChartJS.register(
        Title,
        Tooltip,
        Legend,
        LineElement,
        LinearScale,
        TimeSeriesScale,
        PointElement,
        CategoryScale
    )
    export default defineComponent({
        layout: Layout,
        components: {
            LayoutDefault,
            Link,
            VueTableLite,
            Line
        },
        props: [
            'device',
            'readings',
            'dataconfig'
        ],
        computed: {
            chartData: function() {
                return {
                    datasets: this.datasets
                }
            },
            datasets: function() {
                return [
                    this.dataset1,
                ]
            },
            dataset1: function() {
                let array = [];
                let dataconfig = this.dataconfig
                this.readings.forEach(function(reading) {
                    if(typeof reading.datapoints[dataconfig.key] != 'undefined') {
                        array.push({
                            x: reading.timestamp,
                            y: reading.datapoints[dataconfig.key].value
                        })
                    }
                })
                return {
                    label: this.dataconfig.name,
                    data: array 
                }
            },
            options: function() {
                return {
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                displayFormats: {
                                    quarter: 'MMM YYYY'
                                }
                            }
                        }
                    }
                }
            }
        },

    })
</script>

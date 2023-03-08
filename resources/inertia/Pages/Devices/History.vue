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
        props: {
            'device': Object,
            'readings': Array,
            'figure': Object
        },
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
                console.log(this.readings);
                this.readings.forEach(function(reading) {
                    array.push({
                        x: reading.timestamp,
                        y: reading.value_calibrated
                    })
                })
                return {
                    label: this.figure.name,
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

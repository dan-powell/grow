<template>
    <LayoutDefault>
        <!-- <VueTableLite
            :columns="columns"
            :rows="readings"
        /> -->
        <Line
            :chart-data="chartData"
            :width=800
            :height=800
        />
    </LayoutDefault>
</template>

<script>
    import { defineComponent } from 'vue'
    import Layout from '@/Layouts/LayoutGlobal.vue'
    import LayoutDefault from '@/Layouts/LayoutDefault.vue'
    // import Title from '@/Layouts/Components/Title.vue'
    import Link from '@/Components/Link.vue'
    import { Inertia } from '@inertiajs/inertia'
    import VueTableLite from 'vue3-table-lite'
    import { Line } from 'vue-chartjs'
    import { Chart as ChartJS,
        Title,
        Tooltip,
        Legend,
        LineElement,
        LinearScale,
        PointElement,
        CategoryScale,
        Plugin
    } from 'chart.js'
    ChartJS.register(
        Title,
        Tooltip,
        Legend,
        LineElement,
        LinearScale,
        PointElement,
        CategoryScale
    )
    export default defineComponent({
        layout: Layout,
        components: {
            LayoutDefault,
            // Title,
            Link,
            VueTableLite,
            Line
        },
        props: [
            'readings',
        ],
        data() {
            return {
                
                // columns: [
                //     {
                //         label: "ID",
                //         field: "id",
                //         width: "3%",
                //         sortable: true,
                //         isKey: true,
                //     },
                //     {
                //         label: "timestamp",
                //         field: "timestamp",
                //         width: "10%",
                //         sortable: true,
                //     },
                // ],
            }
        },
        computed: {
            chartData: function() {
                return {
                    labels: this.labels,
                    datasets: [
                        {
                            label: 'Temperature',
                            backgroundColor: '#f87979',
                            data: this.data
                        }
                    ]
                }
            },
            labels: function() {
                let array = [];
                this.readings.forEach(function(reading) {
                    array.push(reading.timestamp)
                })
                return array
            },
            data: function() {
                let array = [];
                this.readings.forEach(function(reading) {
                    array.push(reading.temperature)
                })
                return array
            }
        },

    })
</script>

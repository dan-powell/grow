<template>
    <LayoutDefault>
        <DeviceSummary :device="device"/>
        <h2 class="title is-3">Reading Types</h2>
        <div class="card">
            <div class="card-content">
                <section class="columns is-multiline is-centered">
                    <div v-for="figure in device.figures" v-bind:key="figure">
                        <FigureExcerpt :figure="figure" :device="device"/>
                    </div>
                </section>
            </div>
        </div>
    </LayoutDefault>
</template>

<script>
    import { defineComponent } from 'vue'
    import Layout from '@/Layouts/LayoutGlobal.vue'
    import LayoutDefault from '@/Layouts/LayoutDefault.vue'
    import Link from '@/Components/Link.vue'
    import DeviceSummary from '@/Components/Device/DeviceSummary.vue'
    import FigureExcerpt from '@/Components/Device/FigureExcerpt.vue'
    export default defineComponent({
        layout: Layout,
        components: {
            LayoutDefault,
            Link,
            DeviceSummary,
            FigureExcerpt,
        },
        props: [
            'device',
        ],
        data() {
            return {
            }
        },
        computed: {

        },

    })
</script>

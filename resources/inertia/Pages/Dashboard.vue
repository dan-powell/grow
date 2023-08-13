<template>
    <LayoutDefault>
        <div class="Section" v-if="figures.length">
            <div class="DeviceGroup-figures">
                <template v-for="figure in figures" v-bind:key="figure">
                    <div v-if="figure.dashboard">
                        <FigureExcerpt :figure="figure"/>
                    </div>
                </template>
            </div>
        </div>
        <div class="Section is-stack" v-if="devices_with_configs.length">
            <section class="DeviceGroup" v-for="device in devices_with_configs" v-bind:key="device">
                <main class="DeviceGroup-device">
                    <DeviceExcerpt :device="device"/>
                </main>
                <aside class="DeviceGroup-figures">
                    <template v-for="figure in device.figures" v-bind:key="figure">
                        <div v-if="figure.dashboard">
                            <FigureExcerpt :figure="figure" :device="device"/>
                        </div>
                    </template>
                </aside>
            </section>
        </div>
        <div class="Section" v-if="devices_without_configs.length">
            <div class="Devices">
                <template v-for="device in devices_without_configs" v-bind:key="device">
                    <DeviceExcerpt :device="device"/>
                </template>
            </div>
        </div>
    </LayoutDefault>
</template>

<script>
    import { defineComponent } from 'vue'
    import Layout from '@/Layouts/LayoutGlobal.vue'
    import LayoutDefault from '@/Layouts/LayoutDefault.vue'
    import Title from '@/Layouts/Components/Title.vue'
    import Link from '@/Components/Link.vue'
    import DeviceExcerpt from '@/Components/Device/DeviceExcerpt.vue'
    import FigureExcerpt from '@/Components/Device/FigureExcerpt.vue'
    export default defineComponent({
        layout: Layout,
        components: {
            LayoutDefault,
            Title,
            Link,
            DeviceExcerpt,
            FigureExcerpt,
        },
        props: [
            'devices_with_configs',
            'devices_without_configs',
            'figures'
        ],
        data() {
            return {}
        },
        methods: {

        },

    })
</script>

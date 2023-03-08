<template>
    <LayoutDefault>
        <h2 class="title">Devices</h2>
        <div>
            <section class="columns is-multiline" v-for="device in devices_with_configs" v-bind:key="device">
                <DeviceExcerpt :device="device"/>
                <aside>
                    <template v-for="figure in device.figures" v-bind:key="figure">
                        <div v-if="figure.dashboard && figure.last_reading">
                            <FigureExcerpt :figure="figure" :device="device"/>
                        </div>
                    </template>
                </aside>
            </section>
        </div>
        <h2 class="title">More Devices</h2>
            <div class="tile is-child box" v-for="device in devices_without_configs" v-bind:key="device">
                <DeviceExcerpt :device="device"/>
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
        ],
        data() {
            return {}
        },
        methods: {

        },

    })
</script>

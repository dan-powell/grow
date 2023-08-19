<template>
    <LayoutDefault>
        <div class="Section" v-if="figures.length">
            <div class="DeviceGroup-figures">
                <template v-for="figure in figures" v-bind:key="figure">
                    <div v-if="figure.dashboard">
                        <FigureExcerpt :figure="figure" :show_device="true"/>
                    </div>
                </template>
            </div>
        </div>
        <div class="Section" v-if="devices.length">
            <div class="Devices">
                <template v-for="device in devices" v-bind:key="device">
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
            'devices',
            'figures'
        ],
        data() {
            return {}
        },
        methods: {

        },

    })
</script>

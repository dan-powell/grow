<template>
    <LayoutDefault>
        <div class="Section">
            <DeviceExcerpt :device="device" class="is-horizontal"/>
        </div>
        <div class="Section Figures">
            <div v-for="figure in device.figures" v-bind:key="figure">
                <FigureExcerpt :figure="figure" :device="device"/>
            </div>
        </div>
    </LayoutDefault>
</template>

<script>
    import { defineComponent } from 'vue'
    import Layout from '@/Layouts/LayoutGlobal.vue'
    import LayoutDefault from '@/Layouts/LayoutDefault.vue'
    import Link from '@/Components/Link.vue'
    import DeviceExcerpt from '@/Components/Device/DeviceExcerpt.vue'
    import FigureExcerpt from '@/Components/Device/FigureExcerpt.vue'
    export default defineComponent({
        layout: Layout,
        components: {
            LayoutDefault,
            Link,
            DeviceExcerpt,
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

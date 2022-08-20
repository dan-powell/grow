<template>
    <LayoutDefault>
        <div v-for="device in devices" v-bind:key="device">
            <a :href="route('device.show', device.name)">
                {{ device.name }}
            </a>
        </div>
    </LayoutDefault>
</template>

<script>
    import { defineComponent } from 'vue'
    import Layout from '@/Layouts/LayoutGlobal.vue'
    import LayoutDefault from '@/Layouts/LayoutDefault.vue'
    import Title from '@/Layouts/Components/Title.vue'
    import Link from '@/Components/Link.vue'
    export default defineComponent({
        layout: Layout,
        components: {
            LayoutDefault,
            Title,
            Link,
        },
        props: [
            'devices',
        ],
        data() {
            return {}
        },
        methods: {

        },

    })
</script>

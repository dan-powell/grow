<template>
    <LayoutDefault>
        <div v-for="device in devices" v-bind:key="device">
            <a :href="route('device.show', device.name)">
                {{ device.name }}
            </a>
        </div>
        <VueTableLite
            :columns="columns"
            :rows="devices"
        />
    </LayoutDefault>
</template>

<script>
    import { defineComponent } from 'vue'
    import Layout from '@/Layouts/LayoutGlobal.vue'
    import LayoutDefault from '@/Layouts/LayoutDefault.vue'
    import Title from '@/Layouts/Components/Title.vue'
    import Link from '@/Components/Link.vue'
    import VueTableLite from 'vue3-table-lite'
    export default defineComponent({
        layout: Layout,
        components: {
            LayoutDefault,
            Title,
            Link,
            VueTableLite
        },
        props: [
            'devices',
        ],
        data() {
            return {
                'columns': [
                    'id',
                    'name'
                ]
            }
        },
        methods: {

        },

    })
</script>

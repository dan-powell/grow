<template>
    <LayoutDefault>
        Test
    </LayoutDefault>
</template>

<script>
    import { defineComponent } from 'vue'
    import Layout from '@/Layouts/LayoutGlobal.vue'
    import LayoutDefault from '@/Layouts/LayoutDefault.vue'
    import Title from '@/Layouts/Components/Title.vue'
    import Link from '@/Components/Link.vue'
    import { Inertia } from '@inertiajs/inertia'

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
            return {

            }
        },
        methods: {

        },

    })
</script>

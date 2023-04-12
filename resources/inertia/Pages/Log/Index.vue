<template>
    <LayoutDefault>
        <div class="Section is-stack" v-if="logs.length">
            <div class="LogExcerpt" v-for="log in logs" v-bind:key="log.id">
                {{ log.name }} {{ log.created_at }}
            </div>
        </div>
    </LayoutDefault>
</template>

<script>
    import { defineComponent } from 'vue'
    import Layout from '@/Layouts/LayoutGlobal.vue'
    import LayoutDefault from '@/Layouts/LayoutDefault.vue'
    import Link from '@/Components/Link.vue'
    export default defineComponent({
        layout: Layout,
        components: {
            LayoutDefault,
            Link,
        },
        props: {
            logs: Array
        },
        data() {
            return {}
        },
        methods: {

        },

    })
</script>

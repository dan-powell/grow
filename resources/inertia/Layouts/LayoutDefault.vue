<template>
    <div class="container mt-4">
        <slot></slot>
    </div>
</template>

<script>
    import { defineComponent } from 'vue'
    // import FlashBanner from '@/Layouts/Components/FlashBanner.vue'
    // import PageHeader from '@/Layouts/Components/PageHeader.vue'
    export default defineComponent({
        props: {
            title: String,
        },
        components: {
            // PageHeader,
            // FlashBanner,
        },
        data() {
            return {
            }
        },
        methods: {
        },
        computed: {
           
        }
    })
</script>

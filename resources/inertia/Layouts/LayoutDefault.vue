<template>
    <div class="LayoutDefault">
        <!-- <FlashBanner/> -->
        <main class="LayoutDefault-main">
            <div class="LayoutDefault-inner">
                <slot></slot>
            </div>
        </main>
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

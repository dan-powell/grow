<template>
    <div>
        <GlobalHeader/>
        <slot></slot>
        <GlobalFooter/>
    </div>
</template>

<script>
    import { defineComponent } from 'vue'
    import GlobalHeader from '@/Layouts/Components/GlobalHeader.vue'
    import GlobalFooter from '@/Layouts/Components/GlobalFooter.vue'
    export default defineComponent({
        components: {
            GlobalHeader,
            GlobalFooter
        },
    })
</script>

<template>
    <div>
        <GlobalHeader/>
        <slot></slot>
    </div>
</template>

<script>
    import { defineComponent } from 'vue'
    import GlobalHeader from '@/Layouts/Components/GlobalHeader.vue'
    export default defineComponent({
        components: {
            GlobalHeader,
            // Notifications
        },
    })
</script>

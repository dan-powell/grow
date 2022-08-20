<template>
    <div class="LayoutGlobal">
        <GlobalHeader/>
        <slot></slot>
        <!-- <Notifications/> -->
    </div>
</template>

<script>
    import { defineComponent } from 'vue'
    import GlobalHeader from '@/Layouts/Components/GlobalHeader.vue'
    // import Notifications from '@/Layouts/Components/Notifications.vue'
    export default defineComponent({
        components: {
            GlobalHeader,
            // Notifications
        },
    })
</script>

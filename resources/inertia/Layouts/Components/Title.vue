<template>
    <div class="Title">
        <div class="Title-breadcrumbs">
            <!-- <Breadcrumbs/> -->
        </div>
        <div class="Title-title">
            <slot></slot>
        </div>
        <div class="Title-controls">
            <slot name="controls"></slot>
        </div>
    </div>
</template>

<script>
    import { defineComponent } from 'vue'
    // import Breadcrumbs from '@/Layouts/Components/Breadcrumbs.vue'
    export default defineComponent({
        components: {
            // Breadcrumbs,
        }
    })
</script>

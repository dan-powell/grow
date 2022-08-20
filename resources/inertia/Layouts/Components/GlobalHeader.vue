
<template>
    <div>
        <NavTop/>
        <div class="container">
            <Breadcrumbs/>
        </div>
    </div>
</template>

<script>
    import { defineComponent } from 'vue'
    import NavTop from '@/Layouts/Components/NavTop.vue'
    import Breadcrumbs from '@/Layouts/Components/Breadcrumbs.vue'
    export default defineComponent({
        components: {
            NavTop,
            Breadcrumbs
        },
    })
</script>



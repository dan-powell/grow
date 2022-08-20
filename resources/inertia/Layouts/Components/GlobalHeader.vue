
<template>
    <div>
        <nav class="Header-nav">
            <NavTop></NavTop>
        </nav>
    </div>
</template>

<script>
    import { defineComponent } from 'vue'
    import NavTop from '@/Layouts/Components/NavTop.vue'
    import NavLink from '@/Layouts/Components/NavLink.vue'
    export default defineComponent({
        props: {
        },
        components: {
            NavTop,
            NavLink,
        },
        data() {
            return {
            }
        },
        watch: {
            
        },
        methods: {
           
        }
    })
</script>



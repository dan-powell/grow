<template>
    <!-- START NAV -->
    <nav class="navbar is-white">
        <div class="container">
            <div class="navbar-brand">
                <a class="navbar-item brand-text" :href="route('dashboard.show')">Grow</a>
                <div class="navbar-burger burger" data-target="navMenu">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
            <div id="navMenu" class="navbar-menu">
                <div class="navbar-start">
                    <a class="navbar-item" :href="route('dashboard.show')">
                        Dashboard
                    </a>
                </div>
            </div>
        </div>
    </nav>
    <!-- END NAV -->
</template>

<script>
    import { defineComponent } from 'vue'
    import Link from '@/Components/Link.vue'
    export default defineComponent({
        props: {
        },
        components: {
            Link
        },
    })
</script>

<template>  
    <ul class="NavTop">
        <li class="NavTop-item">
            <Link class="NavTop-link" :href="route('home')" :active="route().current('home')">
                <span class="NavTop-icon fas fa-th"></span> Dashboard
            </Link>
        </li>
    </ul>
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
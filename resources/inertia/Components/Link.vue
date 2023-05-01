<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>

<script>
    import { defineComponent } from 'vue'
    import { Link } from '@inertiajs/vue3'
    export default defineComponent({
        components: {
            Link,
        },
        props: ['href', 'active'],
        computed: {
            classes() {
                return this.active
                    ? 'is-active'
                    : ''
            }
        }
    })
</script>

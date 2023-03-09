<template>
    <article class="tile is-child box">
        <Link class="column is-one-quarter tile is-parent has-text-centered" :href="route('device.show', device)">
            Link
        </Link>
        <p class="">{{ device.name }}</p>
        <p class="">{{ device.description }}</p>
        <p class="">{{ device.last_reading?.timestamp_formatted }}</p>
        <img :src="device.img"/>
    </article>
</template>

<script>
    import { defineComponent } from 'vue'
    import Link from '@/Components/Link.vue'
    export default defineComponent({
        components: {
            Link,
        },
        props: {
            'device': Object
        },
    })
</script>

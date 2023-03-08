<template>
    <article class="tile is-child box">
        <p class="">{{ device.name }}</p>
        <p class="">{{ device.description }}</p>
        <p class="">{{ device.last_reading?.timestamp_formatted }}</p>
        <img :src="device.img"/>
    </article>
</template>

<script>
    import { defineComponent } from 'vue'
    export default defineComponent({
        components: {
        },
        props: {
            'device': Object
        },
    })
</script>

<template>
    <article class="DeviceExcerpt">
        <Link class="DeviceExcerpt-link" :href="route('device.show', device)"></Link>
        <div class="DeviceExcerpt-inner">
            <p class="DeviceExcerpt-name">{{ device.name }}</p>
            <p class="DeviceExcerpt-summary">{{ device.summary }}</p>
            <p class="DeviceExcerpt-timestamp">{{ device.last_reading?.timestamp_formatted }}</p>
        </div>
        <img class="DeviceExcerpt-img" :src="device.image_src"/>
    </article>
</template>

<style lang="postcss">
    .DeviceExcerpt {
        background: var(--color-primary);
        color: var(--color-positive);
        border: 2px solid var(--color-primary);
        &-inner {
            padding: 1em;
        }
        &-img {
            /* max-width: 320px; */
        }
    }
</style>

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

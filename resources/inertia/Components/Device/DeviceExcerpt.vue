<template>
    <article class="DeviceExcerpt">
        <Link class="DeviceExcerpt-link" :href="route('device.show', device)"></Link>
        <h2 class="DeviceExcerpt-name">{{ device.name }}</h2>
        <p class="DeviceExcerpt-summary" v-if="device.summary">{{ device.summary }}</p>
        <div class="DeviceExcerpt-timestamp" v-if="device.last_reading">
            <Timestamp :date="device.last_reading.date_formatted" :time="device.last_reading.time_formatted"/>
        </div>
        <div class="DeviceExcerpt-image" v-if="device.image_src">
            <img class="DeviceExcerpt-image-img" :src="device.image_src"/>
        </div>
    </article>
</template>

<style lang="postcss">
    .DeviceExcerpt {
        background: var(--color-primary);
        color: var(--color-positive);
        border: 2px solid var(--color-primary);
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        gap: 1.5em;
        position: relative;
        padding-top: 1rem;
        &-img {
            /* max-width: 320px; */
        }
        &-link {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            &:hover {
                background: rgba(0,0,0,0.025);
            }
        }
        &-name {
            padding: 0 0.5rem;
            font-size: 1.8em;
        }
        &-summary {
            margin-top: 0.5em;
        }
        &-timestamp {
            padding: 0 0.5rem;
        }
    }
</style>

<script>
    import { defineComponent } from 'vue'
    import Link from '@/Components/Link.vue'
    import Timestamp from '@/Components/Timestamp.vue'
    export default defineComponent({
        components: {
            Link,
            Timestamp
        },
        props: {
            'device': Object
        },
    })
</script>

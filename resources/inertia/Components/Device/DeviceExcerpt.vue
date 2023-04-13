<template>
    <article class="DeviceExcerpt">
        <Link class="DeviceExcerpt-link" :href="route('device.show', device)"></Link>
        <div class="DeviceExcerpt-alerted" v-if="device.alerted">
            <span class="fa-solid fa-warning" :title="device.alert_activated_formatted"></span>
        </div>
        <div class="DeviceExcerpt-details">
            <div class="FigureExcerpt-title">
                <h2 class="DeviceExcerpt-name">{{ device.name }}</h2>
                <p class="DeviceExcerpt-summary" v-if="device.summary">{{ device.summary }}</p>
            </div>
            <div class="DeviceExcerpt-timestamp" v-if="device.last_reading">
                <Timestamp :date="device.last_reading.created_at_date" :time="device.last_reading.created_at_time" :diff="device.last_reading.created_at_diff"/>
            </div>
        </div>
        <div class="DeviceExcerpt-image" v-if="device.image_src">
            <img class="DeviceExcerpt-image-img" :src="device.image_src"/>
        </div>
    </article>
</template>

<style lang="postcss">
    .DeviceExcerpt {
        --gap: 1rem;
        --padding: 2rem;
        background: var(--color-primary);
        color: var(--color-positive);
        /* border: 2px solid var(--color-primary); */
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        gap: var(--gap);
        position: relative;
        /* padding-top: var(--padding); */
        &-link {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            z-index: 2;
            &:hover {
                background: rgba(0,0,0,0.1);
            }
        }
        &-alerted {
            position: absolute;
            right: 0.5em;
            top: 0.5em;
            font-size: 2rem;
            color: yellow;
            z-index: 10;
        }
        &-details {
            flex: 1 1 auto;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            gap: var(--gap);
            position: relative;
            padding-top: 1rem;
            padding: var(--padding);
        }
        &-name {
            font-size: 1.8em;
        }
        &-summary {
            margin-top: 0.5em;
        }
        &-timestamp {

        }
        &-image {
            margin-top: auto;
            &-img {
                display: block;
            }
        }
        @media screen and (max-width: 1280px) {
            flex-direction: row;
            align-items: center;
            justify-content: space-around;
            flex-wrap: wrap;
            &-details {
                flex-direction: row;
                align-items: center;
                justify-content: space-around;
                flex-wrap: wrap;
            }
        }
        &.is-horizontal {
            flex-direction: row;
            align-items: center;
            justify-content: space-around;
            flex-wrap: wrap;
            &-details {
                flex-direction: row;
                align-items: center;
                justify-content: space-around;
                flex-wrap: wrap;
            }
        }
        &.is-horizontal & {
            &-details {
                flex-direction: row;
                align-items: center;
                justify-content: space-around;
                flex-wrap: wrap;
            }
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

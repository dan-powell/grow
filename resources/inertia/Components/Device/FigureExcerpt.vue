
<template>
    <div class="FigureExcerpt">
        <Link class="FigureExcerpt-link" :href="route('device.figure.history', [device ?? figure.device, figure])"></Link>
        <div class="FigureExcerpt-alerts" v-if="figure.active_alerts">
            <div class="FigureExcerpt-alerts-alert" v-for="alert in figure.active_alerts">
                <i class="fa-solid fa-triangle-exclamation" :title="alert.activated_formatted"></i>
            </div>
        </div>
        <figure class="FigureExcerpt-icon" v-if="figure.icon_src">
            <img class="FigureExcerpt-icon-img" :src="figure.icon_src"/>
            <img class="FigureExcerpt-icon-small" :src="figure.icon_small_src"/>
        </figure>
        <div class="FigureExcerpt-details">
            <div class="FigureExcerpt-title">
                <h2 class="FigureExcerpt-name">{{ figure.name }}</h2>
                <p class="FigureExcerpt-summary" v-if="figure.summary">{{ figure.summary }}</p>
            </div>
            <h4 class="FigureExcerpt-time" v-if="figure.last_reading">
                <span class="fa-solid fa-clock"></span>
                {{ figure.last_reading.timestamp_time }}
                <small>{{ figure.last_reading.timestamp_date }}</small>
            </h4>
            <h4 class="FigureExcerpt-value" v-if="figure.last_reading">{{ figure.last_reading.value_string }}</h4>
        </div>
        <div class="FigureExcerpt-chart" v-if="figure.last_reading">
            <div class="FigureExcerpt-chart-bar" :style="{'height': figure.last_reading?.range_percentage + '%', 'background': figure.last_reading?.range_color}"></div>
        </div>
    </div>
</template>

<style lang="postcss">
    .FigureExcerpt {
        /* background: var(--color-primary); */
        /* color: var(--color-positive); */
        /* border: 2px solid var(--color-primary); */
        /* width: 240px; */
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        gap: 1.5em;
        position: relative;
        padding-top: 1rem;
        > * {
            width: 100%;
            text-align: center;
        }
        &-link {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            z-index: 2;
            &:hover {
                background: rgba(144, 163, 90, 0.25);
            }
        }
        &-alerts {
            display: inline-block;
            width: auto;
            position: absolute;
            right: 0.5em;
            top: 0.5em;
            font-size: 2rem;
            color: orangered;
            z-index: 10;
            &-alert {

            }
        }
        &-icon {
            position: relative;
            &-img {
                margin: auto;
                max-height: 96px;
                max-width: 120px;
            }
            &-small {
                margin: auto;
                max-height: 32px;
                max-width: 32px;
                position: absolute;
                right: 0;
                bottom: 0;
            }
        }
        &-details {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            gap: 1.5em;
            position: relative;
            padding-top: 1rem;
            flex: 1 1 auto;
            padding: 0.5rem;
        }
        &-title {
        }
        &-name {
            /* padding: 0 0.5rem; */
            font-size: 1.2em;
        }
        &-summary {
            margin-top: 0.5em;
        }
        &-time {
            margin-top: auto;
            /* padding: 0 0.5rem; */
            small {
                margin-top: 0.5em;
                font-size: 0.8em;
                display: block;
            }
        }
        &-value {
            /* padding: 0 0.5rem; */
            font-size: 2.6em;
        }
        &-chart {
            width: 100%;
            height: 100px;
            min-height: 100px;
            background: var(--color-neutral);
            display: flex;
            align-items: flex-end;
            &-bar {
                background: var(--color-primary);
                width: 100%;
            }
        }
        @media screen and (max-width: 640px) {
            width: 100%;
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
            'figure': Object,
            'device': Object
        },
    })
</script>

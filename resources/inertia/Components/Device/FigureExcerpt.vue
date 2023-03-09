
<template>
    <div class="FigureExcerpt">
        <Link class="FigureExcerpt-link" :href="route('device.figure.history', [device, figure])"></Link>
        <figure class="FigureExcerpt-icon" v-if="figure.icon_src">
            <img class="FigureExcerpt-icon-img" :src="figure.icon_src"/>
        </figure>
        <div class="FigureExcerpt-title">
            <h2 class="FigureExcerpt-name">{{ figure.name }}</h2>
            <p class="FigureExcerpt-summary" v-if="figure.summary">{{ figure.summary }}</p>
        </div>
        <h4 class="FigureExcerpt-time" v-if="figure.last_reading">
            <span class="fa-solid fa-clock"></span>
            {{ figure.last_reading.time_formatted }}
        </h4>
        <h4 class="FigureExcerpt-value" v-if="figure.last_reading">{{ figure.last_reading.value_string }}</h4>
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
        width: 240px;
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
            &:hover {
                background: rgba(0,0,0,0.025);
            }
        }
        &-icon {
            &-img {
                margin: auto;
                max-height: 80px;
                max-width: 120px;
            }
        }
        &-title {
        }
        &-name {
            padding: 0 0.5rem;
            font-size: 1.2em;
        }
        &-summary {
            margin-top: 0.5em;
        }
        &-time {
            margin-top: auto;
            padding: 0 0.5rem;
        }
        &-value {
            padding: 0 0.5rem;
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

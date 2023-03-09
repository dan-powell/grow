
<template>
    <div class="FigureExcerpt">
        <Link class="FigureExcerpt-link" :href="route('device.figure.history', [device, figure])"></Link>
        <img class="FigureExcerpt-icon-img" :src="figure.icon_src"/>
        <div class="FigureExcerpt-inner">
            <h3 class="FigureExcerpt-name">{{ figure.name }}</h3>
            <h4 class="FigureExcerpt-timestamp">{{ figure.last_reading?.timestamp_formatted }}</h4>
            <h4 class="FigureExcerpt-value">{{ figure.last_reading?.value_string }}</h4>
        </div>
        <div class="FigureExcerpt-chart">
            <div class="FigureExcerpt-chart-bar" :style="{'height': figure.last_reading?.range_percentage + '%'}"></div>
        </div>
    </div>
</template>

<style lang="postcss">
    .FigureExcerpt {
        /* background: var(--color-primary); */
        /* color: var(--color-positive); */
        /* border: 2px solid var(--color-primary); */
        &-inner {
            padding: 1em;
            text-align: center;
        }
        &-value {
            font-size: 3em;
        }
        &-chart {
            width: 240px;
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

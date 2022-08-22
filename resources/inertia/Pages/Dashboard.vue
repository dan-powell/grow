<template>
    <LayoutDefault>
        <h2 class="title s-3">Latest reading</h2>
        <div class="card">
            <header class="card-header">
                <h2 class="card-header-title">
                    <p><strong>{{ reading_latest.timestamp_formatted }}</strong></p>
                </h2>
                <div class="card-header-title is-justify-content-end"><p><strong> {{ reading_latest.device.name }}</strong></p></div>
            </header>
            <div class="card-content">
                <section class="columns is-multiline is-centered">
                    <div class="column is-one-quarter tile is-parent has-text-centered" v-for="datapoint in reading_latest.datapoints" v-bind:key="datapoint">
                        <article class="tile is-child box">
                            <p class="title">{{ datapoint.value_formatted }}</p>
                            <p class="subtitle">{{ datapoint.name }}</p>
                        </article>
                    </div>
                </section>
            </div>
        </div>
        <h2 class="title">Devices</h2>
        <section class="columns is-multiline">
            <a class="column tile is-parent has-text-centered" v-for="device in devices" v-bind:key="device" :href="route('device.show', device)">
                <article class="tile is-child box">
                    <p class="title">{{ device.name }}</p>
                    <p class="subtitle">Last reading: {{ device.reading_latest[0]?.timestamp_formatted }}</p>
                </article>
            </a>
        </section>
    </LayoutDefault>
</template>

<script>
    import { defineComponent } from 'vue'
    import Layout from '@/Layouts/LayoutGlobal.vue'
    import LayoutDefault from '@/Layouts/LayoutDefault.vue'
    import Title from '@/Layouts/Components/Title.vue'
    import Link from '@/Components/Link.vue'
    export default defineComponent({
        layout: Layout,
        components: {
            LayoutDefault,
            Title,
            Link,
        },
        props: [
            'devices',
            'reading_latest'
        ],
        data() {
            return {}
        },
        methods: {

        },

    })
</script>

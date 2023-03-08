<template>
    <LayoutDefault>
        <h2 class="title is-1">{{ device.name }}</h2>
        <h2 class="title is-1">{{ device.summary }}</h2>
        <h2 class="title is-1">{{ device.last_reading?.created_at_formatted }}</h2>
        <img :src="device.image"/>
        <h2 class="title is-3">Reading Types</h2>
        <div class="card">
            <div class="card-content">
                <section class="columns is-multiline is-centered">
                    <Link class="column is-one-quarter tile is-parent has-text-centered"  v-for="figure in device.figures" v-bind:key="figure" :href="route('device.figure.history', [device, figure])">
                        <article class="tile is-child box">
                            <p class="title">{{ figure.name }}</p>
                            <p class="subtitle">{{ figure.summary }}</p>
                            <p class="subtitle">{{ figure.last_reading?.timestamp_formatted }}</p>
                            <p class="subtitle">{{ figure.last_reading?.value_string }}</p>
                        </article>
                    </Link>
                </section>
            </div>
        </div>
    </LayoutDefault>
</template>

<script>
    import { defineComponent } from 'vue'
    import Layout from '@/Layouts/LayoutGlobal.vue'
    import LayoutDefault from '@/Layouts/LayoutDefault.vue'
    import Link from '@/Components/Link.vue'
    export default defineComponent({
        layout: Layout,
        components: {
            LayoutDefault,
            Link,
        },
        props: [
            'device',
        ],
        data() {
            return {
            }
        },
        computed: {

        },

    })
</script>

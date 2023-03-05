<template>
    <LayoutDefault>

        <h2 class="title is-1">{{ device.name }}</h2>
        <h2 class="title is-3">Reading Types</h2>
        <div class="card">
            <div class="card-content">
                <section class="columns is-multiline is-centered">
                    <Link class="column is-one-quarter tile is-parent has-text-centered"  v-for="config in configs" v-bind:key="config" :href="route('device.config', [device, config])">
                        <article class="tile is-child box">
                            <p class="title">{{ config.name }}</p>
                            <p class="subtitle">{{ config.summary }}</p>
                            <p class="subtitle">{{ config.location }}</p>
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
            'configs'
        ],
        data() {
            return {
            }
        },
        computed: {

        },

    })
</script>

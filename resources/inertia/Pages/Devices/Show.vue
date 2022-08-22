<template>
    <LayoutDefault>
        
        <h2 class="title is-1">{{ device.name }}</h2>
        <h2 class="title is-3">Reading Types</h2>
        <div class="card">
            <div class="card-content">
                <section class="columns is-multiline is-centered">
                    <Link class="column is-one-quarter tile is-parent has-text-centered"  v-for="dataconfig in dataconfigs" v-bind:key="dataconfig" :href="route('device.reading', [device, dataconfig])">
                        <article class="tile is-child box">
                            <p class="title">{{ dataconfig.name }}</p>
                            <p class="subtitle">{{ dataconfig.summary }}</p>
                            <p class="subtitle">{{ dataconfig.location }}</p>
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
            'dataconfigs'
        ],
        data() {
            return {
            }
        },
        computed: {

        },

    })
</script>

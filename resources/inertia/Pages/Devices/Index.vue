<template>
    <LayoutDefault>
        <section class="info-tiles">
            <div class="tile is-ancestor has-text-centered">
                <a v-for="device in devices" v-bind:key="device" :href="route('device.show', device)" class="tile is-parent">
                    <article class="tile is-child box">
                        <p class="title">{{ device.name }}</p>
                        <p v-if="device.summary" class="subtitle">{{ device.summary }}</p>
                        <p v-if="device.location" class="subtitle">{{ device.location }}</p>
                    </article>
                </a>
            </div>
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
        ],
        data() {
            return {}
        },
        methods: {

        },

    })
</script>

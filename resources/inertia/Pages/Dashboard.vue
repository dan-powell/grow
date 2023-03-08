<template>
    <LayoutDefault>
        <h2 class="title">Devices</h2>
        <div>
            <section class="columns is-multiline" v-for="device in devices_with_configs" v-bind:key="device">
                <article class="tile is-child box">
                    <a class="" :href="route('device.show', device)">Link</a>
                    <p class="">{{ device.name }}</p>
                    <p class="">{{ device.description }}</p>
                    <p class="">{{ device.last_reading?.timestamp_formatted }}</p>
                    <img :src="device.img"/>
                </article>
                <aside>
                    <template v-for="figure in device.figures" v-bind:key="figure">
                        <div v-if="figure.dashboard && figure.last_reading">
                            <img :src="figure.icon"/>
                            <h3>{{ figure.name }}</h3>
                            <h4>{{ figure.last_reading?.timestamp_formatted }}</h4>
                            <h4>{{ figure.last_reading?.value_string }}</h4>
                            <div class="Bar" style="width: 100px; height: 100px; border: 1px solid red; display: flex; align-items: flex-end;">
                                <div :style="{'height': figure.last_reading?.range_percentage + '%'}" style="background: red; width: 100%"></div>
                            </div>
                        </div>
                    </template>
                </aside>
            </section>
        </div>
        <h2 class="title">More Devices</h2>
            <article class="tile is-child box" v-for="device in devices_without_configs" v-bind:key="device">
                <a class="" :href="route('device.show', device)"></a>
                <p class="">{{ device.name }}</p>
                <p class="">{{ device.description }}</p>
                <p class="">{{ device.last_reading?.timestamp_formatted }}</p>
                <img :src="device.img"/>
            </article>
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
            'devices_with_configs',
            'devices_without_configs',
        ],
        data() {
            return {}
        },
        methods: {

        },

    })
</script>

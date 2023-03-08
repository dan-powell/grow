
<template>
    <Link class="column is-one-quarter tile is-parent has-text-centered" :href="route('device.figure.history', [device, figure])">
        Link
    </Link>
    <img :src="figure.icon"/>
    <h3>{{ figure.name }}</h3>
    <h4>{{ figure.last_reading?.timestamp_formatted }}</h4>
    <h4>{{ figure.last_reading?.value_string }}</h4>
    <div class="Bar" style="width: 100px; height: 100px; border: 1px solid red; display: flex; align-items: flex-end;">
        <div :style="{'height': figure.last_reading?.range_percentage + '%'}" style="background: red; width: 100%"></div>
    </div>
</template>

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

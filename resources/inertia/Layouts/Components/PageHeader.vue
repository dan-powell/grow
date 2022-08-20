
<template>
    <div class="PageHeader">
        <div class="PageHeader-container">
            <h1>{{ title }}</h1>
        </div>
    </div>
</template>
<script>
    import { defineComponent } from 'vue'
    export default defineComponent({
        props: {
            title: String
        },
        components: {
        },
        data() {
            return {
            }
        },
        methods: {
        }
    })
</script>



<template>
    <div class="Timestamp">
        <p class="Timestamp-time" v-if="time">{{ time }}</p>
        <p class="Timestamp-date" v-if="date">{{ date }}</p>
    </div>
</template>

<style lang="postcss">
    .Timestamp {
        display: flex;
        flex-direction: column;
        gap: 0.5em;
        &-time {
            font-size: 1.6em;
        }
        &-date {

        }
    }
</style>

<script>
    import { defineComponent } from 'vue'
    export default defineComponent({
        components: {
        },
        props: {
            'date': String,
            'time': String
        },
    })
</script>

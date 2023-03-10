<template>
    <div class="Timestamp">
        <i class="Timestamp-icon fa-solid fa-calendar"></i>
        <div class="Timestamp-details">
            <p class="Timestamp-time" v-if="time">{{ time }}</p>
            <p class="Timestamp-date" v-if="date">{{ date }}</p>
            <p class="Timestamp-date" v-if="diff">({{ diff }})</p>
        </div>
    </div>
</template>

<style lang="postcss">
    .Timestamp {
        display: flex;
        gap: 0.5em;
        &-icon {
            font-size: 1.6em;
        }
        &-details {
            display: flex;
            flex-direction: column;
            gap: 0.5em;
        }
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
            'time': String,
            'diff': String
        },
    })
</script>

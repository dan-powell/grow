<template>
    <article class="LogExcerpt">
        <div class="LogExcerpt-severity">
            <span v-if="log.severity == 'warning'" class="fa-solid fa-warning"></span>
            <span v-if="log.severity == 'info'" class="fa-solid fa-info-circle"></span>
            <span v-if="log.severity == 'success'" class="fa-solid fa-check-circle"></span>
        </div>
        <h3 class="LogExcerpt-name">{{ log.name }}</h3>
        <p class="LogExcerpt-summary">{{ log.summary }}</p>
        <div class="LogExcerpt-timestamp">
            <Timestamp :date="log.created_at_date" :time="log.created_at_time" :diff="log.created_at_diff"/>
        </div>
    </article>
</template>

<style lang="postcss">
    .LogExcerpt {
        padding: 1rem;
        width: 100%;
        display: flex;
        gap: 1rem;
        align-items: center;
        border: 2px solid var(--color-primary);
        &-timestamp {
            margin-left: auto;
        }
    }
</style>

<script>
    import { defineComponent } from 'vue'
    import Link from '@/Components/Link.vue'
    import Timestamp from '@/Components/Timestamp.vue'
    export default defineComponent({
        components: {
            Link,
            Timestamp
        },
        props: {
            'log': Object
        },
    })
</script>

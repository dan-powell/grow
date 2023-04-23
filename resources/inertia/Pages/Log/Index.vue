<template>
    <LayoutDefault>
        <div class="Section">
            <div class="LogIndex">
                <div class="LogIndex-list" v-if="logs.length">
                    <div class="LogIndex-excerpt" v-for="log in logs" v-bind:key="log.id">
                        <LogExcerpt :log="log"/>
                    </div>
                </div>
            </div>
        </div>
    </LayoutDefault>
</template>

<style lang="postcss">
    .LogIndex {
        &-list {
            /* border: 2px solid var(--color-primary); */
        }
    }
</style>

<script>
    import { defineComponent } from 'vue'
    import Layout from '@/Layouts/LayoutGlobal.vue'
    import LayoutDefault from '@/Layouts/LayoutDefault.vue'
    import LogExcerpt from '@/Components/Log/Excerpt.vue'
    export default defineComponent({
        layout: Layout,
        components: {
            LayoutDefault,
            LogExcerpt,
        },
        props: {
            logs: Array
        },
        data() {
            return {}
        },
        methods: {

        },

    })
</script>

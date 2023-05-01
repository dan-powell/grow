<template>
    <LayoutDefault>
        <div class="Section">
            <div class="LogIndex">
                <div class="LogIndex-top">
                    <div></div>
                    <div class="LogIndex-pagination">
                        <Pagination :paginator="logs"/>
                    </div>
                    <div class="LogIndex-filter">
                        <Link class="LogIndex-filter-button" :href="route('log.index')">All</Link>
                        <template v-for="severity in severities" v-bind:key="severity">
                            <Link class="LogIndex-filter-button" :href="route('log.index', {'severity': severity})">{{ severity }}</Link>
                        </template>
                    </div>
                </div>
                <div class="LogIndex-list" v-if="logs.data.length">
                    <div class="LogIndex-excerpt" v-for="log in logs.data" v-bind:key="log.id">
                        <LogExcerpt :log="log"/>
                    </div>
                </div>
                <div class="LogIndex-pagination">
                    <Pagination :paginator="logs"/>
                </div>
            </div>
        </div>
    </LayoutDefault>
</template>

<style lang="postcss">
    .LogIndex {
        &-top {
            padding: 1em 0;
            display: flex;
            justify-content: space-around;
            align-items: center;
            > * {
                flex: 1 1 33%;
            }
        }
        &-filter {
            display: flex;
            justify-content: flex-end;
            &-button {
                display: inline-block;
                padding: 0.5em;
                border: 1px solid var(--color-primary);
                &:hover {
                    background: var(--color-primary);
                }
            }
        }
        &-list {
            margin-bottom: 1rem;
        }
        &-pagination {
            text-align: center;
        }
    }
</style>

<script>
    import { defineComponent } from 'vue'
    import Layout from '@/Layouts/LayoutGlobal.vue'
    import LayoutDefault from '@/Layouts/LayoutDefault.vue'
    import LogExcerpt from '@/Components/Log/Excerpt.vue'
    import Pagination from '@/Components/Pagination.vue'
    import { Link } from '@inertiajs/inertia-vue3';
    export default defineComponent({
        layout: Layout,
        components: {
            LayoutDefault,
            LogExcerpt,
            Pagination,
            Link
        },
        props: {
            logs: Array,
            severities: Array
        },
        data() {
            return {}
        },
        methods: {

        },

    })
</script>

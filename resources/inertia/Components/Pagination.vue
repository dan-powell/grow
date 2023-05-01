<!--
Pagination Template
------------
Regarding Next / Previous Links:
Next and Previous labels are string determined by the transation file: `resources/lang/en/pagination.php`.
Below we check those strings and replace with icons.
-->
<template>
    <div v-if="paginator.total > paginator.per_page" class="Pagination">
        <template v-for="(link, key) in paginator.links" :key="key">
            <span class="Pagination-item" v-if="link.url === null">
                <span class="Pagination-item-inner" v-html="label(link.label)"></span>
            </span>
            <Link v-else class="Pagination-item is-link" :class="{ 'is-current': link.active }" :href="link.url">
                <span class="Pagination-item-inner" v-html="label(link.label)"></span>
            </Link>
        </template>
    </div>
</template>

<style lang="postcss">
    .Pagination {
        display: inline-flex;
        &-item {
            display: inline-block;
            padding: 0.5em;
            border: 1px solid var(--color-primary);
            &.is-link {
                &:hover {
                    background: var(--color-primary);
                }
            }
        }
    }
</style>

<script>
    import { defineComponent } from 'vue'
    import { Link } from '@inertiajs/inertia-vue3';
    export default defineComponent({
        components: {
            Link,
        },
        props: {
            paginator: Object
        },
        methods: {
            label(label) {
                // Replace the Next and Previous labels with FA chevron icons
                if(label == 'Previous') {
                    return '<span class="fas fa-chevron-left"></span>';
                }
                if(label == 'Next') {
                    return '<span class="fas fa-chevron-right"></span>';
                }
                return label;
            }
        }
    })
</script>


<template>
    <nav class="Breadcrumbs" aria-label="breadcrumbs">
        <ul class="Breadcrumbs-list">
            <li class="Breadcrumbs-item" v-for="breadcrumb in breadcrumbs" v-bind:key="breadcrumb">
                <Link class="Breadcrumbs-link" :class="{'is-active':breadcrumb.is_current_page}" :href="breadcrumb.url">
                    <span v-html="breadcrumb.title"></span>
                </Link>
            </li>
        </ul>
    </nav>
</template>

<style lang="postcss">
    .Breadcrumbs {
        color: var(--color-positive);
        &-list {
            display: flex;
        }
        &-link {
            display: block;
            padding: 1em;
            color: var(--color-positive);
            text-decoration: none;
            &:hover {
                background: var(--color-primary-alt);
            }
        }
    }
</style>

<script>
    import { defineComponent } from 'vue'
    import Link from '@/Components/Link.vue'
    export default defineComponent({
        components: {
            Link
        },
        computed: {
            breadcrumbs() {
                return this.$page.props?.breadcrumbs
            },
        }
    })
</script>


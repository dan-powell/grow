
<template>
    <nav class="Breadcrumbs">
        <ul class="Breadcrumbs-list">
            <li class="Breadcrumbs-item" v-for="item in breadcrumbs" v-bind:key="item">
                <Link class="Breadcrumbs-link" v-if="!item.is_current_page" :href="item.url">
                    <span v-html="item.title"></span>
                </Link>
                <!-- <span class="Breadcrumbs-link" v-else v-html="item.title"></span> -->
            </li>
        </ul>
    </nav>
</template>

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
            breadcrumbs_first() {
                return this.$page.props?.breadcrumbs.slice(0,3)
            },
            breadcrumbs_more() {
                if(this.$page.props?.breadcrumbs.slice(2).length > 1) {
                    return this.$page.props?.breadcrumbs.slice(2)
                } else {
                    return [];
                }
            },
        }
    })
</script>


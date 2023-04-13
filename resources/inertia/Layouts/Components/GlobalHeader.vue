
<template>
    <div class="GlobalHeader">
        <div class="GlobalHeader-stamp">Grow Log</div>
        <div class="GlobalHeader-breadcrumbs">
            <Breadcrumbs/>
        </div>
        <div class="GlobalHeader-controls">
            <Link class="GlobalHeader-controls-button" :href="route('log.index')">
                <span class="fa-solid fa-list-check"></span>
            </Link>
        </div>
    </div>
</template>

<style lang="postcss">
    .GlobalHeader {
        display: flex;
        background: var(--color-primary);
        color: var(--color-positive);
        &-stamp {
            background: var(--color-primary-alt);
            padding: 1em;
            font-weight: bold;
        }
        &-breadcrumbs {
            flex: 1 1 auto;
        }
        &-controls {
            display: flex;
            &-button {
                background: var(--color-primary-alt);
                padding: 1em;
                font-weight: bold;
                &:hover {
                    background: var(--color-primary);
                }
            }
        }
    }
</style>

<script>
    import { defineComponent } from 'vue'
    import NavTop from '@/Layouts/Components/NavTop.vue'
    import Breadcrumbs from '@/Layouts/Components/Breadcrumbs.vue'
    import Link from '@/Components/Link.vue'
    export default defineComponent({
        components: {
            NavTop,
            Breadcrumbs,
            Link
        },
    })
</script>



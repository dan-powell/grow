
<template>
    <div class="GlobalHeader">
        <div class="GlobalHeader-stamp">Grow Log</div>
        <div class="GlobalHeader-breadcrumbs">
            <Breadcrumbs/>
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
    }
</style>

<script>
    import { defineComponent } from 'vue'
    import NavTop from '@/Layouts/Components/NavTop.vue'
    import Breadcrumbs from '@/Layouts/Components/Breadcrumbs.vue'
    export default defineComponent({
        components: {
            NavTop,
            Breadcrumbs
        },
    })
</script>



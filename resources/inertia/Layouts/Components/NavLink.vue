<template>
    <div class="NavLink" :class="{'is-open': opened, 'is-active': active}">
        <div class="NavLink-item">
            <Link class="NavLink-link" v-if="href" :href="href"> 
                <slot></slot>
            </Link>
            <button class="NavLink-button" v-if="hasSlotSub" @click="toggle()">
                <template v-if="!href">
                    <slot></slot>
                </template>
                <span v-if="opened" class="NavLink-chevron fas fa-chevron-down"></span>
                <span v-if="!opened" class="NavLink-chevron fas fa-chevron-circle-down"></span>
            </button>
        </div>
        <div class="NavLink-sub" v-if="hasSlotSub">
            <slot name="sub"></slot>
        </div>
    </div>
</template>

<script>
    import { defineComponent } from 'vue'
    import Link from '@/Components/Link.vue'
    export default defineComponent({
        components: {
            Link
        },
        props: {
            href: String,
            active: Boolean
        },
        data() {
            return {
                opened: false,
            }
        },
        watch: {
            active: {
                immediate: true,
                handler(newValue, oldValue) {
                    if (newValue) {
                        this.opened = true;
                    } else {
                        this.opened = false;
                    }
                }
            },
        },
        methods: {
            toggle() {
                this.opened = this.opened ? false : true;
            },
            open() {
                this.opened = true;
            },
            close() {
                this.opened = false;
            }
        },
        computed: {
            hasSlotSub() {
                return !!this.$slots.sub
            },
            hasSlotLink() {
                return !!this.$slots.link
            }
        },
    })
</script>

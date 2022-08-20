<template>
    <div class="ButtonDropdown" v-click-outside="close" :class="{'is-right': right, 'is-open': opened}">
        <button class="ButtonDropdown-button" @click="toggle()" :class="button_classes"> 
            <slot name="button"></slot>
        </button>
        <div class="ButtonDropdown-dropdown">
            <slot name="dropdown"></slot>
        </div>
    </div>
</template>

<script>
    import { defineComponent } from 'vue'
    export default defineComponent({
        components: {
        },
        props: {
            right: false,
            button_class: String
        },
        data() {
            return {
                opened: false,
            }
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
            button_classes() {
                return (this.button_class ?? 'Button') + (this.opened ? ' is-active' : '')
            } 
        }
    })
</script>

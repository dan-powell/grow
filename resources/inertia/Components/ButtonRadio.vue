<template>
    <div class="ButtonRadio">
        <template v-for="option in options" v-bind:key="option">
            <button class="ButtonRadio-button" @click="change(option.value)" :class="{'is-current' : option.value == current}">{{ option.label }}</button>
        </template>
    </div>
</template>

<script>
    import { defineComponent } from 'vue'
    export default defineComponent({
        components: {
        },
        props: {
            options: Array,
            default: String
        },
        data() {
            return {
                current: null
            }
        },
        methods: {
            change(value) {
                this.activate(value);
                // Emit the clicked event to the parent component to be actioned
                this.$emit('changed', value);
            },
            clear() {
                for(let i = 0; i < this.options.length; i++) {
                    this.options[i].current = false;
                }
            },
            activate(value) {
                for(let i = 0; i < this.options.length; i++) {
                    this.options[i].current = false;
                    if(this.options[i].value == value) {
                        this.options[i].current = true;
                    } else {
                        this.options[i].current = false;
                    }
                }
                this.current = value;
            }
        },
        mounted() {
            // Set the default item
            if(this.default) {
                this.current = this.default;
            }
            this.$emit('changed', this.current);
        }
    })
</script>

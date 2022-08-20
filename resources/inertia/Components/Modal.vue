<template>
    <teleport to="body">
        <transition 
            enter-active-class="is-entering" 
            enter-from-class="is-entering-from" 
            enter-to-class="is-entering-to"
            leave-active-class="is-leaving"
            leave-from-class="is-leaving-from" 
            leave-to-class="is-leaving-to">
            <div class="Modal" v-show="show">
                <div class="Modal-backdrop" :class="{closable: 'closable'}"  @click="close"></div>
                <transition 
                    enter-active-class="is-entering" 
                    enter-from-class="is-entering-from" 
                    enter-to-class="is-entering-to"
                    leave-active-class="is-leaving"
                    leave-from-class="is-leaving-from" 
                    leave-to-class="is-leaving-to">
                    <div class="Modal-inner" v-show="show">
                        <div class="Modal-container" :class="maxWidthClass">
                            <div class="Modal-box">
                                <button class="Modal-close" v-if="closeable" @click="close">
                                    <span class="fas fa-times"></span>
                                </button>
                                <slot></slot>
                            </div>
                        </div>
                    </div>
                </transition>
            </div>
        </transition>
   </teleport>
</template>

<script>
import { defineComponent, onMounted, onUnmounted } from "vue";

export default defineComponent({
        emits: ['close'],

        props: {
            show: {
                default: false
            },
            maxWidth: {
                default: 'md'
            },
            closeable: {
                default: true,
            },
        },

        watch: {
            show: {
                immediate: true,
                handler: (show) => {
                    if (show) {
                        document.body.style.overflow = 'hidden'
                    } else {
                        document.body.style.overflow = null
                    }
                }
            }
        },

        setup(props, {emit}) {
            const close = () => {
                if (props.closeable) {
                    emit('close')
                }
            }

            const closeOnEscape = (e) => {
                if (e.key === 'Escape' && props.show) {
                    close()
                }
            }

            onMounted(() => document.addEventListener('keydown', closeOnEscape))
            onUnmounted(() => {
                document.removeEventListener('keydown', closeOnEscape)
                document.body.style.overflow = null
            })

            return {
                close,
            }
        },

        computed: {
            maxWidthClass() {
                return {
                    'sm': 'is-sm',
                    'md': 'is-md',
                    'lg': 'is-lg',
                }[this.maxWidth]
            }
        }
    })
</script>

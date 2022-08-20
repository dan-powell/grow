<template>
    <div class="FlashBanner" :class="classes" v-if="show && message"> 
        <div class="FlashBanner-container">
            <div class="FlashBanner-icon">
                <span class="fas fa-check-circle" v-if="type == 'success'"></span>
                <span class="fas fa-exclamation-circle" v-if="type == 'danger'"></span>
                <span class="fas fa-exclamation-triangle" v-if="type == 'warning'"></span>
                <span class="fas fa-info-circle" v-if="type == 'info'"></span>
            </div>
            <p class="FlashBanner-message">
                {{ message }}
            </p>
            <!-- <button type="button" class="FlashBanner-close" aria-label="Dismiss" @click.prevent="show = false">
                <span class="fas fa-times"></span>
            </button> -->
        </div>
    </div>
</template>

<script>
    import { defineComponent } from 'vue'
    export default defineComponent({
        data() {
            return {
                show: true,
            }
        },
        computed: {
            message() {
                return this.$page.props.flash?.banner?.message || null
            },
            type() {
                return this.$page.props.flash?.banner?.type || null
            },
            classes() {
                switch(this.type) {
                case 'success':
                    return 'is-success';
                case 'danger':
                    return 'is-danger';
                case 'warning':
                    return 'is-warning';
                default:
                    return 'is-info';
                }
            },
        }
    })
</script>

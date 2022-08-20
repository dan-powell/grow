<template>
    <div class="Notifications"> 
        <div class="Notifications-popup" :class="notification.class" v-for="notification in notifications" v-bind:key="notification">
            <div class="Notifications-timer">
                <div class="Notifications-timer-bar" :style="{width: notification.timer + '%'}">
                </div>
            </div>
            <div class="Notifications-inner">
                <div class="Notifications-icon">
                    <span class="fas fa-check-circle" v-if="notification.type == 'success'"></span>
                    <span class="fas fa-exclamation-circle" v-if="notification.type == 'danger'"></span>
                    <span class="fas fa-exclamation-triangle" v-if="notification.type == 'warning'"></span>
                    <span class="fas fa-info-circle" v-if="notification.type == 'info'"></span>
                </div>
                <p class="Notifications-message">
                    {{ notification.message }}
                </p>
            </div>
        </div>
    </div>
</template>
<script>
    import { defineComponent } from 'vue'
    export default defineComponent({
        data() {
            return {
                lifespan: 10000, // notification display time in ms
                notifications: []
            }
        },
        methods: {
            add(notification) {
                this.notifications.push(
                    {
                        timer: 0,
                        message: notification.message,
                        created: Date.now(),
                        type: notification.type,
                        class: this.classname(notification.type)
                    }
                )
            },
            check() {
                this.notifications.forEach((item) => {
                    if(Date.now() - this.lifespan > item.created) {
                        this.notifications.shift() // remove first item
                    } else {
                        item.timer = ((Date.now() - item.created) / this.lifespan) * 100;
                    }
                })
            },
            classname(value) {
                switch(value) {
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
        },
        watch: {
            '$page.props.flash.notification'(value) {
                if(value) {
                    this.add(value);
                }
            },
        },
        mounted() {
            setInterval(() => {
                this.check();
            }, 100);
        },
        unmounted() {
            
        }
    })
</script>

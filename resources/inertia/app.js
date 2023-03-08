import './bootstrap';

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/inertia-vue3'
import { InertiaProgress } from '@inertiajs/progress'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Grow';

import clickOutside from './Directives/ClickOutside';
createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => import(`./Pages/${name}.vue`),
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .mixin({ methods: { route } })
            .directive('click-outside', clickOutside)
            .mount(el);
    },
});

InertiaProgress.init({
    color: '#ff0000',
    showSpinner: true
})

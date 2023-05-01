import './bootstrap';

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Grow';

import clickOutside from './Directives/ClickOutside';
createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: async (name) => {
        const pages = import.meta.glob('./Pages/**/*.vue');
        return (await pages[`./Pages/${name}.vue`]()).default;
    },
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .mixin({ methods: { route } })
            .directive('click-outside', clickOutside)
            .mount(el);
    },
    progress: {
        color: '#ff0000',
        showSpinner: true
    },
});

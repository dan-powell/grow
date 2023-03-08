import './bootstrap';

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/inertia-vue3'
import { InertiaProgress } from '@inertiajs/progress'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Grow';

import clickOutside from './Directives/ClickOutside';
createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: async (name) => {
        const pages = import.meta.glob('./Pages/**/*.vue');
        return (await pages[`./Pages/${name}.vue`]()).default;
    },
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



// createInertiaApp({
//     // resolve: name => import(`./Pages/${name}.svelte`),
//     resolve: name => resolvePageComponent( `./Pages/${name}.svelte`, import.meta.glob("./Pages/**/*.svelte")),
//     setup({ el, App, props }) { new App({ target: el, props }) }, })

import { defineConfig } from 'vite';
const path = require('path');
import laravel, { refreshPaths } from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // 'resources/css/bulma.css',
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: [
                ...refreshPaths,
                'app/Http/Livewire/**',
                'app/Http/**',
                'app/Models/**',
                'app/Filament/**',
            ],
        }),
        vue({
            template: {
                transformAssetUrls: {
                    // The Vue plugin will re-write asset URLs, when referenced
                    // in Single File Components, to point to the Laravel web
                    // server. Setting this to `null` allows the Laravel plugin
                    // to instead re-write asset URLs to point to the Vite
                    // server instead.
                    base: null,

                    // The Vue plugin will parse absolute URLs and treat them
                    // as absolute paths to files on disk. Setting this to
                    // `false` will leave absolute URLs un-touched so they can
                    // reference assets in the public directory as expected.
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        extensions: ['.js', '.jsx', '.ts', '.tsx', '.vue'],
        alias: {
            '@': path.resolve('resources/inertia'),
            '~': path.resolve('node_modules'),
        },
    },
    server: {
        https: false,
        host: true,
        port: 80,
        hmr: {
            host: 'node.grow.lndo.site',
            protocol: 'ws'
        },
    },
    css: {
        devSourcemap: true
    }
});

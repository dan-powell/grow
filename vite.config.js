import { defineConfig } from 'vite';
import path from 'node:path';
import laravel, { refreshPaths } from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        tailwindcss(),
        laravel({
            input: [
                'resources/css/app.css',
                'resources/inertia/app.tsx',
            ],
            refresh: [
                ...refreshPaths,
                'app/Http/**',
                'app/Models/**',
                'app/Filament/**',
            ],
        }),
        react(),
    ],
    resolve: {
        extensions: ['.js', '.jsx', '.ts', '.tsx'],
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
        devSourcemap: true,
    }
});

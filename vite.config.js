import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig({
    plugins: [
        vue(),
        laravel({
            input: [
                'resources/scss/app.scss',
                'resources/scss/app-media.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap/dist/css/bootstrap-grid.css'),
            '~reset-css': path.resolve(__dirname, 'node_modules/reset-css/reset.css'),
        }
    },
});

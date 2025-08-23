import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/main.ts'],
            refresh: true,
            detectTls: false,
            buildDirectory: 'build',
        }),
        vue(),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        hmr: {
            host: 'localhost',
        },
        watch: {
            usePolling: true,
        },
        origin: 'http://localhost:12200',
        cors: {
            origin: ['http://localhost:12100', 'http://localhost:12200'],
            credentials: true,
        },
    },
});

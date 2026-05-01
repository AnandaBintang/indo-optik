import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        tailwindcss(),
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],
    server: {
        host: "127.0.0.1",
        origin: "http://127.0.0.1:5173",
        cors: {
            origin: ["http://127.0.0.1:8000", "http://localhost:8000"],
        },
    },
});

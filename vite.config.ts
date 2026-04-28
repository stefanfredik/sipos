import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.ts'],
            ssr: 'resources/js/ssr.ts',
            refresh: true,
        }),
        tailwindcss(),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        ...(process.env.NODE_ENV !== 'production' ? [
            wayfinder({
                formVariants: true,
            })
        ] : []),
    ],
    server: {
        host: '0.0.0.0',
        port: 5175,
        strictPort: true,
        cors: true,
        origin: 'http://10.70.103.56:5175',
        hmr: {
            host: '10.70.103.56',
        },
    },
});

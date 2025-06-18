import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/css/app.css',
        'resources/js/app.js',
   
      ],
      refresh: true,
    }),
    vue(), // ✅ 반드시 포함되어야 함
  ],
});

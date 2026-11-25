import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import path from 'node:path'

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/js/site.ts', 'resources/css/filament/admin/theme.css'],
      refresh: [
        'resources/views/**',
        'routes/**',
        'app/Http/Controllers/**',
        'lang/**',
      ],
    }),


  ],

  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'resources/js'),
      '~img': path.resolve(__dirname, 'resources/images'),
    },
  },

  build: {
    // The initial HTML must be able to preload both bundled hero images.
    // Keep the other small icons eligible for inlining.
    assetsInlineLimit: (filePath) => /hero-bg(?:-mobile)?\.webp$/.test(filePath) ? false : undefined,
    // Long-cached hashed assets; the manifest tells Laravel what to emit.
    rollupOptions: {
      // Keep the SSR bundle compatible with Vite's externalized dependencies.
    },
  },

  server: {
    host: '127.0.0.1',
    hmr: { host: '127.0.0.1' },
  },
})

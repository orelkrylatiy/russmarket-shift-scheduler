import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
export default defineConfig({
  plugins: [vue(), {
    name: 'classic-entry',
    transformIndexHtml(html) {
      return html.replace('type="module" crossorigin', 'defer')
    },
  }],
  build: {
    rollupOptions: {
      output: {
        format: 'iife',
        inlineDynamicImports: true,
      },
    },
  },
  base: process.env.GITHUB_ACTIONS
    ? '/russmarket-shift-scheduler/'
    : (process.env.VITE_BASE || '/'),
})

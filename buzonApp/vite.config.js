import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
  plugins: [vue()],
  server: {
    proxy: {
      '/php': {
        target: 'http://localhost', // XAMPP normalmente corre aquí
        changeOrigin: true,
        rewrite: (path) => path.replace(/^\/php/, '/buzonQuejas/php')
      }
    }
  }
})
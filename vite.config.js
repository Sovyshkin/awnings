import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

// https://vite.dev/config/
export default defineConfig({
  plugins: [vue()],
  server: {
    proxy: {
      '/wp-json': {
        target: 'http://localhost:8080', // Change to your WordPress URL
        changeOrigin: true,
        secure: false
      }
    }
  }
})

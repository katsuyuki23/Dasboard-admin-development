import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

// https://vitejs.dev/config/
export default defineConfig(({ command }) => ({
    plugins: [react()],
    base: '/landing-assets/',
    build: {
        outDir: '../public/landing-assets',
        emptyOutDir: true,
    },
    server: {
        port: 5173,
    }
}))

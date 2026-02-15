import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

// https://vitejs.dev/config/
export default defineConfig(({ command, mode }) => {
    const isVercel = process.env.VERCEL === '1';

    return {
        plugins: [react()],
        base: isVercel ? '/' : '/landing-assets/',
        build: {
            outDir: isVercel ? 'dist' : '../public/landing-assets',
            emptyOutDir: true,
        },
        server: {
            port: 5173,
        }
    };
})

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./index.html",
        "./src/**/*.{js,ts,jsx,tsx}",
    ],
    theme: {
        extend: {
            colors: {
                "primary": "#15803d",
                "primary-dark": "#052e16",
                "primary-light": "#86efac",
                "secondary": "#ca8a04",
                "secondary-metallic": "#d97706",
                "secondary-light": "#fde047",
                "background-light": "#f8fcfb",
                "surface-light": "#ffffff",
            },
            fontFamily: {
                "display": ["Inter", "sans-serif"],
                "heading": ["Amiri", "serif"],
                "body": ["Inter", "sans-serif"],
            },
            boxShadow: {
                "glow": "0 0 20px rgba(21, 128, 61, 0.15)",
                "glass": "0 8px 32px 0 rgba(31, 38, 135, 0.05)",
                "gold-glow": "0 0 15px rgba(202, 138, 4, 0.3)",
            },
            backgroundImage: {
                'metallic-gold': 'linear-gradient(135deg, #ca8a04 0%, #eab308 50%, #ca8a04 100%)',
            },
            keyframes: {
                shimmer: {
                    '100%': { transform: 'translateX(100%)' },
                }
            },
            animation: {
                shimmer: 'shimmer 2s infinite',
            }
        },
    },
    plugins: [],
}

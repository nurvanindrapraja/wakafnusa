import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                // NU Primary Green Palette
                'nu-green': {
                    50: '#f0fdf0',
                    100: '#dcfce7',
                    200: '#bbf7d0',
                    300: '#86efac',
                    400: '#4ade80',
                    500: '#1a8a3f',
                    600: '#006400', // Primary NU Green
                    700: '#005200',
                    800: '#004000',
                    900: '#003000',
                    950: '#001a00',
                },
                // Gold Accent Palette
                'nu-gold': {
                    50: '#fefce8',
                    100: '#fef9c3',
                    200: '#fef08a',
                    300: '#fde047',
                    400: '#facc15',
                    500: '#D4AF37', // Primary Gold
                    600: '#b8960e',
                    700: '#8a6d0b',
                    800: '#6b5408',
                    900: '#4a3a06',
                    950: '#2d2204',
                },
                // Neutral / Surface
                'surface': {
                    50: '#fafafa',
                    100: '#f5f5f5',
                    200: '#e5e5e5',
                    300: '#d4d4d4',
                    400: '#a3a3a3',
                    500: '#737373',
                    600: '#525252',
                    700: '#404040',
                    800: '#262626',
                    900: '#171717',
                    950: '#0a0a0a',
                },
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            backgroundImage: {
                'gradient-nu': 'linear-gradient(135deg, #006400 0%, #004000 50%, #003000 100%)',
                'gradient-gold': 'linear-gradient(135deg, #D4AF37 0%, #b8960e 100%)',
                'gradient-hero': 'linear-gradient(135deg, #006400 0%, #003000 40%, #001a00 100%)',
                'gradient-card': 'linear-gradient(180deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%)',
            },
            boxShadow: {
                'glass': '0 8px 32px 0 rgba(0, 100, 0, 0.15)',
                'glass-lg': '0 16px 48px 0 rgba(0, 100, 0, 0.2)',
                'gold': '0 4px 20px 0 rgba(212, 175, 55, 0.3)',
                'card': '0 4px 6px -1px rgba(0, 0, 0, 0.07), 0 2px 4px -1px rgba(0, 0, 0, 0.04)',
                'card-hover': '0 20px 40px -12px rgba(0, 100, 0, 0.15)',
            },
            animation: {
                'float': 'float 6s ease-in-out infinite',
                'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                'slide-up': 'slideUp 0.5s ease-out',
                'slide-down': 'slideDown 0.3s ease-out',
                'fade-in': 'fadeIn 0.5s ease-out',
                'scale-in': 'scaleIn 0.3s ease-out',
                'count-up': 'countUp 2s ease-out',
                'shimmer': 'shimmer 2s linear infinite',
            },
            keyframes: {
                float: {
                    '0%, 100%': { transform: 'translateY(0px)' },
                    '50%': { transform: 'translateY(-10px)' },
                },
                slideUp: {
                    '0%': { transform: 'translateY(20px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
                slideDown: {
                    '0%': { transform: 'translateY(-10px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                scaleIn: {
                    '0%': { transform: 'scale(0.95)', opacity: '0' },
                    '100%': { transform: 'scale(1)', opacity: '1' },
                },
                shimmer: {
                    '0%': { backgroundPosition: '-200% 0' },
                    '100%': { backgroundPosition: '200% 0' },
                },
            },
            borderRadius: {
                '4xl': '2rem',
            },
        },
    },

    plugins: [forms],
};

import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Plus Jakarta Sans', 'Inter', 'system-ui', 'sans-serif'],
            },
            colors: {
                brand: {
                    50: '#EEF2FF',
                    100: '#E0E7FF',
                    200: '#C7D2FE',
                    300: '#A5B4FC',
                    400: '#818CF8',
                    500: '#6366F1',
                    600: '#4F46E5',
                    700: '#4338CA',
                    800: '#3730A3',
                    900: '#312E81',
                },
                violet: {
                    500: '#8B5CF6',
                    600: '#7C3AED',
                },
                sky: {
                    500: '#0EA5E9',
                },
                emerald: {
                    500: '#10B981',
                },
                amber: {
                    500: '#F59E0B',
                },
                rose: {
                    500: '#F43F5E',
                },
                pink: {
                    500: '#EC4899',
                },
                teal: {
                    500: '#14B8A6',
                },
            },
            boxShadow: {
                soft: '0_1px_2px_rgba(16,24,40,0.04),0_4px_16px_rgba(16,24,40,0.06)',
                glow: '0_8px_30px_rgba(79,70,229,0.15)',
            },
            backgroundImage: {
                'grad-brand': 'linear-gradient(135deg,#6366F1 0%,#8B5CF6 50%,#EC4899 100%)',
                'grad-sky': 'linear-gradient(135deg,#0EA5E9,#6366F1)',
                'grad-emerald': 'linear-gradient(135deg,#10B981,#14B8A6)',
                'grad-amber': 'linear-gradient(135deg,#F59E0B,#F43F5E)',
                'grad-violet': 'linear-gradient(135deg,#8B5CF6,#EC4899)',
            },
        },
    },

    plugins: [forms],
};

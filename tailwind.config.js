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
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'inka-navy': '#111d33',
                'inka-light-gray': '#f4f6f9',
                'inka-text-muted': '#6c757d',
                'inka-border': '#dee2e6',
                'accent-orange': '#f2994a'
            }
        },
    },

    plugins: [forms],
};

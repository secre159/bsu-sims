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
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    deep: '#1e7e34',
                    primary: '#2d5016',
                    medium: '#4CAF50',
                    light: '#A5D6A7',
                    pale: '#F1F8F4',
                },
                gold: {
                    accent: '#D4AF37',
                },
                status: {
                    red: '#E53935',
                    yellow: '#FBC02D',
                    green: '#4CAF50',
                },
            },
        },
    },

    plugins: [forms],
};

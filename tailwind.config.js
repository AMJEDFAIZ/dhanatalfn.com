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
                primary: '#0A192F',
                secondary: '#0A192F',
                accent: '#C5A059',
                accent2: '#0A192F',
            },
            fontFamily: {
                sans: ['Tajawal', 'sans-serif', ...defaultTheme.fontFamily.sans],
            },
            container: {
                center: true,
                padding: '1rem',
                screens: {
                    sm: '600px',
                    md: '728px',
                    lg: '984px',
                    xl: '1240px',
                    '2xl': '1280px',
                },
            },
        },
    },

    plugins: [forms],
};

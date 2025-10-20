import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'teamtalk-blue': {
                    DEFAULT: '#134c96',
                    claro: '#249fda',
                },
                'teamtalk-green': {
                    DEFAULT: '#3e802f',
                    claro: '#559e35',
                },
                'teamtalk-orange': '#ee7019',
                'teamtalk-gray': '#3f3f50',
            },
        },
    },

    plugins: [forms, typography, require('daisyui')],
};

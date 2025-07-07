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
        },
    },

    plugins: [forms],

    safelist: [
        'row-span-1',
        'row-span-2',
        'row-span-3',
        'row-span-4',
        'row-span-5',
        'border-l-4',
        'border-gray-300',
        'bg-gray-50',
        'dark:border-gray-500',
        'dark:bg-gray-800',
        'text-gray-900',
        'dark:text-white',
    ],
};

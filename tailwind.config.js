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
        'lg:col-span-1',
        'lg:col-span-2',
        'lg:col-span-3',
        'lg:col-span-4',
        'lg:col-span-5',
        'lg:col-span-6',
        'md:col-span-1',
        'md:col-span-2',
        'md:col-span-3',
        'md:col-span-4',
        'md:col-span-5',
        'md:col-span-6',
        'lg:order-1',
        'lg:order-2',
        'lg:order-3',
        'lg:order-4',
        'lg:order-5',
        'lg:order-6',
        'md:order-1',
        'md:order-2',
        'md:order-3',
        'md:order-4',
        'md:order-5',
        'md:order-6',
    ],
};

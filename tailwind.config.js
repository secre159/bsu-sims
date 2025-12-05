import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    safelist: [
        // User role colors for avatars and badges - Admin (red)
        'bg-red-100', 'bg-red-500', 'bg-red-600', 'text-red-600', 'text-red-700', 'text-red-800',
        'hover:bg-red-200', 'border-red-500',
        // Chairperson (purple)
        'bg-purple-100', 'bg-purple-500', 'bg-purple-600', 'text-purple-600', 'text-purple-700', 'text-purple-800',
        'hover:bg-purple-200',
        // Approver/Other (blue)
        'bg-blue-50', 'bg-blue-100', 'bg-blue-500', 'bg-blue-600', 'text-blue-600', 'text-blue-700', 'text-blue-800',
        'hover:bg-blue-200',
        // Gray/Default
        'bg-gray-100', 'bg-gray-500', 'bg-gray-600', 'text-gray-600', 'text-gray-700', 'text-gray-800', 'text-gray-900',
        'hover:bg-gray-600',
        // Indigo (for edit buttons)
        'bg-indigo-100', 'bg-indigo-600', 'text-indigo-600', 'text-indigo-700', 'text-indigo-800',
        'hover:bg-indigo-200',
        // Emerald
        'bg-emerald-500', 'bg-emerald-600', 'bg-emerald-700', 'text-emerald-100', 'text-emerald-600', 'text-emerald-700',
        'hover:bg-emerald-500', 'hover:bg-emerald-600', 'hover:bg-emerald-700', 'hover:text-emerald-600',
        'from-emerald-500', 'from-emerald-600', 'from-emerald-700', 'to-emerald-500', 'to-emerald-600', 'to-emerald-700',
        'hover:from-emerald-500', 'hover:from-emerald-600', 'hover:to-emerald-500', 'hover:to-emerald-600',
        'border-emerald-500', 'border-emerald-600', 'border-emerald-700',
        'focus:ring-emerald-500', 'focus:border-emerald-500',
        // Green (success)
        'bg-green-100', 'border-green-500', 'text-green-700',
        // Red (danger/delete)
        'border-red-500', 'text-red-700',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Hero gradient colors
                hero: {
                    DEFAULT: '#064e3b',  // Dark emerald start
                    mid: '#0f172a',      // Slate-indigo middle
                    dark: '#1a2332',     // Dark slate-blue end
                },
                
                // Semantic color tokens for consistent theming
                primary: {
                    DEFAULT: '#4f46e5',  // indigo-600
                    50: '#eef2ff',
                    100: '#e0e7ff',
                    200: '#c7d2fe',
                    300: '#a5b4fc',
                    400: '#818cf8',
                    500: '#6366f1',       // indigo-500
                    600: '#4f46e5',       // indigo-600
                    700: '#4338ca',
                    800: '#3730a3',
                    900: '#312e81',
                },
                success: {
                    DEFAULT: '#059669',  // emerald-600
                    50: '#ecfdf5',
                    100: '#d1fae5',
                    200: '#a7f3d0',
                    300: '#6ee7b7',
                    400: '#34d399',
                    500: '#10b981',       // emerald-500
                    600: '#059669',       // emerald-600
                    700: '#047857',
                    800: '#065f46',
                    900: '#064e3b',
                },
                warning: {
                    DEFAULT: '#f59e0b',  // amber-500
                    50: '#fffbeb',
                    100: '#fef3c7',
                    200: '#fde68a',
                    300: '#fcd34d',
                    400: '#fbbf24',
                    500: '#f59e0b',
                    600: '#d97706',
                    700: '#b45309',
                    800: '#92400e',
                    900: '#78350f',
                },
                danger: {
                    DEFAULT: '#dc2626',  // red-600
                    50: '#fef2f2',
                    100: '#fee2e2',
                    200: '#fecaca',
                    300: '#fca5a5',
                    400: '#f87171',
                    500: '#ef4444',
                    600: '#dc2626',
                    700: '#b91c1c',
                    800: '#991b1b',
                    900: '#7f1d1d',
                },
                accent: {
                    DEFAULT: '#a855f7',  // purple-500
                    50: '#faf5ff',
                    100: '#f3e8ff',
                    200: '#e9d5ff',
                    300: '#d8b4fe',
                    400: '#c084fc',
                    500: '#a855f7',
                    600: '#9333ea',
                    700: '#7e22ce',
                    800: '#6b21a8',
                    900: '#581c87',
                },
                muted: {
                    DEFAULT: '#9ca3af',  // gray-400
                    light: '#d1d5db',    // gray-300
                    dark: '#6b7280',     // gray-500
                },
            },
        },
    },

    plugins: [forms],
};

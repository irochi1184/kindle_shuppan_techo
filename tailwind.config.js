import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        // モデル内で状態バッジの色クラスを文字列定義しているため app も走査対象にする
        './app/**/*.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', '"Noto Sans JP"', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // ブランドアクセント（温かみのあるアンバー）
                brand: {
                    50: '#fdf8f3', 100: '#faecdd', 200: '#f3d4b3',
                    300: '#e9b483', 400: '#dd8f4f', 500: '#cf7430',
                    600: '#b85d24', 700: '#984820', 800: '#7b3b20',
                    900: '#65321d', 950: '#37180d',
                },
            },
            boxShadow: {
                card: '0 1px 2px 0 rgb(28 25 23 / 0.04), 0 1px 3px 0 rgb(28 25 23 / 0.06)',
                lift: '0 8px 24px -8px rgb(28 25 23 / 0.12), 0 2px 6px -2px rgb(28 25 23 / 0.08)',
            },
        },
    },

    plugins: [forms],
};

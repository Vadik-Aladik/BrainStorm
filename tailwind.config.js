/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      screens: {
        // 'eso': '350px', // Добавьте это, если используете кастомные брейкпоинты
      },
    },
  },
  plugins: [],
}


/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './components/**/*.{js,vue,ts}',
    './layouts/**/*.vue',
    './pages/**/*.vue',
    './plugins/**/*.{js,ts}',
    './app.vue',
    './error.vue',
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          50:  '#e8f1f9',
          100: '#c5d9ef',
          200: '#9dbfe4',
          300: '#71a4d7',
          400: '#4590cc',
          500: '#1e7bc0',
          600: '#1569ae',
          700: '#0d5189',
          800: '#0a3d6b',
          900: '#072d50',
          950: '#041c33',
        }
      },
      fontFamily: {
        sans:   ['Inter', 'Noto Sans JP', 'ui-sans-serif', 'system-ui', 'sans-serif'],
        mincho: ['Shippori Mincho', 'Noto Serif JP', 'serif'],
      },
    },
  },
  plugins: [require('@tailwindcss/typography')],
}

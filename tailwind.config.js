/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./src/**/*.{html,js}",
    "./index.html",
  ],
  theme: {
    extend: {
      colors: {
        'ice': '#E9E9E9',
        'iceLight': '#F0F0F0',
        'gold': '#F1B03A',
        'goldDark': '#DB9310',
        'pink': '#DD52AB',
        'purple': '#562A79',
      },
      fontFamily: {
        'rosmatika': ['Rosmatika', 'serif'],
        'lato': ['Lato', 'sans-serif']
      },
      boxShadow: {
        'purple-s': '0 1px 2px rgba(84, 28, 131, 0.3)',
        'purple-m': '0 3px 8px rgba(84, 28, 131, 0.6)',
        'purple-b': '0 5px 12px rgba(84, 28, 131, 0.8)',
      },
      maxWidth: {
        // 1440px (90rem) - Layout maximum width
        '1xl': '90rem',
      }
    },
  },
  plugins: [],
}


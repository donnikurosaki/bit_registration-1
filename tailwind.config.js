/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: '#3B82F6', // Bleu
          dark: '#2563EB',
          light: '#93C5FD',
        },
        accent: {
          DEFAULT: '#10B981', // Vert
          dark: '#059669',
          light: '#6EE7B7',
        },
      },
    },
  },
  plugins: [],
} 
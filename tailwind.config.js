/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './templates/**/*.html.twig', // Path to Twig templates
    './assets/**/*.js', // Path to your JS files if using Tailwind in JavaScript
    './assets/styles/**/*.css', // Include CSS if necessary
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}



/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./app/view/auth/**/*.php",
    "./app/view/admin/**/*.php",
    "./app/view/registrar/**/*.php",
    "./app/view/user/**/*.php",      
    "./src/**/*.{html,js,php}", // If your PHP files are inside the src directory
    "./*.php"                   // Include PHP files in the root directory
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}

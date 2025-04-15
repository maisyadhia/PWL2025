// tailwind.config.js
module.exports = {
    content: [
      "./resources/**/*.blade.php",
      "./resources/**/*.js",
      "./resources/**/*.vue",
    ],
    theme: {
        extend: {
          colors: {
            'custom-blue': '#1D4ED8',
            'custom-pink': '#D946EF',
          },
        },
      },
    plugins: [],
  }
  
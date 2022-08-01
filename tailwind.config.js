/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ["./resources/views/**/*.blade.php"],
    theme: {
        extend: {
            fontFamily: {
                sans: ["Roboto", "sans-serif"],
            },
            maxWidth: {
                "8xl": "88rem",
            },
        },
    },
    plugins: [],
};

// tailwind.config.js
const brand = {
    50:'#f5f7f2',100:'#e9eee3',200:'#d5dcc9',300:'#c1cab0',400:'#aab798',
    500:'#889875',600:'#778663',700:'#606d50',800:'#4b5440',900:'#3b4333',
};

export default {
    content: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                brand: brand,
                // se quiser que TUDO que é "blue-*" do projeto use sua paleta:
                 blue: brand,
            },
        },
    },
    plugins: [],
};

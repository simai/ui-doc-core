const mix = require('laravel-mix');
const path = require('path');
require('laravel-mix-jigsaw');

mix.disableSuccessNotifications();
mix.setPublicPath('source/assets/build');
mix.copy('source/_core/_assets/img', 'source/assets/build/img');
mix.jigsaw()
    .js('source/_core/_assets/js/main.js', 'js')
    .css('source/_core/_assets/css/main.css', 'css', [
        require('postcss-import'),
        require('tailwindcss/nesting'),
        require('tailwindcss'),
    ])
    .options({ processCssUrls: false })
    .browserSync({
        server: 'build_local',
        files: ['build_local/**'],
    })
    .sourceMaps()
    .version();

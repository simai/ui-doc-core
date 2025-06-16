const mix = require('laravel-mix');
require('laravel-mix-jigsaw');

mix.disableSuccessNotifications();
mix.setPublicPath('source/assets/build');
mix.copy('source/_core/_assets/img', 'source/assets/build/img');
mix.copy('source/_core/_assets/fonts', 'source/assets/build/fonts');
mix.sass('source/_core/_assets/css/main.scss', 'css');
mix.jigsaw()
    .js('source/_core/_assets/js/main.js', 'js')
    .js('source/_core/_assets/js/turbo.js', 'js')
    .options({ processCssUrls: false })
    .browserSync({
        server: 'build_local',
        files: ['build_local/**'],
    })
    .version();

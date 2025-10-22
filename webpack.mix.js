const mix = require('laravel-mix');
require('laravel-mix-jigsaw');

mix.disableSuccessNotifications();
mix.setPublicPath('source/assets/build');
mix.copy('source/_core/_assets/img', 'source/assets/build/img');
mix.copy('source/_core/_assets/fonts', 'source/assets/build/fonts');
mix.sass('source/_core/_assets/css/main.scss', 'css');
mix.webpackConfig({
    stats: {
        all: false,
    },
    watchOptions: {
        ignored: [
            'node_modules/**',
            'source/assets/build/**',
            'vendor/**',
            'build_local/**'
        ]
    },
});
mix.jigsaw()
    .js('source/_core/_assets/js/main.js', 'js')
    .js('source/_core/_assets/js/turbo.js', 'js')
    .options({ processCssUrls: false })
    .browserSync({
        server: 'build_local',
        files: ['build_local/**.php', 'build_local/**.scss', 'build_local/**.js', 'build_local/**.md', 'build_local/**.css'],
    })
    .version();

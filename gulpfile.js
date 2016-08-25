var elixir = require('laravel-elixir');

elixir(function(mix) {
    mix.styles([
        './node_modules/bootstrap/dist/css/bootstrap.css',
        './node_modules/select2/dist/css/select2.min.css',
        './app/Resources/public/css/AdminLTE.css',
        './app/Resources/public/css/style.css',
        './node_modules/admin-lte/dist/css/skins/skin-blue.css',
        './node_modules/font-awesome/css/font-awesome.css',
    ], 'web/assets/css/vendor.css');


    mix.scripts([
        './node_modules/jquery/dist/jquery.js',
        './node_modules/select2/dist/js/select2.min.js',
        './node_modules/bootstrap/dist/js/bootstrap.js',
        './node_modules/admin-lte/dist/js/app.js',
        './app/Resources/public/js/html5shiv.min.js',
        './app/Resources/public/js/nprogress.js',
    ], 'web/assets/js/vendor.js');

    mix.copy([
        './node_modules/font-awesome/fonts',
        './app/Resources/public/fonts',
    ],'web/assets/fonts');

    mix.copy('./app/Resources/public/js/global.js', 'web/assets/js/global.js');
});
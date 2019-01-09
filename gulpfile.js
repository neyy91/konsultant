var gulp = require('gulp');
var elixir = require('laravel-elixir');
var del = require('del');
var Task = elixir.Task;

elixir.extend('remove', function(path) {
    new Task('remove', function() {
        return del(path);
    });
});

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */
elixir.config.versioning.buildFolder = '';
elixir.config.js.folder = elixir.config.js.outputFolder = 'scripts';
elixir.config.css.folder = elixir.config.css.sass.folder = elixir.config.css.outputFolder = 'styles';
elixir(function(mix) {
    // Styles
    mix.copy('resources/assets/vendor/bootstrap/dist/css/bootstrap.min.css', 'resources/assets/styles/build')
    .sass([
        'for-all.scss',
        'app.scss',
        ], 'resources/assets/styles/build/app.css')
    .styles([
        'build/bootstrap.min.css',
        'build/app.css',
        ], 'public/assets/styles/all.css')
    /* Scripts */
    .copy('resources/assets/vendor/jquery/dist/jquery.min.js', 'resources/assets/scripts/build')
    .copy('resources/assets/vendor/bootstrap/dist/js/bootstrap.min.js', 'resources/assets/scripts/build')
    .copy('resources/assets/vendor/jquery-serialize-object/dist/jquery.serialize-object.min.js', 'resources/assets/scripts/build')
    .scripts([
        'build/jquery.min.js',
        'build/jquery.serialize-object.min.js',
        'build/bootstrap.min.js',
        'data-ajax.js',
        'ajax-message.js',
        'for-all.js',
        'app.js',
        ], 'public/assets/scripts/all.js')
    .scripts([
        'list-general.js',
        ], 'public/assets/scripts/list-general.js')
    .scripts([
        'view-general.js',
        ], 'public/assets/scripts/view-general.js')
    .scripts([
        'list-others.js',
        ], 'public/assets/scripts/list-others.js')
    .scripts([
        'view-others.js',
        ], 'public/assets/scripts/view-others.js')
    .scripts([
        'user.js',
        ], 'public/assets/scripts/user.js')
    /* Fonts */
    .copy('resources/assets/vendor/bootstrap/dist/fonts', 'public/assets/fonts')
    /**
     * Admin mix
     */
    // Styles
    .sass([
        'for-all.scss',
        'admin.scss',
        ], 'resources/assets/styles/build/admin.css')
    .sass([
        'admin-front.scss',
        ], 'public/assets/styles/admin-front.css')
    .styles([
        'build/admin.css',
        ], 'public/assets/styles/admin.css')
    .styles([
        'build/bootstrap.min.css',
        'build/admin.css',
        ], 'public/assets/styles/all.admin.css')
    /* Scripts */
    .scripts([
        'admin.js',
        ], 'public/assets/scripts/admin.js')
    .scripts([
        'admin-front.js',
        ], 'public/assets/scripts/admin-front.js')
    .scripts([
        'build/jquery.min.js',
        'build/bootstrap.min.js',
        'for-all.js',
        'admin.js',
        ], 'public/assets/scripts/all.admin.js');
    // version
    if(elixir.config.production) {
        mix.remove('public/rev-manifest.json').version([
            'assets/styles/all.css',
            'assets/scripts/all.js',
            'assets/styles/all.admin.css',
            'assets/scripts/all.admin.js',
            'assets/scripts/quest_doc.js',
        ]);
    }

});


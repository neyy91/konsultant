let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
// mix.webpackConfig(
// );
mix
  // .setResourceRoot('resources/assets/').setPublicPath('public/assets/')
  // .copy('resources/assets/vendor/bootstrap/dist/css/bootstrap.min.css', 'public/assets/styles')
  .sass('resources/assets/styles/for-all.scss', 'public/assets/styles/for-all.css')
  .sass('resources/assets/styles/app.scss', 'public/assets/styles/app.css')
  .combine([
    'resources/assets/vendor/bootstrap/dist/css/bootstrap.min.css',
    'public/assets/styles/for-all.css',
    'public/assets/styles/app.css',
    ], 'public/assets/styles/all.css')
  // Script
  // .copy('resources/assets/vendor/jquery/dist/jquery.min.js', 'public/assets/scripts')
  // .copy('resources/assets/vendor/bootstrap/dist/js/bootstrap.min.js', 'public/assets/scripts')
  // .copy('resources/assets/vendor/jquery-serialize-object/dist/jquery.serialize-object.min.js', 'public/assets/scripts')
  .combine([
    'resources/assets/vendor/jquery/dist/jquery.min.js',
    'resources/assets/vendor/jquery-serialize-object/dist/jquery.serialize-object.min.js',
    'resources/assets/vendor/bootstrap/dist/js/bootstrap.min.js',
    'resources/assets/vendor/js-cookie/src/js.cookie.js',
    'resources/assets/scripts/data-ajax.js',
    'resources/assets/scripts/ajax-message.js',
    'resources/assets/scripts/pushstream.js',
    'resources/assets/scripts/for-all.js',
    'resources/assets/scripts/app.js',
    'resources/assets/scripts/chat.js',
    ], 'public/assets/scripts/all.js')
  .scripts('resources/assets/scripts/list-general.js', 'public/assets/scripts/list-general.js')
  .scripts('resources/assets/scripts/view-general.js', 'public/assets/scripts/view-general.js')
  .scripts('resources/assets/scripts/list-others.js', 'public/assets/scripts/list-others.js')
  .scripts('resources/assets/scripts/view-others.js', 'public/assets/scripts/view-others.js')
  .scripts('resources/assets/scripts/user.js', 'public/assets/scripts/user.js')
  // Fonts
  .copy('resources/assets/vendor/bootstrap/dist/fonts', 'public/assets/fonts')
  /**
  * Admin mix
  */
  // Styles
  .sass('resources/assets/styles/admin.scss', 'public/assets/styles/admin.css')
  .sass('resources/assets/styles/admin-front.scss', 'public/assets/styles/admin-front.css')
  .combine([
    'resources/assets/vendor/bootstrap/dist/css/bootstrap.min.css',
    'public/assets/styles/for-all.css',
    'public/assets/styles/admin.css',
    ], 'public/assets/styles/all.admin.css')
  // Scripts
  .scripts('resources/assets/scripts/admin.js', 'public/assets/scripts/admin.js')
  .scripts('resources/assets/scripts/admin-front.js', 'public/assets/scripts/admin-front.js')
  .combine([
    'resources/assets/vendor/jquery/dist/jquery.min.js',
    'resources/assets/vendor/bootstrap/dist/js/bootstrap.min.js',
    'public/assets/scripts/for-all.js',
    'public/assets/scripts/admin.js',
    ], 'public/assets/scripts/all.admin.js')

  // enable source map
  .sourceMaps()
;

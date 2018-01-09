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

mix.js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css')
     .sass('resources/assets/sass/style.scss', 'public/css');

// Add near top of file
let ImageminPlugin = require( 'imagemin-webpack-plugin' ).default;


mix.webpackConfig( {
    plugins: [
        new ImageminPlugin( {
//            disable: process.env.NODE_ENV !== 'production', // Disable during development
            pngquant: {
                quality: '95-100',
            },
            test: /\.(jpe?g|png|gif|svg)$/i,
        } ),
    ],
    resolve: {
    alias: {
      'vue$': 'vue/dist/vue.runtime.common.js',
       img: path.resolve(__dirname, './assets/img'),
    	}
	 }
} );
mix.copy( 'resources/assets/img', 'public/img', false );
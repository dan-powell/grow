const mix = require('laravel-mix');
const path = require('path');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

 mix.js('resources/inertia/app.js','public/js/app.js')
    .vue()
    // .postCss('resources/css/app.css', 'public/css', [
    //     require('postcss-nested'),
    //     require('postcss-import'),
    //     require('postcss-mixins')
    // ])
    .css('node_modules/bulma/css/bulma.css', 'public/css',)
    .webpackConfig({
        resolve: {
            extensions: ['.js', '.jsx', '.ts', '.tsx', '.vue'],
            alias: {
                '@': path.resolve('resources/inertia'),
                '~': path.resolve('node_modules'),
            },
        },
        // output: {
        //     chunkFilename: 'js/[name].js?id=[chunkhash]',
        // },
        // stats: {
        //     children: true
        // }
    });

if (mix.inProduction()) {
    mix.version();
}

mix.browserSync({
    proxy: process.env.BS_PROXY, // Could be 'http://appserver' if you're running apache.
    socket: {
        domain: process.env.BS_DOMAIN, // The node proxy domain you defined in .lando.yaml. Must be https?
        port: process.env.BS_PORT // NOT the 3000 you might expect.
    },
    open: false
});

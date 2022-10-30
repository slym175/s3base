let mix = require('laravel-mix');
let glob = require('glob');

const path = require('path');
let directory = path.basename(path.resolve(__dirname));

const source = 'packages/' + directory;
const dist = 'public/vendor/s3base/' + directory;

glob.sync(source + '/resources/assets/sass/base/themes/*.scss').forEach(item => {
    mix.sass(item, dist + '/css/themes');
});

mix
    .postCss(source + '/resources/assets/css/base.css', dist + '/css')
    .postCss(source + '/resources/assets/css/style.css', dist + '/css')
    .js(source + '/resources/assets/js/base.js', dist + '/js')
    .js(source + '/resources/assets/js/base.js', dist + '/js')
    .vue()

    .copyDirectory(dist + '/fonts', source + '/resources/dist/fonts')
    .copyDirectory(dist + '/images', source + '/resources/dist/images')
    .copyDirectory(dist + '/css', source + '/resources/dist/css')
    .copyDirectory(dist + '/js', source + '/resources/dist/js');

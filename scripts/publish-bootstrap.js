'use strict';

const fs = require('node:fs');
const path = require('node:path');
const root = path.resolve(__dirname, '..');
const source = path.join(root, 'node_modules/bootstrap');
const target = path.join(root, 'public/vendor/bootstrap');
for (const [from, to] of [
    ['jquery/dist/jquery.min.js', 'jquery/jquery.min.js'],
    ['jquery/LICENSE.txt', 'jquery/LICENSE.txt'],
    ['moment/min/moment-with-locales.min.js', 'moment/moment.js'],
    ['moment/LICENSE', 'moment/LICENSE'],
]) {
    fs.copyFileSync(path.join(root, 'node_modules', from), path.join(root, 'public/vendor', to));
}
fs.mkdirSync(target, { recursive: true });
for (const [from, to] of [
    ['dist/css/bootstrap.min.css', 'bootstrap.min.css'],
    ['dist/css/bootstrap.min.css.map', 'bootstrap.min.css.map'],
    ['dist/js/bootstrap.bundle.min.js', 'bootstrap.min.js'],
    ['dist/js/bootstrap.bundle.min.js.map', 'bootstrap.bundle.min.js.map'],
    ['LICENSE', 'LICENSE'],
]) {
    fs.copyFileSync(path.join(source, from), path.join(target, to));
}
console.log('Bootstrap 5.3.8, Popper, jQuery und Moment reproduzierbar bereitgestellt.');

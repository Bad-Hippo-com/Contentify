'use strict';

const fs = require('node:fs');
const path = require('node:path');
const root = path.resolve(__dirname, '..');
const source = path.join(root, 'node_modules/bootstrap');
const target = path.join(root, 'public/vendor/bootstrap');
const less = require('less');
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
const glyphicons = path.join(root, 'resources/assets/less/glyphicons.less');
less.render(fs.readFileSync(glyphicons, 'utf8'), {filename: glyphicons, rewriteUrls: 'off'})
    .then(({css}) => {
        fs.writeFileSync(path.join(root, 'public/css/glyphicons.css'), css);
        console.log('Bootstrap 4.6.2, Popper-Bundle und unabhängige Glyphicons bereitgestellt.');
    }).catch(error => { console.error(error); process.exitCode = 1; });

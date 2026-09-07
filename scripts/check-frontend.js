'use strict';

const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');

const root = path.resolve(__dirname, '..');
const packageJson = require(path.join(root, 'package.json'));
const lock = require(path.join(root, 'package-lock.json'));
const backendCss = fs.readFileSync(path.join(root, 'public', 'css', 'backend.css'), 'utf8');
const frontendCss = fs.readFileSync(path.join(root, 'public', 'css', 'frontend.css'), 'utf8');
const bootstrapJs = fs.readFileSync(path.join(root, 'public', 'vendor', 'bootstrap', 'bootstrap.min.js'), 'utf8');
const layouts = [
    path.join(root, 'resources', 'views', 'backend', 'layout_main.blade.php'),
    path.join(root, 'app', 'Modules', 'MorpheusTheme', 'Resources', 'Views', 'layout.blade.php'),
    path.join(root, 'app', 'Modules', 'PhobosTheme', 'Resources', 'Views', 'layout.blade.php'),
].map((file) => fs.readFileSync(file, 'utf8'));

function filesBelow(directory) {
    return fs.readdirSync(directory, { withFileTypes: true }).flatMap((entry) => {
        const absolute = path.join(directory, entry.name);
        return entry.isDirectory() ? filesBelow(absolute) : [absolute];
    });
}

assert.equal(Number(process.versions.node.split('.')[0]), 24, 'Node.js 24 ist erforderlich.');
assert.equal(packageJson.devDependencies.less, '4.9.1');
assert.equal(packageJson.dependencies.bootstrap, '3.4.1');
assert.equal(packageJson.devDependencies.grunt, undefined);
assert.equal(packageJson.devDependencies['grunt-contrib-less'], undefined);
assert.equal(packageJson.devDependencies['grunt-contrib-watch'], undefined);
assert.equal(packageJson.devDependencies['jit-grunt'], undefined);
assert.equal(lock.packages['node_modules/less'].version, '4.9.1');
assert.equal(lock.packages['node_modules/bootstrap'].version, '3.4.1');
assert.equal(fs.readFileSync(path.join(root, 'resources', 'assets', 'less', 'bootstrap', 'version.txt'), 'utf8').trim(), '3.4.1');
assert.match(bootstrapJs, /Bootstrap v3\.4\.1/);
assert.match(backendCss, /Bootstrap v3\.4\.1/);
assert.match(frontendCss, /Bootstrap v3\.4\.1/);
for (const layout of layouts) {
    assert.match(layout, /vendor\/bootstrap\/bootstrap\.min\.js/);
    assert.doesNotMatch(layout, /maxcdn\.bootstrapcdn\.com\/bootstrap/);
}
for (const source of filesBelow(path.join(root, 'node_modules', 'bootstrap', 'less'))) {
    const relative = path.relative(path.join(root, 'node_modules', 'bootstrap', 'less'), source);
    assert.deepEqual(
        fs.readFileSync(path.join(root, 'resources', 'assets', 'less', 'bootstrap', relative)),
        fs.readFileSync(source),
        `Bootstrap-LESS weicht vom festgeschriebenen npm-Paket ab: ${relative}`,
    );
}
for (const font of fs.readdirSync(path.join(root, 'node_modules', 'bootstrap', 'fonts'))) {
    const published = fs.readFileSync(path.join(root, 'public', 'css', 'fonts', font));
    const source = fs.readFileSync(path.join(root, 'node_modules', 'bootstrap', 'fonts', font));
    if (font.endsWith('.svg')) {
        assert.equal(published.toString().trim(), source.toString().trim());
    } else {
        assert.deepEqual(published, source, `Bootstrap-Schriftdatei weicht vom festgeschriebenen npm-Paket ab: ${font}`);
    }
}
assert.deepEqual(
    fs.readFileSync(path.join(root, 'public', 'vendor', 'bootstrap', 'bootstrap.min.js')),
    fs.readFileSync(path.join(root, 'node_modules', 'bootstrap', 'dist', 'js', 'bootstrap.min.js')),
);
assert.deepEqual(
    fs.readFileSync(path.join(root, 'public', 'vendor', 'bootstrap', 'LICENSE')),
    fs.readFileSync(path.join(root, 'node_modules', 'bootstrap', 'LICENSE')),
);
assert.match(backendCss, /\.contentify-editor-toolbar/);
assert.match(backendCss, /\.sun-editor/);
assert.match(backendCss, /url\(['"]?\.\/fonts\/glyphicons-halflings-regular\.woff2/);
assert.doesNotMatch(backendCss, /url\(['"]?\.\.\/fonts\/glyphicons-halflings/);
assert.match(backendCss, /@import url\(['"]https:\/\/fonts\.googleapis\.com\/css\?family=Open\+Sans:400,700['"]\)/);

console.log('OK: Node-24-, LESS-, Bootstrap-3.4.1- und Editor-Asset-Verträge sind erfüllt.');

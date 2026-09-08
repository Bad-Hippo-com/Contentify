'use strict';

const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');

const root = path.resolve(__dirname, '..');
const packageJson = require(path.join(root, 'package.json'));
const lock = require(path.join(root, 'package-lock.json'));
const backendCss = fs.readFileSync(path.join(root, 'public', 'css', 'backend.css'), 'utf8');
const frontendCss = fs.readFileSync(path.join(root, 'public', 'css', 'frontend.css'), 'utf8');
const glyphiconsCss = fs.readFileSync(path.join(root, 'public/css/glyphicons.css'), 'utf8');
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
assert.equal(packageJson.dependencies.bootstrap, '5.3.8');
assert.equal(packageJson.devDependencies.grunt, undefined);
assert.equal(packageJson.devDependencies['grunt-contrib-less'], undefined);
assert.equal(packageJson.devDependencies['grunt-contrib-watch'], undefined);
assert.equal(packageJson.devDependencies['jit-grunt'], undefined);
assert.equal(lock.packages['node_modules/less'].version, '4.9.1');
assert.equal(lock.packages['node_modules/bootstrap'].version, '5.3.8');
assert.equal(fs.readFileSync(path.join(root, 'resources', 'assets', 'less', 'bootstrap', 'version.txt'), 'utf8').trim(), '3.4.1');
assert.match(bootstrapJs, /Bootstrap v5\.3\.8/);
assert.match(backendCss, /Bootstrap\s+v5\.3\.8/);
assert.match(frontendCss, /Bootstrap\s+v5\.3\.8/);
assert.doesNotMatch(bootstrapJs, /Bootstrap v3\./);
assert.doesNotMatch(backendCss, /\.modal\.in\b/);
assert.doesNotMatch(frontendCss, /\.modal\.in\b/);
for (const layout of layouts) {
    assert.match(layout, /vendor\/bootstrap\/bootstrap\.min\.js/);
    assert.doesNotMatch(layout, /maxcdn\.bootstrapcdn\.com\/bootstrap/);
}
assert.deepEqual(
    fs.readFileSync(path.join(root, 'public/vendor/bootstrap/bootstrap.min.css')),
    fs.readFileSync(path.join(root, 'node_modules/bootstrap/dist/css/bootstrap.min.css')),
);
assert.deepEqual(
    fs.readFileSync(path.join(root, 'public', 'vendor', 'bootstrap', 'bootstrap.min.js')),
    fs.readFileSync(path.join(root, 'node_modules', 'bootstrap', 'dist', 'js', 'bootstrap.bundle.min.js')),
);
assert.deepEqual(
    fs.readFileSync(path.join(root, 'public', 'vendor', 'bootstrap', 'LICENSE')),
    fs.readFileSync(path.join(root, 'node_modules', 'bootstrap', 'LICENSE')),
);
assert.match(backendCss, /\.contentify-editor-toolbar/);
assert.match(backendCss, /\.sun-editor/);
assert.match(glyphiconsCss, /url\(['"]?\.\/fonts\/glyphicons-halflings-regular\.woff2/);
assert.doesNotMatch(backendCss + frontendCss, /url\([^)]*glyphicons-halflings/);
assert.match(backendCss, /@import url\(['"]https:\/\/fonts\.googleapis\.com\/css\?family=Open\+Sans:400,700['"]\)/);

const firstPartyViews = [...filesBelow(path.join(root, 'app')), ...filesBelow(path.join(root, 'resources/views'))]
    .filter(file => file.endsWith('.blade.php'));
for (const file of firstPartyViews) {
    assert.doesNotMatch(fs.readFileSync(file, 'utf8'), /data-(?:toggle|dismiss|target|ride|parent)=/, file);
}
const sharedJs = fs.readFileSync(path.join(root, 'public/vendor/contentify/contentify.js'), 'utf8');
const backendJs = fs.readFileSync(path.join(root, 'public/vendor/contentify/backend.js'), 'utf8');
const pickerJs = fs.readFileSync(path.join(root, 'public/vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.js'), 'utf8');
assert.match(sharedJs, /new bootstrap\.Modal/);
assert.match(sharedJs, /instance\.dispose\(\)/);
assert.match(backendJs, /new bootstrap\.Tooltip/);
assert.match(pickerJs, /bootstrap\.Collapse\.getOrCreateInstance/);
assert.doesNotMatch(sharedJs + backendJs + pickerJs, /\.(?:modal|tooltip|collapse)\(['"](?:hide|show)?['"]?\)/);
assert.equal(lock.packages['node_modules/popper.js'], undefined);
console.log('OK: Node-24-, LESS-, Bootstrap-5.3.8- und Editor-Asset-Verträge sind erfüllt.');

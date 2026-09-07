'use strict';

const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');

const root = path.resolve(__dirname, '..');
const packageJson = require(path.join(root, 'package.json'));
const lock = require(path.join(root, 'package-lock.json'));
const backendCss = fs.readFileSync(path.join(root, 'public', 'css', 'backend.css'), 'utf8');

assert.equal(Number(process.versions.node.split('.')[0]), 24, 'Node.js 24 ist erforderlich.');
assert.equal(packageJson.devDependencies.less, '4.9.1');
assert.equal(packageJson.devDependencies.grunt, undefined);
assert.equal(packageJson.devDependencies['grunt-contrib-less'], undefined);
assert.equal(packageJson.devDependencies['grunt-contrib-watch'], undefined);
assert.equal(packageJson.devDependencies['jit-grunt'], undefined);
assert.equal(lock.packages['node_modules/less'].version, '4.9.1');
assert.match(backendCss, /\.contentify-editor-toolbar/);
assert.match(backendCss, /\.sun-editor/);
assert.match(backendCss, /url\(['"]?\.\/fonts\/glyphicons-halflings-regular\.woff2/);
assert.doesNotMatch(backendCss, /url\(['"]?\.\.\/fonts\/glyphicons-halflings/);
assert.match(backendCss, /@import url\(['"]https:\/\/fonts\.googleapis\.com\/css\?family=Open\+Sans:400,700['"]\)/);

console.log('OK: Node-24-, LESS- und Editor-Asset-Verträge sind erfüllt.');

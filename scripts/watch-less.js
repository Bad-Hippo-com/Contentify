'use strict';

const { spawn } = require('node:child_process');
const { watch } = require('node:fs');
const path = require('node:path');

const root = path.resolve(__dirname, '..');
const source = path.join(root, 'resources', 'assets', 'less', 'backend.less');
const destination = path.join(root, 'public', 'css', 'backend.css');
const lessc = path.join(root, 'node_modules', 'less', 'bin', 'lessc');
const once = process.argv.includes('--once');

let building = false;
let queued = false;
let debounce;

function build() {
    if (building) {
        queued = true;
        return;
    }

    building = true;
    const child = spawn(process.execPath, [
        lessc,
        '--rewrite-urls=all',
        '--math=parens-division',
        source,
        destination
    ], {
        cwd: root,
        stdio: 'inherit'
    });

    child.on('exit', (code) => {
        building = false;

        if (once) {
            process.exitCode = code ?? 1;
            return;
        }

        if (queued) {
            queued = false;
            build();
        }
    });
}

build();

if (!once) {
    const watcher = watch(source, () => {
        clearTimeout(debounce);
        debounce = setTimeout(build, 150);
    });

    process.once('SIGINT', () => {
        watcher.close();
        process.exit(130);
    });
}

# Contentify defect and risk register

Local workstream version: **0.2.4**
Last updated: **2026-09-06 19:23 CEST**
Scope: upstream commit `5bd21fb7879cf0fbede159a6dc71d0554c8d2bde`

## Open blockers

### BUG-001 - PHP 8 is not supported despite the documentation claim

Severity: **blocker**
Status: **open**

PHP 8 reserves `match` as a language keyword. Contentify declares two classes
named `Match` and references them throughout the Matches and Cups modules.
PHP 8.5.10 therefore reports nine syntax failures among 726 project PHP files.
Representative failure:

`app/Modules/Matches/Match.php:38: unexpected token "match", expecting identifier`

`php artisan --version` exits with code 255 on PHP 8.5.10. The upstream README
and installation wiki nevertheless claim PHP 8 support when Composer is run
with `--ignore-platform-reqs`. Ignoring dependency constraints cannot repair
invalid PHP syntax.

### BUG-002 - Production dependencies contain known vulnerabilities

Severity: **blocker**
Status: **open**

`composer audit --locked --no-dev` with Composer 2.10.3 reports **47 known
advisories**: 1 critical, 18 high, 26 medium and 2 low. Affected runtime
packages include Laravel Framework 6.20.30, Guzzle 7.3.0, Guzzle PSR-7 2.0.0,
League CommonMark 1.6.6, Symfony components and Carbon 2.51.1.

The critical Laravel finding applies to versions below 6.20.44. This checkout
locks 6.20.30. A second high Laravel finding requires at least 6.20.45, while
the current application lock remains below both fixed versions.

### BUG-003 - Current Composer installation is not reproducible

Severity: **high**
Status: **open**

- The bundled `composer.phar` is obsolete, emits extensive deprecation output
  on PHP 8.5 and does not provide the `audit` command.
- Current Composer reports that `composer.lock` is out of date relative to
  `composer.json`.
- A normal PHP 8.5 install is rejected by package PHP constraints. It proceeds
  only with `--ignore-platform-reqs`, installing 112 old packages despite the
  declared incompatibilities.
- Two locked packages are abandoned: `oyejorge/less.php` and
  `sebastian/resource-operations`.

### BUG-004 - Front-end dependency installation fails by default

Severity: **high**
Status: **open**

With Node.js 24.15.0 and npm 11.12.1, `npm install` fails with `ERESOLVE`:
`grunt-contrib-watch@0.6.1` requires Grunt `~0.4.0`, while the project requests
Grunt `^1.3.0`. Installation only succeeds with `--legacy-peer-deps`.

The generated npm dependency tree contains **23 vulnerabilities**: 3 critical,
15 high and 5 moderate. Both direct build dependencies
`grunt-contrib-less@1.x` and `grunt-contrib-watch@0.6.x` require breaking
upgrades. Several transitive packages are deprecated or unsupported.

### BUG-005 - The automated tests do not validate the CMS

Severity: **high**
Status: **open**

Only two placeholder tests exist. On the last syntactically compatible PHP
release, PHP 7.4.33, the unit test `assertTrue(true)` passes and the sole feature
test fails because `/` returns 404 instead of 200. There are no meaningful
module, authorization, installer, upload, database, or security regression
tests.

### BUG-006 - Container definitions are unsafe and non-reproducible

Severity: **high**
Status: **open**

- `php:7-apache`, `mysql:latest`, `adminer` and `contentify:latest` are unpinned.
- PHP 7 is end-of-life; PHP 7.4 lost security support in November 2022.
- The Dockerfile recursively grants mode `777` to `storage`, `bootstrap/cache`
  and all of `public`.
- The Compose file publishes the CMS on host port 80 and Adminer on 8080,
  while embedding development database credentials.
- There is no container health check, persistent database volume, application
  environment contract, migration/installer gate, or CI build verification.

### BUG-007 - Production defaults and installation documentation are stale

Severity: **medium**
Status: **open**

`.env.example` defaults to `APP_ENV=local`, `APP_DEBUG=true`, a blank app key,
root database access without a password, Mailtrap host settings and obsolete
Laravel 6 mail variable names. The wiki was last edited in 2021, contains the
impossible requirement `PHP 7.6.5`, recommends broad CHMOD 777 permissions and
claims PHP 8 compatibility contradicted by the source.

### BUG-008 - Comment update passes undefined view variables

Severity: **low**
Status: **open**

`Contentify\Comments::update()` returns the `comments.comment` view using
`compact('comment', 'foreignType', 'foreignId')`, but the method defines only
`$comment`. This can emit undefined-variable warnings and indicates an
uncovered code path.

### BUG-009 - Docker Compose silently changes dollar signs in staging secrets

Severity: **high**
Status: **mitigated on staging; deployment path open, 2026-09-06 18:43 CEST**

The requested administrator password ended in two dollar signs. During the
staging installation Docker Compose reduced that sequence to one dollar sign
while loading `.env.staging`. The installer therefore created a valid password
hash for a different 11-character value. The account itself is active,
unbanned, assigned to `super-admins`, and its session directory is writable.

Five failed login attempts then activated Sentinel's IP and user throttles.
The default throttle interval is 900 seconds and the threshold is five
attempts. The browser correctly displayed the remaining lock time, but the
English exception text is exposed unchanged on the German login page. Secrets
containing Compose interpolation characters require an explicit round-trip
check before the installer is considered successful.

The staging administrator hash was reset to the exact requested value and only
the five user plus six client-IP throttle records were removed. A real browser
login and the authenticated admin dashboard then succeeded. The deployment
path remains defective until clean installs preserve such secrets unchanged.

## Resolved application defects

### BUG-010 - Cached admin navigation exposes the internal Nginx hostname

Severity: **high**
Status: **resolved 2026-09-06 19:03 CEST**

After successful authentication, the admin dashboard itself loads through
the external staging address, but many links in the left navigation point to
`http://nginx/admin/...`. Quick-access links on the same page correctly use the
external address. This makes part of the backend navigation unusable from a
client and suggests that absolute URLs generated during an internal request
were cached without a safe canonical application URL or host boundary.

Resolution: version `0.2.4` caches a neutral base-URL placeholder instead of
the host seen while the menu HTML is generated. The placeholder is replaced
with the current request's application root only when the menu is returned.
This retains support for installations in a subdirectory while preventing an
internal Docker hostname from becoming permanent shared cache content. The
legacy navigation cache entry must be refreshed once during deployment.
The staging regression check generated the cache while forcing
`http://nginx`, confirmed that the cached value contains no such hostname,
then reused that cache with an external staging URL. Browser tests opened
News, Pages and Configuration from the left menu at the staging IP.

## Staging defects

### STAGE-004 - Admin icon and vendor assets are absent from the image

Severity: **high**
Status: **resolved 2026-09-06 18:57 CEST**

The backend correctly includes Font Awesome from
`vendor/font-awesome/css/all.min.css`, and the repository contains the CSS plus
webfont files. The staging build context does not contain them: the broad
`.dockerignore` entry `vendor` excludes both Composer's root `vendor` directory
and the required public directory `public/vendor`.

Live requests for the Font Awesome CSS and `fa-solid-900.woff2` therefore return
HTTP 500 with Contentify's HTML crash page instead of CSS/font content. The same
exclusion removes jQuery, CKEditor and other browser assets below
`public/vendor`, so the visible missing icons are only the first symptom.
`backend.css` itself returns HTTP 200. Its separate Glyphicons font URL also
returns HTTP 500 because `public/css/fonts` is absent from the upstream tree.

This is a staging packaging defect, not GitHub issue #614: that old issue is
about a Valorant game icon and the current `3.2-dev` source already contains the
corresponding game-icon change.

Resolution: `.dockerignore` still excludes the root dependency tree but now
explicitly re-includes `public/vendor` and all descendants. The 272 tracked
browser assets were restored to the remote source and persistent public volume.
The five missing Glyphicons files were taken unchanged from the official
Bootstrap 3.3.7 npm package. Rebuilt `0.2.3` app and Nginx images both contain
the required assets. Font Awesome CSS/font, jQuery, CKEditor, backend CSS and all
five Glyphicons formats return HTTP 200 with suitable MIME types. A browser
reload after full service recreation displays icons throughout the dashboard.

## Closed during staging installation

### STAGE-001 - PHP-FPM rejected the initial access-log duration format

Severity: **deployment blocker**
Status: **resolved 2026-09-06 18:12 CEST**

PHP-FPM 7.4 rejected the original duration modifier and restarted with exit
code 78. The access format was changed to the PHP 7.4-compatible `%d` field.
The rebuilt app container then remained up and request logging succeeded.

### STAGE-002 - Public runtime data was not shared with Nginx

Severity: **high**
Status: **resolved 2026-09-06 18:17 CEST**

The first container layout shared only `public/uploads`. Contentify can also
write compiled CSS, RSS and share data below `public`. A persistent
`public-data` volume is now mounted read/write by PHP-FPM and read-only by
Nginx, and all installer directory checks pass.

### STAGE-003 - Staging baseline installation

Status: **verified 2026-09-06 18:25 CEST**

Contentify installed 65 database tables, created the administrator and
installation marker, serves the homepage and login page with HTTP 200, accepts
an administrator login, and serves the authenticated backend with HTTP 200.
No new container errors appeared during the final validation interval.

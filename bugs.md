# Contentify defect and risk register

Local workstream version: **0.5.0**
Last updated: **2026-09-06 21:01 CEST**
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

After the controlled `0.5.0` Laravel-7 rung, `composer audit --locked --no-dev`
with Composer 2.10.3 reports **12 known advisories in two packages** instead of
39 in eight packages on Laravel 6. The remaining findings affect Laravel
Framework 7.30.7 and League CommonMark. Laravel 7 and PHP 7.4 are unsupported;
production approval therefore remains blocked.

### BUG-003 - Current Composer installation is not reproducible

Severity: **high**
Status: **partially resolved; modern Composer required, 2026-09-06 20:17 CEST**

- The bundled `composer.phar` is obsolete, emits extensive deprecation output
  on PHP 8.5 and does not provide the `audit` command.
- Version `0.3.0` regenerates `composer.lock`; current Composer now reports
  `composer.json` and the lock as valid with `--strict`.
- A normal PHP 8.5 install is rejected by package PHP constraints. It proceeds
  only with `--ignore-platform-reqs`, installing 112 old packages despite the
  declared incompatibilities.
- The refreshed lock still contains four abandoned production packages:
  `invisnik/laravel-steam-auth`, `laravelcollective/html`, `oyejorge/less.php`
  and `swiftmailer/swiftmailer`; development dependencies add further legacy
  warnings.

### BUG-013 - Laravel-6 patch level was below available security fixes

Severity: **high**
Status: **resolved as migration rung 2026-09-06 20:17 CEST**

The historical lock selected Laravel 6.20.30 although compatible security fixes
exist in the same framework major. Version `0.3.0` regenerates the lock on the
real PHP 7.4 baseline and selects Laravel 6.20.45 plus its compatible dependency
set. The isolated candidate passed strict Composer validation, Artisan boot,
five unit tests with 16 assertions, both smoke tests and a complete first-party
PHP syntax pass. This resolves only the outdated Laravel-6 patch level; BUG-001
and BUG-002 remain open until the later PHP/Laravel rungs remove the unsupported
stack and all advisories.

### BUG-016 - Laravel 7 required explicit application compatibility changes

Severity: **high**
Status: **resolved as migration rung 0.5.0, 2026-09-06 20:51 CEST**

The first Laravel-7 dependency probe stopped during package discovery because
Contentify's exception handler still type-hinted `Exception`, while Laravel 7
requires `Throwable`. The application handler now uses `Throwable` for report
and render. The Laravel-7 session-cookie default is adopted and all three own
Artisan commands return an explicit integer success code. Sentinel is raised
from 3.0.4 to 4.0.0 because the former restricts Illuminate Support to version
6. The isolated PHP-7.4 candidate boots as Laravel 7.30.7, lists 540 routes,
passes eight focused unit tests with 27 assertions, both smoke tests and the
complete first-party syntax pass. The sole full-suite failure remains the
pre-existing placeholder feature test that expects the uninstalled test root
to answer with HTTP 200.

### BUG-017 - Unknown routes are returned as server errors

Severity: **medium**
Status: **open; confirmed on staging 2026-09-06 21:01 CEST**

The original production exception handler renders its generic HTTP-500 view
for every exception other than `ModelNotFoundException`. Consequently an
unknown route such as `/login` and an absent static file return HTTP 500 instead
of 404. The real Contentify login route `/auth/login` and the Font Awesome 5
assets are present and return HTTP 200, so this is not a Laravel-7 or missing-
asset regression. A focused exception-handler test and correct handling of
Symfony's `NotFoundHttpException` are still required.

### BUG-014 - Remote dashboard feed was rendered without validation

Severity: **high**
Status: **resolved in 0.4.0, 2026-09-06 20:28 CEST**

The original dashboard trusted remote JSON fields and rendered message text and
icon names as raw HTML. A compromised or malformed feed could therefore inject
markup into an authenticated administrator page. The previous single cache key
also meant that one failed source could suppress the entire feed output.

Version `0.4.0` normalizes each remote message, accepts only HTTP(S) links and
safe icon identifiers, skips incomplete records and escapes message text in the
Blade view. Original Contentify and Bad Hippo now use independent cache entries
and failure handling. This preserves the original feed while adding our clearly
labelled GitHub-backed feed. Three focused regression tests pass as part of the
eight-test, 27-assertion unit suite.

### BUG-015 - Newer Bad-Hippo feed appeared below the historical feed

Severity: **low**
Status: **resolved in 0.4.1, 2026-09-06 20:38 CEST**

Both feeds rendered correctly in `0.4.0`, but the original source was declared
first and therefore occupied the upper dashboard position despite its newest
message dating from 2020. Version `0.4.1` makes the maintained Bad-Hippo source
the first dashboard feed and keeps Contentify Original directly below it.

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

## Prüfung der Original-Issues

### Erste Prüfung der offenen Original-Issues

Status: **laufend, sieben Issues geprüft 2026-09-06 19:55 CEST**

- `#645` und `#663`: derselbe bestätigte PHP-8-Blocker durch die reservierte
  Klasse `Match`; noch nicht behoben, bei uns als Issue `#4` geführt.
- `#658`: Docker-/Installationsweg im Fork grundsätzlich reproduziert; saubere
  Testinstallation und GHCR-Veröffentlichung fehlen noch.
- `#613`: OpenGraph-Typfehler wurde bereits von Chris in Upstream-Commit
  `7e0294ec` für `3.2-dev` korrigiert; Browser-Charakterisierung folgt.
- `#614`: Valorant- und weitere Spielsymbole wurden bereits von Chris in
  Upstream-Commit `4630a7aa` für `3.2-dev` ergänzt.
- `#650`: reproduzierter Mehrfachupload-Fehler. `Uploader::uploadModelFiles()`
  beendete seine Schleife bereits nach dem ersten konfigurierten Dateifeld.
  Der Fix ist in Bad Hippo `0.2.5` enthalten und auf Staging getestet.
- `#624`: die ungefangene Abfrage von `disk_free_space()` konnte auf
  eingeschränktem Hosting das Dashboard abbrechen. Bad Hippo `0.2.6` behandelt
  nicht verfügbare Angaben als unbekannt und behält echte Platzwarnungen bei.

In den ersten fünf Original-Issues wurde der Befund veröffentlicht; die
verifizierte Lösung für `#650` folgt mit dem öffentlichen Commit. Jeder Hinweis
nennt den inoffiziellen Community-Status sowie die aktuelle Baseline aus PHP
7.4 und Laravel 6.20.30. Eine PHP-8- oder Produktionsfreigabe wurde ausdrücklich
nicht behauptet.

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

### BUG-011 - Only the first configured upload field is processed

Severity: **medium**
Status: **resolved 2026-09-06 19:37 CEST**
Original issue: `Contentify/Contentify#650`

`Uploader::uploadModelFiles()` returned from inside its `foreach` loop. A model
with several configured upload fields therefore processed only the first field,
whether or not that field contained a file. For teams, this meant that `image`
worked but `banner` was ignored; no exception occurred, so Laravel and Nginx had
nothing to log. Permissions and a separate banner folder were not the cause.

Version `0.2.5` returns only after the loop has examined every configured field.
The PHPUnit regression test covers logo plus banner and banner without logo;
PHPUnit 9.5.8 passed both tests with 11 assertions on PHP 7.4. A standalone
smoke test also ran successfully inside the real PHP 7.4/Laravel 6.20.30
staging container for both cases.

### BUG-012 - Disk-space warning can crash on restricted hosting

Severity: **medium**
Status: **resolved 2026-09-06 19:55 CEST**
Original issue: `Contentify/Contentify#624`

The dashboard checked only whether `disk_free_space()` exists, then called it
twice without handling warnings, exceptions or a `false` result. The diagnostics
page used the same unsafe pattern. Hosting restrictions such as `open_basedir`
or an unreadable path can therefore turn an informational check into a Laravel
exception. Removing the warning entirely would also remove useful monitoring.

Version `0.2.6` adds `Contentify\DiskSpace`, calls the native function once with
warning suppression and exception handling, validates the result, and returns
`null` when the host cannot provide it. Dashboard and diagnostics now share the
same behavior. Unit and staging smoke tests cover a readable and a deliberately
missing path.

## Staging defects

### STAGE-005 - Nginx retains the replaced app container address

Severity: **high during deployment**
Status: **operationally mitigated 2026-09-06 19:44 CEST**

After only `app` and `jobs` were recreated for version `0.2.5`, the already
running Nginx worker continued using the removed app container's resolved IP.
Requests temporarily returned HTTP 502 although the new PHP-FPM container was
healthy. Recreating Nginx resolved `app` again and restored HTTP 200.

The staging runbook now requires Nginx recreation whenever the app container is
replaced. A later delivery-hardening step should make upstream resolution
dynamic or add an atomic deployment procedure so this cannot be omitted.

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

# Contentify project assessment

Local workstream version: **0.5.0**
Assessment/update time: **2026-09-06 21:01 CEST**
Workspace: `E:\WorkSpace\contentify`

## Purpose

This document records what was downloaded, what was tested, the evidence found,
and whether the upstream Contentify CMS can be implemented on a current stack.

## Source state

- Community repository: `https://github.com/Bad-Hippo-com/Contentify.git`
- Upstream repository: `https://github.com/Contentify/Contentify.git`
- Local branch: `main`, intended to track `origin/main`
- Commit: `5bd21fb7879cf0fbede159a6dc71d0554c8d2bde`
- Commit date: 2022-11-20 15:35:06 +0100
- Upstream label: Contentify v3.2 ALPHA
- Latest upstream release/tag: v3.1, also described by its README as beta-based
- License: MIT
- Size reviewed: 726 first-party PHP files, approximately 32,101 PHP lines,
  44 module directories and only two placeholder tests
- GitHub state rechecked on 2026-09-06: 32 open issues and 4 open pull requests;
  the latest `3.2-dev` commit remains `5bd21fb` from 2022-11-20, all four open
  pull requests are old Dependabot dependency bumps, and no GitHub Actions
  workflow is present

The default branch is not a completed stable release. Its changelog still lists
both breaking changes and changes as `TBA`. Upstream provides no real v3.1 to
v3.2 upgrade and instructs users to reinstall and manually transfer data.

## Environment used for verification

- Windows PowerShell workspace
- Node.js 24.15.0 / npm 11.12.1
- PHP 8.5.10 portable runtime for current compatibility tests
- PHP 7.4.33 portable runtime for historical compatibility tests
- Composer 2.10.3 for validation and advisory checks
- Bundled project Composer executable for compatibility comparison
- Staging: internal Debian 13.6 host, Docker 26.1.5 and Compose 2.26.1
- Staging containers: Nginx 1.26.3, PHP-FPM 7.4.33 and MariaDB 10.11

Temporary runtimes and dependency directories were used only for verification.
Generated lock/build output was removed before documentation was written.

## Verification results

| Check | Result |
| --- | --- |
| Git checkout | Pass; official default branch cloned cleanly |
| PHP 8.5 lint | Fail; 9 of 726 first-party files have syntax errors |
| PHP 8.5 Artisan | Fail; exit code 255 |
| PHP 8.5 PHPUnit | Fail; 2 tests, 2 errors |
| PHP 7.4 lint | Pass; 726 files parse |
| PHP 7.4 Artisan | Pass; `0.5.0` candidate reports Laravel 7.30.7 |
| PHP 7.4 PHPUnit | Fail; unit placeholder passes, feature placeholder gets 404 |
| Composer validation | Pass for `0.5.0`; regenerated lock is valid with `--strict` |
| Composer normal install on PHP 8.5 | Fail; incompatible PHP/package constraints |
| Composer install with ignored requirements | Packages extract, but this is not a runnable current build |
| Composer production audit | Improved but fails; 12 advisories in two packages |
| npm install | Fail; direct peer-dependency conflict |
| npm install with legacy resolution | Completes with 23 vulnerabilities |
| Grunt LESS build after legacy install | Pass; one stylesheet compiled |
| Docker/Compose review | Fail for current production readiness |

## Currentness assessment

Contentify is obsolete as delivered:

- Laravel 7 no longer receives official bug or security fixes. Current Laravel
  is 13, whose supported PHP range is 8.3 through 8.5.
- PHP 7.4, the newest runtime on which the first-party source parses cleanly,
  reached end-of-life in November 2022.
- PHP 8.5 is actively supported, but Contentify cannot parse on it because
  `match` became a reserved keyword in PHP 8.
- Locked runtime libraries mostly date from 2017-2021 and have accumulated 47
  production advisories.
- The old Travis badge/configuration and stale-bot file are the only automation;
  there is no current CI pipeline.
- The installation wiki was last edited in August 2021 and contradicts both PHP
  version history and the actual source behavior.

## Feasibility assessment

**Unchanged deployment: not feasible for a responsible internet-facing
production system.** It either fails on supported PHP or runs on unsupported
PHP with vulnerable dependencies.

**Modernized fork: feasible with high effort.** The application has a coherent
Laravel module structure, a working historical Artisan bootstrap and a legacy
asset build, so the behavior can be preserved. The work must include language
compatibility, framework/package replacement, delivery hardening and a new test
baseline. This should be scoped as a major release, not a patch.

### Approved migration method

The modernization will use two isolated systems on separate IP addresses.
The internal staging host is provisioned and is the development and
fault-analysis system where changes may break. The test-system IP is pending.
Test is the clean installation and acceptance system for a candidate already
proven on staging. The addresses will be documented when provisioned. Staging
and test must not share application data, uploads, cache, sessions or secrets.

The first technical target is to reproduce and stabilize Contentify on staging
using its historically compatible PHP 7.4/Laravel 6 stack. That is a migration
baseline, not an approved public deployment. Characterization tests are added
before crossing the PHP 8 boundary. PHP, Laravel and dependencies are then
raised in separate, measurable increments on staging. For every candidate, all
prerequisites are recreated on test and the application is installed cleanly.
Only an error-free test installation and representative trial can approve the
version for Public.

PHP 8.5/Laravel 13 is no longer prescribed as an immediate destination. The
final version combination will be chosen from actual compatibility results and
must be supported, secure, reproducible and advisory-clean. This reduces
breakage and makes the exact cause of each regression identifiable.

### Central error logging baseline

The repository now prepares one central log root per host:
`/var/log/contentify`. Test and staging use identical structure on their own
servers and never share the directory. Laravel's default channel is a 30-day
daily JSON log with configurable severity. Every Laravel record includes the
environment, hostname, request ID when supplied by the webserver, HTTP method
and request path. Separate prepared Laravel channels exist for security/audit
and jobs.

Deployment templates configure PHP/PHP-FPM, Nginx or Apache, queue workers,
the scheduler and deployment output to use separate files in the same root.
External component files are rotated daily for 30 rotations. Laravel owns its
own daily rotation. All files are planned with mode 0640, and HTTP error display
remains disabled so diagnostic details are logged rather than disclosed.

The Laravel configuration was runtime-tested on PHP 7.4.33 with Laravel
6.20.30. It was then deployed and verified on staging. Laravel wrote valid JSON
containing `environment=staging` and `build_version=0.2.0`; PHP-FPM, Nginx and
the Contentify job runner also wrote to their separate files. Logrotate
accepted the installed 30-day policy in a dry run.

The installed staging baseline started at version `0.2.0`; the diagnosed login
credential defect is recorded as local workstream version `0.2.1`. At that time,
the original CMS identifier was still `3.2-dev`. Bad Hippo now identifies its
continued development as `3.3-dev` while the separate `0.x.y` workstream version
keeps every staged change traceable.

## Effort and delivery estimate

For full functional parity across the current 44 module directories, the base
engineering estimate is **146-235 person-days**. A 20% uncertainty reserve is
required because the project has virtually no regression suite, outdated
installation behavior and several dependencies that may need replacement.
The responsible planning range is therefore **175-282 person-days**, or about
**1,400-2,256 hours** at eight hours per person-day.

Expected elapsed time is 9-14 months for one experienced full-time developer,
6-9 months for two engineers, or 4.5-7 months for a coordinated three-person
team. The three-person model is recommended because framework/application,
test/security and delivery work can overlap, although the staged Laravel
migration remains partly sequential.

The estimate includes migration, dependency replacement, test construction,
security remediation, delivery automation, UAT and release documentation. It
excludes new product features, a visual redesign, content migration from an
unknown live installation and external infrastructure procurement. Detailed
work-package values and assumptions are recorded in `porting.md`.

### User plus Codex delivery model

When the user and Codex implement the modernization together, the planning
estimate falls to **110-175 human-equivalent engineering days**. Codex performs
the bulk of repository-wide edits, dependency migration, automation, tests and
documentation; the user supplies decisions, access, representative data,
visual checks and production acceptance. Expected active user involvement is
approximately **20-35 concentrated days** distributed over the project.

With regular collaboration and prompt access to test infrastructure, the
realistic elapsed duration is **4-6 months**. Part-time weekly collaboration is
more safely planned at **6-9 months**. Six months is the recommended target,
with four to nine months retained as the planning range.

## Staging installation record

On 2026-09-06 the staging host was prepared with Docker and Compose. The
versioned Nginx/PHP-FPM/MariaDB stack was built from the local checkout,
Contentify's command-line installer completed, 65 database tables and the
administrator were created, and the installation state was persisted.

The first PHP-FPM start exposed an invalid access-log duration modifier; it was
corrected and the incident retained in the central logs and `bugs.md`. The
public runtime tree was then changed to one persistent volume shared read/write
with PHP and read-only with Nginx, because Contentify writes more than uploads.

Final checks returned HTTP 200 for the homepage and login page, HTTP 302 for a
valid login submission, and HTTP 200 for the authenticated administrator area.
All four services remained running, MariaDB was healthy, three Contentify jobs
executed on the first scheduled pass, central JSON parsing passed, and no new
container error appeared in the final validation interval.

### Administrator login diagnosis - 2026-09-06 18:43 CEST

The first interactive browser login could not succeed because Sentinel had
blocked the client IP after five failed attempts. Database inspection showed
the administrator is present, activated, unbanned and assigned to the
`super-admins` role. The file-session directory is owned by `www-data` and is
writable. The login controller accepts the email address only; the username is
not a valid substitute.

A non-secret round-trip comparison then identified the installation defect:
Docker Compose changed the requested password's two trailing dollar signs into
one before passing it to the Contentify installer. The stored hash validates
the altered 11-character value and rejects the intended 12-character value.
The actual secret is deliberately not recorded in repository documentation.
The administrator hash was then reset to the exact requested value. Exactly
five user and six client-IP throttle entries were removed; unrelated global
records were retained. A real browser login succeeded, and the authenticated
admin dashboard opened with the expected `super-admins` access.

That dashboard exposed a separate fault: many left-navigation links use the
internal absolute host `http://nginx`, while the dashboard's quick-access links
use the correct external host. This was recorded as BUG-010 and left unchanged
for the original-baseline fault-analysis phase.

### Missing admin icons - 2026-09-06 18:45 CEST

Browser inspection and direct HTTP probes confirmed that Font Awesome markup
is generated correctly but its stylesheet and webfonts are unavailable. The
repository contains these files under `public/vendor/font-awesome`; the
staging Nginx volume does not. The unanchored `.dockerignore` rule `vendor`
removes nested `public/vendor` from the Docker build context. Requests for both
the stylesheet and `fa-solid-900.woff2` return Contentify's HTML crash page with
HTTP 500, while `css/backend.css` returns HTTP 200.

The impact extends beyond icons because jQuery, CKEditor and other client assets
are stored in the same excluded directory. The compiled backend CSS also points
to a Glyphicons font directory that is absent from the upstream checkout. No
matching current upstream admin-icon issue exists; GitHub issue #614 concerns a
Valorant game icon already addressed in the current branch.

STAGE-004 was resolved in local version `0.2.3`. Docker ignore rules now
explicitly re-include `public/vendor`; all 272 tracked files were restored to
the staging source and existing public-data volume. The absent Glyphicons were
copied unchanged from the official Bootstrap 3.3.7 npm tarball (package SHA-1
`5a389394549f23330875a3b150656574f8a9eb71`) into `public/css/fonts`.

Both app and Nginx images were rebuilt and inspected before recreation. After
the complete stack restarted with the real Compose environment file, MariaDB
was healthy and all four services were running. Homepage, Font Awesome CSS and
WOFF2, jQuery, CKEditor, backend CSS and all five Glyphicons formats returned
HTTP 200 with suitable MIME types. A real authenticated browser reload showed
the Font Awesome glyphs in the header, navigation, quick access and dashboard.
The separate cached `http://nginx/...` navigation-link defect remains open.

### Cached admin navigation URL repair - 2026-09-06 19:03 CEST

The left menu was rendered once through Laravel's `url()` helper and the full
HTML was cached forever per locale. If an internal health check or service
request created that cache, Docker's `nginx` hostname became part of every
administrator's links even when they connected through the staging IP.

Version `0.2.4` keeps the existing per-locale HTML cache but stores a neutral
base-URL placeholder in every navigation link. `BackendNavGenerator::get()`
replaces it with `url('/')` for the active client request. This is deliberately
narrower than globally forcing `APP_URL`, and it preserves Contentify installs
below a URL path. Deployment refreshes only the affected navigation cache.

The `0.2.4` image passed PHP 7.4 syntax validation. A controlled test first
forced `http://nginx` while building the menu, verified that only the neutral
placeholder was cached, and then obtained IP-based links from the same cache.
An authenticated browser opened News, Pages and Configuration from the left
menu through the external staging address; no `nginx` link remained. All services stayed up,
MariaDB remained healthy, and no new error-level container log entry appeared.
One follow-up Tinker inspection command contained an accidentally doubled PHP
namespace separator and produced a console parse error. It did not execute
application code or change state; the corrected cache inspection immediately
passed and is the result reported above.

## Öffentlicher Community-Fork - 2026-09-06 19:20 CEST

Der öffentliche Fork liegt unter `Bad-Hippo-com/Contentify`. Die eigene
Entwicklung verwendet `main`; der unveränderte Upstream-Stand bleibt über den
Remote `upstream` und dessen Branch `3.2-dev` nachvollziehbar. Beschreibung und
Projektstatus sind deutsch. Der Fork bezeichnet sich ausdrücklich als
Community-Projekt und nicht als offizielle Übernahme von Chris Konnertz.

Container sollen später über GitHub Container Registry unter dem Namensraum
`ghcr.io/bad-hippo-com` veröffentlicht werden. Bis ein unabhängiger Testserver
die saubere Installation bestätigt hat, wird das PHP-7.4-Baseline-Image nicht
als stabile oder produktionsreife Version angeboten. Geplant sind eindeutige
Versions-Tags und unveränderliche Image-Digests; ein bewegliches `latest` gibt
es erst nach der ersten freigegebenen Version.

Der bisherige Dashboard-Feed von `contentify.org` bleibt zunächst sichtbar und
wird als Original-Contentify-Feed gekennzeichnet. Daneben kommt ein eigener
Bad-Hippo-Projektfeed für Wartungsstatus, Releases und Sicherheitsmeldungen.
Beide Quellen müssen per HTTPS, mit getrennten Zeitlimits und getrennten Caches
geladen werden. Externe Texte, URLs und Icons werden validiert und escaped. Ein
Ausfall eines Feeds darf weder Dashboard noch zweiten Feed blockieren.

Zur öffentlichen Arbeitsorganisation wurden vier deutschsprachige Issues im
Bad-Hippo-Fork angelegt: vollständige Upstream-Triage (`#1`), sicherer zweiter
Dashboard-Feed (`#2`), GHCR-Container (`#3`) und der spätere PHP-8-Blocker
(`#4`). Issue-, Pull-Request- und Security-Hinweise sind ebenfalls auf Deutsch
umgestellt. Befunde werden im ursprünglichen Repository erst nach Quellprüfung
und, bei eigenen Änderungen, erst nach erfolgreichem Staging-Test kommentiert.

Interne IP-Adressen und echte Zugangsdaten werden nicht im öffentlichen Fork
geführt. Beispiele verwenden ausschließlich reservierte Testdaten; konkrete
Betriebsziele bleiben in der privaten Betriebsumgebung.

Der erste Upstream-Triageblock umfasst die Original-Issues `#645`, `#663`,
`#658`, `#613` und `#614`. Die beiden PHP-8-Berichte wurden als derselbe noch
offene `Match`-Blocker bestätigt. Der Docker-Bericht verweist auf den neuen,
noch nicht produktionsreifen Nginx-Stagingweg. OpenGraph-Newsfehler und
Valorant-Icon wurden anhand der ursprünglichen Chris-Commits als bereits in
`3.2-dev` behoben eingeordnet. Die Befunde wurden in den jeweiligen
Original-Issues veröffentlicht und in unserem Issue `#1` zusammengefasst.

### Original-Issue #650: Team-Logo und Banner - 2026-09-06 19:37 CEST

Der Fehler ist unabhängig von Dateirechten und Upload-Verzeichnissen im
gemeinsamen `Contentify\Uploader` reproduzierbar. `uploadModelFiles()` enthielt
sein erfolgreiches `return []` innerhalb der Schleife über `$fileHandling`.
Bei `Team::$fileHandling` steht `image` vor `banner`; damit endete jeder Aufruf
nach der Logo-Prüfung. War ein Logo vorhanden, wurde nur dieses gespeichert.
War es leer, wurde trotzdem zurückgekehrt und ein vorhandener Banner ignoriert.
Da dies ein regulärer erfolgreicher Rückgabepfad war, entstand erwartungsgemäß
kein Laravel-, PHP- oder Nginx-Fehlerlog.

Bad Hippo `0.2.5` verschiebt die Rückgabe hinter die Schleife. Ein PHPUnit-Test
deckt beide gemeldeten Varianten ab. Weil das Produktionsabbild bewusst ohne
Entwicklungsabhängigkeiten gebaut wird, enthält das Repository zusätzlich einen
direkt ausführbaren Smoke-Test. Dieser lief im echten Staging-App-Container mit
PHP 7.4.33 und Laravel 6.20.30 erfolgreich für Logo plus Banner sowie Banner
ohne Logo. Die Änderung hebt weder PHP noch Laravel an und bleibt damit Teil der
Stabilisierung des Originalumfangs.

Für den eigentlichen PHPUnit-Lauf wurden die im Produktionsabbild bewusst
fehlenden Entwicklungsabhängigkeiten nur in einem kurzlebigen Container
installiert. PHPUnit 9.5.8 meldete `OK (2 tests, 11 assertions)`; der Container
wurde danach verworfen. Das laufende App-Abbild bleibt damit `--no-dev`.

Beim anschließenden Containerwechsel blieben App und Jobs gesund, aber der
bereits laufende Nginx-Prozess hielt die nicht mehr gültige IP des ersetzten
App-Containers und antwortete vorübergehend mit HTTP 502. Eine gezielte
Neuerstellung des Nginx-Containers stellte Homepage und Anmeldeseite mit HTTP
200 wieder her. STAGE-005 dokumentiert den Vorgang; der Staging-Runbook verlangt
nun bei jedem App-Austausch auch die Nginx-Neuerstellung.

### Original-Issue #624: disk_free_space - 2026-09-06 19:55 CEST

Die Speicherplatzwarnung im Dashboard war als optionale Admininformation
gedacht, konnte aber selbst das Dashboard abbrechen. `function_exists()` prüft
nur die grundsätzliche Verfügbarkeit der PHP-Funktion. Der anschließende
zweifache, ungefangene Aufruf von `disk_free_space('.')` berücksichtigte weder
Hostingbeschränkungen noch Warnungen, Exceptions oder `false`. Die Diagnose
enthielt denselben ungeschützten Aufruf.

Bad Hippo `0.2.6` führt `Contentify\DiskSpace` als gemeinsamen, kleinen Adapter
ein. Er fragt einen expliziten Basispfad einmal ab, unterdrückt die native
Warnung, fängt `Throwable`, validiert den Rückgabewert und liefert bei nicht
verfügbaren Daten `null`. Das Dashboard warnt weiterhin bei einem validen Wert
unter 100 MB; die Diagnose zeigt andernfalls `?`.

Der Smoke-Test lief im realen Staging-Container erfolgreich mit einem lesbaren
und einem absichtlich fehlenden Pfad. Die PHPUnit-Fälle werden zusätzlich im
kurzlebigen Testcontainer mit Entwicklungsabhängigkeiten ausgeführt. Es wurden
keine PHP-, Laravel- oder Paketversionen angehoben.

Der kombinierte PHPUnit-Lauf für Upload und Speicherplatz meldete mit PHP 7.4
`OK (4 tests, 15 assertions)`. Anschließend wurde das versionierte Abbild
`contentify-staging-app:0.2.6` gemeinsam für App und Jobs ausgerollt und Nginx
im selben Vorgang neu erstellt. Beide Smoke-Tests liefen im neuen App-Container
grün; Startseite und Anmeldeseite antworteten mit HTTP 200, alle vier Dienste
liefen und MariaDB blieb gesund.

### Laravel-6-Patchstufe 0.3.0 - 2026-09-06 20:17 CEST

Die erste Modernisierungsstufe ändert weder PHP-Major noch Laravel-Major. In
einem wegwerfbaren Container auf Basis des realen Staging-Abbilds wurde gezielt
`laravel/framework` mit allen zulässigen Abhängigkeiten aktualisiert. Composer
wählte Laravel 6.20.45, 45 Paketupdates und vier zusätzliche Hilfspakete; die
erzeugte Lockdatei wurde erst nach den Prüfungen in den Arbeitsstand übernommen.

Der Kandidat bestand `composer validate --strict`, meldete über Artisan Laravel
6.20.45, bestand fünf Unit-Tests mit 16 Assertions, beide Regression-Smoke-Tests
und den Syntaxlauf über alle eigenen PHP-Dateien. Composer 2.10.3 meldet noch
39 Advisories in acht Paketen statt zuvor 47. Das Produktionsabbild
`contentify-staging-app:0.3.0` wurde danach für App und Jobs ausgerollt; Nginx
wurde im selben Vorgang neu erstellt. Laravel meldet live 6.20.45, MariaDB ist
gesund und beide Smoke-Tests bleiben grün. Startseite, Anmeldung, Font Awesome,
Glyphicons und der von der Seite eingebundene jQuery-Pfad antworten mit HTTP
200 und passenden MIME-Typen. Die zentralen Logs zeigen keine neuen Fehler.

Damit ist diese Stufe auf Staging angenommen, aber weiterhin weder test- noch
public-freigegeben.
Die noch offenen Findings werden nicht verschwiegen oder per
`--ignore-platform-reqs` umgangen, sondern in den folgenden isolierten
Framework- und Laufzeitstufen bearbeitet.

### Bad-Hippo-Newsfeed und CMS-Version 3.3-dev - 2026-09-06 20:28 CEST

Bad Hippo führt die gepflegte Entwicklungslinie ab Version `0.4.0` sichtbar als
Contentify `3.3-dev`. Diese Kennung ist von der kleinschrittigen internen
Releaseversion getrennt und ersetzt nicht rückwirkend Chris' Upstream-Version
3.2-dev.

Das Admin-Dashboard lädt nun zwei ausdrücklich bezeichnete Quellen. Der
weiterhin unveränderte Originalfeed verweist auf `Contentify/Contentify`; der
zusätzliche Feed wird aus `public/share/feeds/cms.json` auf dem `main`-Branch
des Repositories `Bad-Hippo-com/Contentify` geladen. Überschrift, Projektquelle
und jede eigene Meldung führen auf unser GitHub. Beide Quellen besitzen eigene
Cache- und Fehlerzustände.

Weil Feed-Inhalte außerhalb der Installation liegen, wurde die bestehende rohe
HTML-Ausgabe nicht auf die zweite Quelle übertragen. `Contentify\DashboardFeed`
verwirft ungültige JSON-Wurzeln und unvollständige Einträge, erlaubt nur
HTTP(S)-Links und sichere Iconnamen und stellt einen GitHub-Fallback bereit. Die
Blade-Ansicht escaped Texte und Attribute und setzt bei externen Tabs
`noopener noreferrer`.

Drei neue Unit-Tests prüfen gültige Meldungen, unsichere URL-/Iconfelder,
unvollständige Einträge und ungültige JSON-Wurzeln. Der vollständige Unit-Lauf
im PHP-7.4-Kandidaten bestand acht Tests mit 27 Assertions. Außerdem wurde der
veraltete Staging-Wert `CONTENTIFY_BUILD_VERSION=0.2.4` auf `0.4.0` korrigiert,
damit neu erzeugte zentrale Logs die tatsächlich laufende Buildversion tragen.

Die reale Browserprüfung von `0.4.0` zeigte beide Quellen und alle drei eigenen
GitHub-Meldungen korrekt, aber den Originalfeed oberhalb der neueren Quelle.
Version `0.4.1` korrigiert ausschließlich diese Reihenfolge: Bad Hippo steht
oben, Contentify Original bleibt direkt darunter vollständig erhalten.

### Laravel-7-Migrationsstufe 0.5.0 - 2026-09-06 20:51 CEST

Die zweite Framework-Stufe wurde erneut in einem wegwerfbaren Container auf
Basis des tatsächlich laufenden PHP-7.4-Staging-Abbilds aufgelöst. Composer
identifizierte `cartalyst/sentinel` 3.0.4 als direkten Laravel-6-Blocker. Der
Kandidat verwendet Laravel 7.30.7, Sentinel 4.0.0, Ignition 2.17.7 und
Collision 4.3.0. Insgesamt wurden 56 Pakete aktualisiert, sieben hinzugefügt
und 14 aus dem Lockbestand entfernt.

Der erste Package-Discovery-Lauf zeigte einen echten Quellcodebruch im eigenen
Exception-Handler: Laravel 7 verlangt dort `Throwable` statt `Exception`.
Zusätzlich übernimmt `config/session.php` den neuen neutralen Cookie-Secure-
Standard und die drei eigenen Artisan-Befehle liefern explizit den Integercode
0. Nach diesen begrenzten Änderungen lief Package Discovery vollständig durch.

Der isolierte Kandidat meldet Laravel 7.30.7, listet 540 Routen, besteht acht
Unit-Tests mit 27 Assertions, beide Regression-Smoke-Tests und den Syntaxlauf
über alle eigenen PHP-Dateien. Der vollständige PHPUnit-Lauf enthält neun Tests
mit 28 Assertions; ausschließlich der bereits unter Laravel 6 bekannte
Platzhalter `ExampleTest::testBasicTest` scheitert, weil `/` in der nicht
installierten Testumgebung HTTP 404 statt der fest codierten 200 liefert.
`composer audit --locked --no-dev` sinkt von 39 Advisories in acht Paketen auf
12 Advisories in Laravel Framework und League CommonMark. Vier produktive
Pakete bleiben als aufgegeben markiert. Deshalb ist auch `0.5.0` nur eine
interne, prüfbare Zwischenstufe und noch keine Public-Freigabe.

Das Produktionsabbild `contentify-staging-app:0.5.0` wurde anschließend
gemeinsam für App und Jobs ausgerollt; Nginx wurde wegen seiner aufgelösten
Containeradresse im selben Vorgang neu erstellt. MariaDB blieb gesund. Live
melden Konfiguration und Footer `3.3-dev / 0.5.0`, Laravel 7.30.7 sowie 512 in
der installierten Modulauswahl aktive Routen. Das bestehende Admin-Sitzungscookie
blieb unter Sentinel 4 gültig. Dashboard, echte Anmeldung `/auth/login`,
Font-Awesome-5-CSS und -WOFF2, Glyphicons, jQuery und Startseite antworten mit
HTTP 200; beide Smoke-Tests bleiben grün. Die Browserprüfung zeigt Icons und
sämtliche Menüzieladressen weiterhin korrekt unter der Staging-IP.

Ein absichtlich falsch verwendeter Altpfad deckte unabhängig davon BUG-017 auf:
Der originale produktive Exception-Handler macht aus unbekannten Routen HTTP
500 statt 404. Das betrifft weder die echte Anmeldung noch vorhandene Assets,
wurde aber als eigener offener Originalfehler erfasst.

## Files added or updated

- `README.md`: local assessment notice and documentation links
- `bugs.md`: confirmed blockers and risks
- `todo.md`: completed audit work and required modernization backlog
- `porting.md`: seven-stage port plan and acceptance gates
- `PROJECT_DOCUMENTATION.md`: complete assessment record
- `config/logging.php` and `.env.example`: central Laravel channels and controls
- `app/Logging/JsonLogFormatter.php`: structured JSON Lines plus host/request context
- `deploy/logging`: PHP, webserver, worker, scheduler, rotation and verification templates
- `deploy/staging`: reproducible Nginx, PHP-FPM, MariaDB and Contentify job stack
- `tests/Unit/UploaderTest.php`: PHPUnit regression for multiple upload fields
- `tests/Smoke/UploaderMultipleFiles.php`: dependency-free staging smoke check
- `contentify/DiskSpace.php`: safe shared disk-space adapter
- `tests/Unit/DiskSpaceTest.php` and `tests/Smoke/DiskSpace.php`: restricted-host regression checks

Die Stabilisierung wird auf `main` des Bad-Hippo-Forks veröffentlicht. Es wurde
kein Pull Request gegen das Original erstellt und kein Branch des ursprünglichen
Repositories verändert.

# Contentify project assessment

Stand 2026-09-08 09:35 CEST: **0.19.1 in Kandidatenprüfung, noch nicht auf Staging.**
Erster Lauf 0.19.0: 29 Tests/137 Assertions, beide Smoke-Tests und LESS-Neubau
bestanden. Composer- und npm-Audit ohne bekannte gemeldete Schwachstellen.
Nachprüfung erweitert CSRF-Schutz auf Forum-, Freunde- und Verwaltungsaktionen;
zusätzliche Negativtests für Kommentare und CSRF. Vollständiger Audit bleibt offen.
Funktionsreparaturen: Cup-Siegerwechsel (BUG-020), Kommentar-Kontext (BUG-008),
HTTP-Status (BUG-017), Nachrichten-Einstiegsroute und Eingabevalidierung.
Sicherheitsprüfung: Cup-GET-Mutationen auf Bestätigung/POST umgestellt; jQuery
3.7.1, Moment 2.30.1 mit Sprachpaketen; Editor-Standardfilter wieder aktiviert.
PHP- und Browser-Abnahme noch offen. Weitere Altmodule, Berechtigungen,
serverseitige HTML-Bereinigung und Container-Pakete müssen separat geprüft werden.
Keine Freigabe für Public; Staging bleibt bis zur Abnahme auf 0.18.3.

Vorheriger Stand 2026-09-08 08:56 CEST: **0.18.3 entfernt Tabellenrahmen in beiden Frontend-Themes.**
Äußere Rahmen, Zell- und Zeilenlinien entfallen auch mobil; Farben bleiben erhalten.
Node-Build, Rahmen-Regressionsprüfung und PHP-LESS-Neubau bestanden.
Auf Staging installiert und visuell geprüft: Zell- und Zeilenrahmen 0px.
Profil- und Nachrichtentabellen übernehmen dunkle Themefarben inklusive Schrift,
Rahmen, Streifen und Hover. Backend bleibt passend zu seinem hellen Inhaltsbereich.

Vorheriger Stand 2026-09-08 08:26 CEST: **0.18.1 mit Bootstrap 5.3.8 auf Staging abgenommen.**
23 Tests / 83 Assertions, beide Smoke-Skripte und PHP-LESS-Neubau bestanden.
35 Admin-Menüziele, sechs Tabs, Dialoge, Kalender und beide Themes geprüft.
Implementierung auf GitHub main; kein Public-Release. LESS-Referenzadapter,
Glyphicons und manuell vendorte Altplugins bleiben Folgearbeit.
BUG-043 (Kalendertexte/Editorwarnungen) und BUG-046 (Server-Seitentitel) sind offen.

Local workstream version: **0.18.3**
Assessment/update time: **2026-09-08 08:56 CEST**
Workspace: `E:\WorkSpace\contentify`

## Tabellenfarben – 0.18.2, 2026-09-08

Benutzer meldet weiße/grauweiße Profiltabellen und Nachrichtenflächen nach dem
Bootstrap-5-Wechsel. Browsermessung bestätigt die Bootstrap-Defaults #fff/#000.
Ein gemeinsamer LESS-Mixin setzt nun die nativen Tabellenvariablen; Morpheus
und Phobos verwenden #202020/weiß, Rahmen #434343, Streifen #292929 und Hover
#363636. Das Backend erhält seine eigene helle, blaugraue Palette. Kalender-
Tabellen behalten eine separate Farbdefinition und Nachrichtentabs dunkle Rahmen.
Beide Theme-Builds und Node-Farbverträge bestanden; im isolierten Kandidaten
PHP-LESS-Neubau und beide Smoke-Skripte erfolgreich. Profilzellen dort gemessen:
Hintergrund rgb(32,32,32), Text rgb(255,255,255), Rahmen rgb(67,67,67).
Profil und Nachrichteneingang visuell geprüft. Keine Nachrichten versendet.
Umsetzung f3095ef7 auf main; Protokolle unter den bestehenden zentralen
deployment-Verzeichnissen mit Präfix contentify-tables abgelegt.
Staging-Abnahme 08:50 CEST: alle vier Profiltabellen und Nachrichteneingang
liefern die neue Palette #202020/weiß; Screenshots bestätigen dunkle Flächen.
PHP-LESS-Neubau auf Staging erfolgreich, Version 0.18.2 installiert.

## Historische Bootstrap-5-Portierung – 2026-09-08 08:21 CEST

- Abnahme 08:26 CEST: finaler Kandidat 0.18.1 erneut mit 23 Tests / 83 Assertions
  und Audit bestanden. Umsetzung über `bad-hippo/bootstrap-5` nach `main`
  übernommen (`1e02bd64`) und auf Staging installiert. Smoke-Skripte, LESS-Neubau,
  Composer-Audit, sechs Asset-HTTP-200-Prüfungen sowie Editor/Modal/Tabs/Feed
  auf Staging erneut bestanden. Keine neuen Anwendungs- oder JS-Fehler beobachtet.
  Nginx protokolliert beim großen Konfigurationsformular lediglich einen
  FastCGI-Buffering-Hinweis (temporäre Datei), kein fehlgeschlagener Request.
- `0.18.0` ersetzt die Laufzeit durch Bootstrap 5.3.8 und das Popper-2-Bundle.
  data-bs-Attribute, native Modal/Tooltip/Collapse-APIs und Laravel-Pagination
  sind migriert. `0.18.1` ergänzt Dispose beim Kalender und erhält die Linkoptik.
- Referenz-LESS für alte Theme-Mixins und das eigenständige Glyphicons-Stylesheet
  bleiben erhalten; Bootstrap 3/4 werden nicht als zweite JS-Laufzeit geladen.
  Dies ist keine Behauptung einer vollständigen Sass- oder jQuery-Ablösung.
- Kandidat auf Port 8088 mit separaten DB-, Storage- und Public-Volumes:
  23 Tests / 83 Assertions, beide Smoke-Skripte und echter PHP-LESS-Neubau bestanden.
  Alle 35 Admin-Menüziele ohne Error-Seite, sechs Konfigurationstabs, beide
  Editoren, Bildermodal samt Entfernung von Dialog und Backdrop, Kalenderwechsel
  Datum/Uhrzeit und Phobos-Benutzerdropdown geprüft. Beide Themes bei 1440 und
  390 Pixeln geprüft. Theme-Wechsel erfolgt nur im isolierten Kandidaten.
- Aufbaubefund: Composer-Dev-Pakete ließen sich zunächst nicht in das root-
  eigene Vendorverzeichnis schreiben. Nur im Prüfcontainer wurde dessen
  Eigentümer korrigiert; keine Dev-Abhängigkeiten auf Staging installiert.
- Protokolle: `/var/log/contentify-bootstrap-candidate/deployment/` für
  Kandidat und Fehlversuche, `/var/log/contentify/deployment/` für Staging.
  Lokaler erster Asset-Test erforderte wegen des offiziellen CSS-Banners mit
  doppeltem Leerzeichen einen toleranteren Versionsvergleich.
- BUG-043 bleibt offen; BUG-046 dokumentiert den nebenbei gefundenen falschen
  Server-Seitentitel. Node-Audit prüft nicht manuell vendorte Altbibliotheken.

## Historische Bootstrap-4-Abnahme – 2026-09-08 07:37 CEST

- Umsetzung `3045bad5` über `bad-hippo/bootstrap-4` nach `main` übernommen und
  auf Staging `192.168.178.213` als **0.17.2** installiert. PHP 8.5.10 und
  Laravel 13.30.1 bleiben unverändert; kein Public-Release und keine Testserver-Freigabe.
- Bootstrap 4.6.2 und das Popper-Bundle werden lokal ausgeliefert. Raster,
  Tabs, Formulare, Pagination, Modals und Datumsauswähler wurden angepasst.
  Alte Theme-Mixins bleiben als LESS-Referenzadapter erhalten; dies ist noch
  keine vollständige Sass-Portierung. Glyphicons haben ein separates Stylesheet.
- Reale Theme-Wechsel deckten drei zusätzliche Fehler auf: der alte PHP-LESS-
  Compiler scheiterte unter PHP 8.5, Public-Dateien gehörten root und dynamisch
  kompilierte Icon-Pfade waren falsch. Wikimedia/less.php 5.5.1, atomarer
  Neubau, korrekte Container-Dateirechte und getrennte Glyphicons beheben sie.
- Isolierter Kandidat: **23 Tests / 83 Assertions**, beide Smoke-Skripte,
  tatsächlicher PHP-LESS-Neubau, 35 Admin-Menüziele, sechs Konfigurationstabs,
  beide Editoren, Bildermodal, Kalenderumschaltung sowie beide Themes geprüft.
  Desktop und 390-Pixel-Ansicht geprüft; Phobos-Überlappungen korrigiert.
- Staging: Smoke-Skripte und LESS-Neubau erneut bestanden, Assets mit HTTP 200,
  Browserprüfung von Editor, Modal, Tabs, Homepage und neuem Feed erfolgreich.
  Keine JavaScript-Fehler; optionale Editorwarnungen bleiben unter BUG-043 offen.
- Build- und Prüfprotokolle liegen zentral unter
  `/var/log/contentify/deployment/` sowie für Kandidaten unter
  `/var/log/contentify-bootstrap-candidate/deployment/`.
  Der separate Kandidat wird nach Abnahme ohne Löschen seiner Volumes beendet.
- Composer-Audit ohne bekannte Advisories; npm-Audit ohne Treffer im deklarierten
  Paketbaum. Manuell eingebundene Altbibliotheken sind davon nicht abgedeckt.
  Bootstrap 4 bleibt eine EOL-Zwischenstufe vor Bootstrap 5.

## Purpose

This document records what was downloaded, what was tested, the evidence found,
and whether the upstream Contentify CMS can be implemented on a current stack.

## Source state

- Community repository: `https://github.com/Bad-Hippo-com/Contentify.git`
- Upstream repository: `https://github.com/Contentify/Contentify.git`
- Local branch: `main`, intended to track `origin/main`
- Upstream baseline commit: `5bd21fb7879cf0fbede159a6dc71d0554c8d2bde`
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
- Staging containers: Nginx 1.26.3, PHP-FPM 8.5.10 and MariaDB 10.11

Isolierte Kandidaten und versionierte Lockbestände werden vor jedem
Staging-Rollout geprüft; temporäre Prüfcontainer werden danach entfernt.

## Verification results

| Check | Result |
| --- | --- |
| PHP 8.5 / Laravel 13 lint | Pass; `0.13.0` parses 802 project and local-package files |
| PHP 8.5 / Laravel 13 Artisan | Pass; Laravel 13.30.1 and 512 active production routes |
| PHP 8.5 / Laravel 13 PHPUnit | Pass; 0.18.1 has 23 tests with 83 assertions including both theme compilers |
| Git checkout | Pass; official default branch cloned cleanly |
| PHP 8.0 lint | Pass; `0.7.0` parses 688 selected first-party and test files |
| PHP 8.0 Artisan | Pass; Laravel 8.83.29 and 512 active routes |
| PHP 8.0 PHPUnit | Pass; 12 unit tests with 42 assertions |
| PHP 7.4 lint | Pass; latest `0.6.0` candidate parses 686 selected first-party and test files |
| PHP 7.4 Artisan | Pass; `0.6.0` candidate reports Laravel 8.83.29 |
| PHP 7.4 PHPUnit | Fail; unit placeholder passes, feature placeholder gets 404 |
| Composer validation | Pass for `0.6.0`; regenerated lock is installable on PHP 7.4 |
| Composer normal install on PHP 8.0 | Pass for the pinned `0.7.0` image without ignored requirements |
| Composer platform check | Pass on PHP 8.0.30 with all required extensions |
| Composer production audit | Pass in 0.18.1; no known advisories or abandoned production package |
| npm clean install | Pass on Node 24/npm 11 without legacy resolution |
| npm audit | 0 known advisories in the declared npm tree with Bootstrap 5.3.8 and Popper 2; manually vendored legacy libraries remain outside this audit |
| SunEditor production audit | Pass; exact 3.3.2 dependency, no known production vulnerability |
| LESS build/watch | Pass with exact Less 4.9.1; deterministic CSS hash |
| Docker/Compose review | Fail for current production readiness |

## Currentness assessment

Contentify is obsolete as delivered:

- Laravel 10 no longer receives official bug or security fixes. Current Laravel
  is 13, whose supported PHP range includes PHP 8.5.
- Der Bad-Hippo-Stand läuft inzwischen auf PHP 8.5.10; die reservierten
  `Match`-Klassennamen wurden in 0.7.0 beseitigt.
- Der Bad-Hippo-Produktions-Lockbestand auf Laravel 13 meldet keine bekannte
  Sicherheitslücke mehr. Composer markiert nur noch Less.php als aufgegeben;
  Public bleibt bis zur unabhängigen Testinstallation gesperrt.
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

PHP 8.5/Laravel 13 wurde nicht als ungetesteter Sprung, sondern über einzeln
geprüfte Stufen erreicht. Der Stand muss weiterhin unterstützt, sicher,
reproduzierbar und advisory-frei bleiben. Dadurch ist die Ursache jeder
Regression einer einzelnen Migrationsachse zuzuordnen.

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

Die anschließende Prüfung der leeren Admin-Seite `/admin/config/log` bestätigte
keinen Logging-Ausfall. Der Controller sucht weiterhin ausschließlich nach
`storage/logs/laravel.log`, während die zentrale Konfiguration tägliche JSON-
Dateien unter `/var/log/contentify/application-YYYY-MM-DD.log` erzeugt. Auf
Staging war die Legacy-Datei nicht vorhanden; gleichzeitig enthielten
Anwendungs-, PHP-, Nginx- und Job-Logdateien Daten. Diese auseinanderlaufenden
Quellen sind als BUG-018 erfasst. Eine spätere UI-Anbindung muss Ausgabegröße,
Escaping, Zugriffsrecht und die gefährliche alte Löschfunktion berücksichtigen.

Version `0.5.1` löst BUG-018 durch bewusstes Splitting statt durch direkten
Zugriff der Weboberfläche auf die Betriebslogs. Der Standardkanal `stack`
verteilt normale Laravel-Einträge gleichzeitig an `application` und `legacy`.
`application` bleibt das ausführliche tägliche JSON-Protokoll unter dem
zentralen Logroot. `legacy` nutzt Laravels klassisches Single-File-Format genau
unter `storage/logs/laravel.log`, das der vorhandene Controller bereits sicher
escaped darstellt. Die Admin-Löschtaste kann damit ausschließlich diese
Anzeige-Kopie löschen; die zentralen Anwendungs-, Security-, Job-, PHP- und
Nginx-Dateien bleiben erhalten.

Der finale Kandidat bestand neun Unit-Tests mit 33 Assertions sowie den
vollständigen First-Party-Syntaxlauf. Das gemeinsam neu erstellte Staging-Set
aus App, Jobs und Nginx läuft mit Image `contentify-staging-app:0.5.1`; MariaDB
blieb gesund und Laravel meldet weiterhin 7.30.7. Ein als `www-data`
geschriebener Prüfdatensatz erschien gleichzeitig als ausführliches JSON mit
Umgebung, Container, Build `0.5.1` und Requestkontext sowie als klassischer
84-Byte-Eintrag im Admin-Logviewer. Die Anzeige-Datei besitzt Modus `0640` und
`www-data:www-data`. Startseite und Admin-Route antworteten mit HTTP 200.

Version `0.6.0` hebt als nächste getrennte Framework-Stufe Laravel von 7.30.7
auf 8.83.29 an; PHP bleibt unverändert auf 7.4. Composer ermittelte Sentinel
4 als ersten Blocker, weil diese Generation Illuminate Support 7 verlangt.
Die kleinste passende Major-Stufe ist Sentinel 5; der finale Lockbestand nutzt
Sentinel 5.1.0, Cartalyst Support 5.1.2 und Collision 5.11.0. Insgesamt wurden
sechs Pakete neu aufgenommen und sieben aktualisiert.

Der erste echte HTTP-Aufruf deckte BUG-019 auf: Contentifys eigener Übersetzer
rief die in Laravel 8 entfernte geschützte Methode `sortReplacements()` auf.
Die bisherige längste-Platzhalter-zuerst-Sortierung liegt nun im eigenen
Übersetzer und ist durch einen Regressionstest gesichert. Zusätzlich verwendet
der Wartungsmodus Laravels neue `PreventRequestsDuringMaintenance`-Middleware.
Die PHP-Anforderung ist für diese Stufe ehrlich auf `^7.3` begrenzt; PHP 8
bleibt bis zur Beseitigung der reservierten `Match`-Klassennamen gesperrt.

Der finale Kandidat meldete Laravel 8.83.29, bestand zehn Unit-Tests mit 34
Assertions, beide Smoke-Tests, Syntaxprüfungen für 686 Dateien, 512 aktivierte
Routen und den lesenden Datenbanktest mit vier erkannten Migrationen. Der
Produktions-Audit sank von 12 Findings in zwei Paketen auf drei Findings in
Laravel Framework; vier aufgegebene Produktionspakete bleiben erfasst. Das
Staging-Image `contentify-staging-app:0.6.0` läuft gemeinsam für App und Jobs.
Nginx wurde mit neu erstellt, MariaDB blieb gesund. Startseite, Anmeldung,
bestehende Admin-Sitzung, Dashboard, Newsverwaltung, Logviewer, Icons,
IP-Navigation und Jobrunner wurden erfolgreich geprüft.

Version `0.7.0` trennt den anschließenden PHP-Sprung vollständig vom nächsten
Laravel-Major. Die zwei unter PHP 8 reservierten Modellnamen wurden zu
`GameMatch` und `CupMatch`; alle Relationen und Controller wurden angepasst,
die Tabellen `matches` und `cups_matches` sowie alle URLs bleiben bestehen.
Der Umbau bestand zunächst unter PHP 7.4, anschließend baute Composer das
unveränderte Laravel 8.83.29 auf dem gepinnten PHP-8.0.30-FPM-Abbild ohne
`--ignore-platform-reqs`. Auf PHP 8 bestanden 688 Syntaxprüfungen, zwölf
Unit-Tests mit 42 Assertions, beide Smoke-Tests, 512 Routen, vier erkannte
Migrationen und Abfragen über beide neuen Modellklassen.

App, Jobrunner und Nginx wurden gemeinsam auf Staging ersetzt; MariaDB blieb
gesund und das 0.6.0-Abbild wurde als Rückfallstufe behalten. Startseite,
Anmeldung, Font Awesome und Glyphicons antworten mit HTTP 200. Die bestehende
authentifizierte Sitzung öffnet Dashboard, Matches, Cups und den Admin-Logviewer.
Der 0.7.0-Prüfeintrag liegt sowohl als zentrales JSON mit Buildkontext als auch
im klassischen `laravel.log`. PHP 8.0 und Laravel 8 sind weiterhin abgekündigt;
die Stufe ist eine Migrationsbrücke und keine Public-Freigabe.

### Request-IP-Stabilisierung 0.7.1 - 2026-09-07 06:44 CEST

Der isolierte PHP-Testserver erzeugte SQLSTATE 22007 in der Besucherstatistik,
weil die Anwendung `false` aus `getenv('REMOTE_ADDR')` als Zahlenwert `0` band.
Der echte Nginx/FPM-Pfad war nicht betroffen. Middleware sowie Kontakt- und
Bewerbungsformular beziehen die IP nun unabhängig von der Server-API aus dem
Laravel-Request. Der Fehler und seine Abgrenzung zum PHP-8-Port sind als
BUG-021 im zentralen Register erfasst. Ein zusätzlicher Aufruf der nicht
existierenden Route `/login` bestätigte BUG-017; die echte Route
`/auth/login` bestand den wiederholten anonymen HTTP-Test.

### PHP-8.5-Meilenstein 0.8.0 - 2026-09-07 07:35 CEST

Die PHP-Achse wurde ohne Zwischenfreigabe direkt von PHP 8.0.30 auf PHP 8.5.10
angehoben. Laravel blieb absichtlich auf 8.83.29, damit Framework- und Runtime-
Änderungen getrennt messbar bleiben. Das reproduzierbare Abbild verwendet die
gepinnten offiziellen Basen PHP 8.5 FPM Bookworm und Composer 2.10.3. Ein erster
Buildfehler zeigte, dass OPcache im PHP-Abbild schon enthalten ist; die doppelte
Kompilierung wurde entfernt. Composer identifizierte anschließend Nette Schema
1.2.5 und Nette Utils 3.2.10 als PHP-8.5-Blocker. Der gezielte Lock-Update auf
1.3.6 und 4.1.5 löste die Installation regulär und ohne ignorierte Plattform-
anforderungen.

Contentifys eigene implizit-nullbare Signaturen wurden explizit typisiert. Die
SimpleXML-Überschreibungen entsprechen den aktuellen internen Signaturen und
die MySQL-SSL-Option nutzt die PHP-8.5-PDO-Konstante. Der abschließende Lauf
prüfte 735 eigene PHP-Dateien ohne Syntaxfehler und ohne First-Party-
Deprecations. PHPUnit 9.6.36 bestand zwölf Tests mit 42 Assertions. Composer
bestätigte alle Produktionsanforderungen einschließlich GD, cURL, PDO und
SimpleXML. Die vorhandenen Migrationen und Modellabfragen funktionierten gegen
die unveränderte Staging-Datenbank.

App, Jobrunner und Nginx wurden gemeinsam auf `contentify-staging-app:0.8.0`
umgestellt; MariaDB blieb gesund. PHP meldet live 8.5.10, Artisan Laravel
8.83.29. Startseite, `/auth/login` und `/admin` antworten über den echten
Nginx/FPM-Pfad mit HTTP 200. Laravel 8 schreibt unter PHP 8.5 weiterhin eigene
Deprecation-Hinweise in die vorbereiteten Logs. Sie werden nicht unterdrückt
und bilden zusammen mit drei verbleibenden Laravel-Advisories die klare
Eingangslage für den folgenden Framework-Sprung. Public bleibt gesperrt.

### Laravel-9-Migrationsstufe 0.9.0 - 2026-09-07 08:30 CEST

Laravel wurde als eigene Achse von 8.83.29 auf die letzte stabile Version
9.52.21 gehoben; PHP blieb exakt 8.5.10. Die offiziellen Laravel-9-Hinweise
führten zu Collision 6.4.0, Spatie Laravel Ignition, Symfony Mailer, Flysystem 3
und der Framework-eigenen Proxy-Middleware. Sentinel 6.0.1 ist die erste
Ausgabe mit Illuminate-9-Vertrag. Mail- und Dateisystemkonfiguration folgen dem
Laravel-9-Aufbau, behalten aber Rückfallwerte für die vorhandenen
`MAIL_DRIVER`- und `FILESYSTEM_DRIVER`-Umgebungen bei.

Der nächste Composer-Blocker war `caffeinated/modules` 6.3.1. Die letzte
Veröffentlichung von 2021 erlaubt Illuminate nur bis 8, obwohl Contentifys 44
Module zentral von ihrer Repository- und Bootstrap-API abhängen. Ein Austausch
des Modulsystems hätte die isolierte Framework-Stufe aufgehoben. Der exakt
verwendete MIT-Stand wurde deshalb in `packages/caffeinated-modules` als lokale
Version 6.3.2 übernommen. Der Paketquellcode ist unverändert; nur sein PHP- und
Illuminate-Vertrag wurde auf die gemessene Umgebung erweitert. Eine eigene
Herkunftsdatei nennt Originalversion und Commit.

Der isolierte Produktionskandidat installierte ohne ignorierte Plattform-
anforderungen und meldete PHP 8.5.10 sowie Laravel 9.52.21. Er bestand 781
Syntaxprüfungen ohne Fehler, zwölf Unit-Tests mit 42 Assertions, beide
Smoke-Tests, 515 registrierte Routen, vier vorhandene Migrationen und lesende
Abfragen über beide Match-Modelle. Die bestehende Admin-Sitzung öffnete
Dashboard, Matches, Cups, Logviewer, News und Modulverwaltung auf dem getrennten
Prüfport; Links enthielten die korrekte IP und alle Assets wurden geladen.

Die letzte stabile Laravel-9-Version ist 2026 selbst von vier veröffentlichten
Advisories betroffen. Composer 2.10 blockiert sie daher standardmäßig bei einer
neuen Auflösung. Nur das Erzeugen dieses internen Lockbestands erfolgte einmal
mit `--no-blocking`; `composer audit` bleibt aktiv und meldet alle vier Funde.
Zusätzlich bleiben Steam Auth, Laravel Collective HTML und Less.php aufgegeben.
Der Schritt ist eine interne Migrationsstufe und ausdrücklich keine Public-
Freigabe.

### Laravel-10-Migrationsstufe 0.10.0 - 2026-09-07 09:45 CEST

Die nächste Framework-Achse hebt Laravel bei unverändertem PHP 8.5.10 auf die
stabile Ausgabe 10.50.3. Sentinel 7.0.2 stellt den Illuminate-10-Vertrag her;
Collision 7.12.0, Spatie Laravel Ignition 2.9.1 und PHPUnit 10.5.64 folgen dem
Laravel-10-Upgradepfad. Die PHPUnit-Konfiguration wurde auf das aktuelle Schema
migriert und Composer verwendet jetzt `minimum-stability: stable`.

Der erste strenge Composer-Lauf zeigte den tatsächlichen Paketblocker
`invisnik/laravel-steam-auth`: Version 4.4.0 endet bei Illuminate 9 und ist
aufgegeben, Contentify nutzt ihre Steam-OpenID-API jedoch direkt. Der exakte
MIT-lizenzierte Upstream-Commit
`94a0ef489932615612ddb745b3be3ff679afa209` wurde deshalb als lokale Version
4.4.1 übernommen. Nur `composer.json` und die Herkunftsdokumentation wurden
ergänzt; der PHP-Paketcode blieb unverändert.

Laravel 10 entfernt die bisher in 40 Contentify-Modellen verwendete Eloquent-
Eigenschaft `$dates`. Sämtliche Felder wurden mechanisch auf gleichwertige
`datetime`-Casts umgestellt. Ein neuer Test prüft `GameMatch::played_at`,
`CupMatch::deleted_at` und `News::published_at`. Der historische Feature-
Platzhalter erwartet nun den korrekten Installer-Redirect statt einer
unbegründeten HTTP-200-Antwort auf einem uninstallierten Testsystem.

Der finale Kandidat meldete Laravel 10.50.3 und PHP 8.5.10. Er bestand 792
Syntaxprüfungen, 14 Tests mit 47 Assertions, Composer-Validierung, 515 Routen,
vier ausgeführte Migrationen, lesende Match-/Cup-Modellabfragen und beide
Smoke-Tests. Das JSON-Betriebslog und das klassische Adminlog enthalten den
gleichen Prüfmarker. Im Browser funktionierten Dashboard, Matches, Cups,
Logviewer, News und Module ohne Konsolenfehler und mit der korrekten IP.

`composer audit --locked --no-dev` meldet weiterhin drei Advisories in Laravel
10.50.3. Composer markiert außerdem Laravel Collective HTML und Less.php als
aufgegeben. Der Lockbestand wurde für diese bewusst interne Zwischenstufe
einmal mit `--no-blocking` erzeugt; Findings werden nicht ausgeblendet. Daher
bleibt Public gesperrt und Laravel 11 ist die nächste Migrationsstufe.

### Laravel-11-Migrationsstufe 0.11.0 - 2026-09-07 10:26 CEST

Laravel wurde bei unverändertem PHP 8.5.10 auf 11.56.1 angehoben. Sentinel
wechselt regulär auf 8.0.0, Collision auf 8.5.0 und Carbon auf 3.13.2. Die
bestehende Laravel-10-Anwendungsstruktur bleibt gemäß offiziellem Upgradepfad
erhalten. Contentify verwendet weiterhin seinen vollständigen eigenen
Konfigurationsbaum und deaktiviert deshalb das zusätzliche Zusammenführen der
schlanken Laravel-11-Frameworkvorgaben. Dadurch verschwinden zugleich zwei
PHP-8.5-Deprecations aus der doppelten Datenbankkonfiguration.

Die erste Auflösung stoppte regulär an Sentinel 7; dessen neue Version 8 stellt
den Laravel-11-Vertrag bereit. Danach blockierte `laravelcollective/html` 6.4.1,
das upstream nur bis Illuminate 10 reicht. Der exakte MIT-Stand vom Commit
`64ddfdcaeeb8d332bd98bef442bef81e39c3910b` liegt nun als lokale Version 6.4.2
im Projekt. Composer-Metadaten, Herkunftsdokumentation und genau zwei unter
PHP 8.5 implizit-nullbare Konstruktorsignaturen wurden angepasst.
Die Modul- und Steam-Brücken werden nach demselben kontrollierten Muster als
6.3.4 und 4.4.2 weitergeführt; ihr PHP-Code bleibt unverändert.

Carbon 3 kann Zeitdifferenzen gerichtet als Fließkommazahl liefern. Die einzige
Contentify-Verwendung von `diffInMinutes()` entscheidet nur, ob ein Forenbeitrag
nachträglich geändert wurde, und wertet die Differenz nun mit `abs()` bewusst
richtungsunabhängig aus. Der Installationstest kapselt den vorhandenen
Staging-Marker jetzt vor dem Anwendungsstart und prüft die tatsächliche
Installer-Closure ohne Abhängigkeit von einem gemounteten Laufzeitspeicher.

Die eigene Carbon-Unterklasse las außerdem noch die in Carbon 3 entfernte
statische Eigenschaft `$toStringFormat`. Erstellformulare für News, Matches und
Seiten antworteten deshalb im ersten Browserlauf mit HTTP 500. `date()` und
`dateTime()` verwenden nun direkt das bereits übersetzte Contentify-
Datumsformat; ein eigener Regressionstest deckt beide Methoden ab.

Der erweiterte Formularlauf deckte zusätzlich einen bereits auf Laravel 10
vorhandenen Fehler aus der PHP-8-Modellumbenennung auf: Der Matches-Controller
leitete `admin_matches_form` statt des vorhandenen `admin_form` ab. Ein
expliziter Formularvertrag und ein Regressionstest beheben diesen Fehler.

Composer validiert den Lockbestand und PHPUnit 10.5.64 besteht 17 Tests mit 52
Assertions. Der Audit meldet weiterhin drei Advisories in Laravel 11.56.1 und
nur noch ein offiziell aufgegebenes Paket, Less.php. Public bleibt gesperrt;
Laravel 12 ist die nächste getrennte Framework-Stufe.

Der abschließende Kandidat bestand 801 Syntaxprüfungen, 17 Tests mit 52
Assertions, 512 aktive Produktionsrouten, vier Migrationen, beide Smoke-Tests,
lesende Match-/Cup-Datenbankzugriffe und den Steam-Service-Bootstrap. Der
saubere Browserlauf öffnete Dashboard, Matches, Cups, Logviewer, News- und
Seitenformulare sowie Module ohne neue Anwendung-, PHP- oder Nginx-Fehler.

Die Live-Prüfung zeigte danach, dass ein Laravel-CLI-Lauf als Container-Root
die zentrale Tagesdatei mit `root:root` erzeugen kann. PHP-FPM konnte sie als
`www-data` nicht fortschreiben und die Protokollausnahme überlagerte den
ursprünglichen Fehler. Version 0.11.1 setzt vorhandene Anwendungs- und PHP-FPM-
Logs auf `www-data` zurück, startet den App-Dienst selbst unter diesem Benutzer und
dokumentiert `www-data` als verbindlichen Benutzer für Laravel-CLI-Befehle.

### Laravel-12-Prüfung und Staging-Rollout 0.12.0 - 2026-09-07 12:10 CEST

Der Laravel-12-Löserlauf zeigte zunächst genau einen harten Konflikt: Sentinel
8.0.0 ist auf Illuminate 11 begrenzt. Statt einer lokalen Paketänderung wird die
offizielle Sentinel-Version 9.0.0 eingesetzt, die Illuminate 12 unterstützt.
Laravel löst dadurch stabil auf 12.69.1, Collision auf 8.9.5 und PHPUnit auf
11.5.56. Die lokalen Paketversionen Module 6.3.5, Steam-Auth 4.4.3 und
Collective HTML 6.4.3 ändern ausschließlich ihre Composer-Verträge auf
Illuminate 12; gegenüber 0.11.1 wurde dort kein PHP-Code verändert.

Der offizielle Upgradeleitfaden nennt neben den Abhängigkeiten UUIDv7,
Container-Standardwerte, Route-Priorität, lokale Dateisystemwurzel,
SVG-Validierung und einige niedrigstufige Datenbankkonstruktoren. Eine statische
Suche bestätigte, dass Contentify weder UUID-Traits noch eigene Grammatiken,
Concurrency oder verschachteltes `mergeIfMissing()` verwendet. `storage/app`
ist bereits explizit konfiguriert und wird mit einem neuen Test gesichert. Der
eigene Uploader behandelt SVG unabhängig von Laravels Validator.

Der isolierte Kandidat auf Port 8088 verwendete getrennte Storage-, Public- und
Logbereiche, aber die echte Staging-Datenbank nur lesend für die geprüften
Modelle und Dienste. Er bestand 802 Syntaxprüfungen, 18 Tests mit 54 Assertions,
512 Routen, vier ausgeführte Migrationen, beide Smoke-Tests, Sentinel- und
Steam-Auflösung sowie das Dual-Logging. Im Browser wurden sämtliche 36
Adminbereiche geöffnet. Das Match-Erstellformular erreichte erwartungsgemäß die
fachliche Sperre für ein fehlendes Team; der frühere falsche Viewname trat nicht
mehr auf. Fehlende Spielbilddateien liefern bereits auf 0.11.1 wegen BUG-017
HTTP 500 und wurden deshalb nicht als Laravel-12-Regression gewertet.

`composer audit --locked` meldet erstmals keine bekannte Sicherheitslücke. Nur
`oyejorge/less.php` bleibt als aufgegebenes Paket und eigene Frontend-
Modernisierungsaufgabe offen. Die Laravel-12-Stufe wurde als 0.12.0 auf Staging
übernommen; Public bleibt bis zur unabhängigen Testinstallation gesperrt.

### Laravel-12-Laufzeitkorrektur 0.12.1 - 2026-09-07 12:42 CEST

Die nachgelagerte authentifizierte Browserprüfung zeigte `Unknown named
parameter $user` beziehungsweise `$slug` in Contentifys überschriebenem
`BaseController::callAction()`. Die Methode verwendete noch
`call_user_func_array()` mit dem assoziativen Routenparameterarray. PHP 8 deutet
dessen Schlüssel als benannte Argumente, während Laravels eigener Controller
die Werte ausdrücklich positionsbasiert übergibt. Contentify folgt nun diesem
Vertrag mit `array_values($parameters)`. Ein fokussierter Regressionstest prüft
bewusst einen Routenparameter `user` gegen ein anders benanntes Controller-
Argument. Der vollständige Stand besteht danach 19 Tests mit 55 Assertions;
alle 36 Adminbereiche sowie das Benutzerprofil wurden erneut im isolierten
Kandidaten geöffnet.

### Laravel-13-Prüfung und Staging-Rollout 0.13.0 - 2026-09-07 13:49 CEST

Der getrennte Laravel-13-Löserlauf aktualisiert das Framework auf 13.30.1,
Sentinel auf 10.0.0, Tinker auf 3.0.2 und PHPUnit auf 12.5.34. PHP bleibt
unverändert 8.5.10. Die lokalen Versionen Module 6.3.6, Steam-Auth 4.4.4 und
Collective HTML 6.4.4 erweitern nur ihre Composer-Verträge auf Illuminate 13;
gegenüber 0.12.1 wurde ihr PHP-Paketcode nicht verändert.

Der vollständige offizielle Upgradeleitfaden wurde gegen Contentify geprüft.
Laravel 13 verhindert verschachtelte Instanzen eines Modells während dessen
Bootvorgang; genau dies löste `watson/validating` über `observe(new Observer)`
aus. Contentifys gemeinsame Modellbasis registriert die beiden vorhandenen
Observer-Methoden deshalb direkt. Der neue Frameworkschutz
`PreventRequestForgery` prüft zusätzlich die Request-Origin. Contentifys eigene
Middleware erweitert ihn nun und behält nur die bestehende Drei-Sekunden-
Spamsperre als Hülle. Die echte Ab- und Anmeldung im Kandidaten bestätigt den
Token-, Cookie- und Sitzungsweg.

Laravels neue Cache-Klassenliste blockierte erwartungsgemäß einen aus 0.12.1
kopierten Feed-Cache mit `stdClass`-Datensätzen. Statt den Altbestand nur zu
löschen, erlaubt die Konfiguration gezielt diese eine Datenklasse. Alle anderen
Objektklassen bleiben gesperrt. Das bisherige PHP-Sitzungsformat wird explizit
beibehalten; eine spätere JSON-Umstellung wäre ein eigener, sitzungsbrechender
Migrationsschritt. Die übrigen Änderungen an Cache-/Session-Fallbacknamen,
Upsert-Schlüsseln, Domainrouten, Queue-Ereignissen, Manager-Closures,
Pagination-Views und Polyfill-Helfern betreffen keinen eigenen Contentify-Code.

Der finale isolierte Kandidat auf Port 8088 bestand 802 Syntaxprüfungen,
19 Tests mit 58 Assertions, 512 aktive Routen, vier Migrationen, beide
Smoke-Tests, Datenbankmodelle, Sentinel- und Steam-Auflösung sowie das
Dual-Logging. Nach einer frischen Browseranmeldung wurden alle 36 Adminbereiche
und das Benutzerprofil ohne neuen Laravel-, PHP-FPM- oder Nginx-Fehler geöffnet.
Der Composer-Audit meldet keine bekannte Sicherheitslücke; nur das aufgegebene
Less.php bleibt als Frontend-Aufgabe. Die im Kandidaten gefundene Cache-
Inkompatibilität und zwei korrigierte Kandidaten-Mounts bleiben in den
getrennten Prüfprotokollen erhalten.

Bei der Live-Abnahme blieb zunächst der Laravel-12-Feed an erster Stelle, obwohl
der veröffentlichte GitHub-Stand bereits Laravel 13 enthielt. Der Raw-CDN lieferte
dem Staging-Netz noch die alte Antwort. Die Bad-Hippo-Feed-URL enthält deshalb
die Standkennung 0.13.0 und verwendet einen neuen unabhängigen Cache-Schlüssel;
die erneute Browserprüfung muss den Laravel-13-Eintrag an erster Stelle zeigen.

### CKEditor-Ablösung und Kandidatenprüfung 0.14.0 - 2026-09-07 15:58 CEST

Der ausgelieferte CKEditor 4.3.1 stammt aus 2013. CKEditor 4 ist seit Juni 2023
außerhalb des kommerziellen LTS-Zweigs abgekündigt, und die letzte freie 4.22.1
enthält bekannte Sicherheitsprobleme. CKEditor 5 ist selbst gehostet nur unter
GPL 2+ oder einer kommerziellen Sonderlizenz verfügbar. Um Contentifys
MIT-Verteilung beizubehalten, ersetzt 0.14.0 den alten Editor durch den exakt
festgeschriebenen, MIT-lizenzierten SunEditor 3.3.2. Alle 236 CKEditor-Dateien
werden aus dem Repository entfernt.

`public/vendor/contentify/editor.js` bildet die Contentify-spezifische Brücke:
deutsche Desktop-/Mobil-Werkzeugleisten, mehrere Editoren pro Formular sowie die
vorhandenen Dialoge für Bilder, Vorlagen und Flaggen. Vor dem Absenden wird jeder
Editor – auch im Quelltextmodus – in sein Textfeld synchronisiert. Nur interne
`data-se-*`-Attribute und `se-*`-/`__se__*`-Klassen werden entfernt; fachliches
HTML bleibt unverändert.

Im isolierten Kandidaten auf Port 8088 bestanden 21 Unit-Tests mit 71 Assertions,
die fokussierte Editor-Suite mit 3 Tests und 16 Assertions, 515 Routen sowie
beide Smoke-Tests. Ein echter Browserlauf erstellte eine News mit Umlauten,
Flagge und aktivem Quelltextmodus, speicherte sie und öffnete sie anschließend
erneut zum Bearbeiten. Zwei kandidatspezifische Dateirechte wurden getrennt
korrigiert und nicht als Editorfehler verschwiegen. PHP 8.5.10, Laravel 13.30.1
und Bootstrap bleiben in dieser Stufe unverändert.

Da Contentifys offener BUG-017 fehlende Dateien bisher an Laravel durchreichte
und dort als HTTP 500 darstellte, behandelt Nginx bekannte statische Endungen
ab 0.14.0 direkt mit `try_files ... =404`. Das löst den Asset-Teil und erlaubt
eine eindeutige CKEditor-Negativprobe; die fehlerhafte Behandlung unbekannter
dynamischer Routen bleibt separat offen.

Um 16:44 CEST wurde exakt der auf GitHub veröffentlichte Stand auf das Staging
an Port 80 übernommen. Datenbank, Uploads, Schlüssel und zentrale Logs blieben
unverändert. App und Jobs laufen mit dem Image `contentify-staging-app:0.14.0`;
Nginx und MariaDB sind gesund. PHP meldet 8.5.10, Laravel 13.30.1 und die
Buildkennung 0.14.0. Homepage sowie SunEditor-JavaScript, SunEditor-CSS und der
Contentify-Adapter antworten mit HTTP 200, der alte CKEditor-Pfad mit HTTP 404.
Beide Smoke-Tests bestehen, der Live-Modulbestand enthält 512 Routen, und der
echte Browser zeigt beide deutschen Editoren sowie die neue Bad-Hippo-Meldung
an erster Stelle. Die Containerlogs enthalten seit dem Rollout keinen neuen
Fehler, keine Exception und keinen Rechtefehler.

### Node-/LESS-Modernisierung 0.15.0 bis 0.15.3 - 2026-09-07 18:33 CEST

Der historische Baum aus Grunt 1.3, `grunt-contrib-less` 1.0.1,
`grunt-contrib-watch` 0.6.1 und `jit-grunt` ließ sich mit npm 11 nicht regulär
auflösen. Eine Legacy-Installation enthielt 23 bekannte Schwachstellen. Selbst
die neuesten Grunt-Plugins ließen über den alten Watch-Unterbau noch vier hohe
Schwachstellen zurück.

Da Contentify nur `resources/assets/less/backend.less` kompiliert, verwendet
0.15.0 stattdessen Less 4.9.1 direkt. `scripts/watch-less.js` bietet denselben
Build- und Watch-Ablauf ohne den aufgegebenen Task-Runner; ein `--once`-Modus
macht ihn prüfbar. `scripts/check-frontend.js` kontrolliert Node 24, Lockstand,
entfernte Grunt-Pakete, Editorregeln und den für die Admin-Icons entscheidenden
Glyphicons-Pfad. Die URL-Umschreibung ist explizit, damit weiterhin
`public/css/fonts` statt des nicht vorhandenen `public/fonts` verwendet wird.

Der Open-Sans-Import wird als CSS-Import erhalten und nicht mehr während des
Builds vom Google-Endpunkt expandiert. Zwei unabhängige Buildwege erzeugten
denselben SHA-256-Wert
`22B885942914CCB8FA02A858521EF9B908C033DCBD1129D190C5FB7D0F76833F`.
`npm ci`, `npm audit`, Build, Vertragstest und Einmal-Watcher bestanden; der
Audit meldet null bekannte Schwachstellen. PHP 8.5.10, Laravel 13.30.1,
SunEditor 3.3.2 und Bootstrap 3.3.7 wurden in dieser Stufe nicht geändert.

Der erste Containerlauf des Kandidaten zeigte zusätzlich, dass `phpunit.xml`
SQLite erzwingt, ein installierter Kandidat aber wegen seines `.installed`-
Markers schon beim Booten die echte Contentify-Konfigurationstabelle abfragt.
Die 0.15.1/0.15.2-Versuche, SQLite ins Image aufzunehmen, wurden kontrolliert
verworfen: Der Test darf nicht an die installierte Betriebsumgebung gekoppelt
sein. 0.15.3 blendet den Marker zentral in `Tests\CreatesApplication` aus und
stellt ihn im gemeinsamen `TestCase` selbst bei Fehlern wieder her. Das Image
und die MariaDB-Betriebsdaten bleiben unverändert.

Der finale isolierte Kandidat bestand unter PHP 8.5.10 und Laravel 13.30.1 die
vollständigen 22 Tests mit 74 Assertions, 515 Routen, beide direkt ausführbaren
Smoke-Skripte sowie den npm-Vertrag. Anschließend wurde der exakte Commit
`0a4e094826b9f091c1a4de57c600b12b77e38ad3` in ein frisches Git-Checkout unter
`/opt/contentify-staging` übernommen. Der vorherige 0.14.0-Quellstand liegt als
Rollback-Kopie unter `/opt/contentify-staging-backup-0.14.0-20260907-1835`;
Datenbank, Storage, Uploads, Anwendungsschlüssel und zentrale Logs wurden nicht
ersetzt.

Die Live-Container melden Build 0.15.3, PHP 8.5.10, Laravel 13.30.1, 512 auf
diesem Modulbestand aktive Routen und eine vorhandene Installationsmarkierung.
Composer meldet keine bekannte Sicherheitslücke, aber weiterhin das bereits
erfasste aufgegebene Produktionspaket `oyejorge/less.php`. Upload- und
Speicherplatz-Smoke bestehen. Homepage, Backend-CSS, Glyphicons und die vier
tatsächlich eingebundenen SunEditor-Dateien antworten mit HTTP 200; CKEditor
antwortet mit HTTP 404. Der authentifizierte Browser zeigt Icons, ausschließlich
IP-basierte Menüziele, den Bad-Hippo-Feed an erster Stelle sowie beide deutschen
Editoren mit ihren Werkzeugleisten. Seit dem Rollout entstanden keine neuen
Anwendungs-, Job-, PHP- oder Nginx-Fehler. Danach wurde der isolierte Kandidat
beendet, ohne seine Diagnose-Volumes zu löschen.

### Bootstrap-3-Kompatibilitätsbrücke 0.16.0 - 2026-09-07 19:44 CEST

Der Quellbestand war nicht auf einer einzelnen Version: die eingebetteten LESS-
Dateien meldeten 3.3.3, während Backend, Morpheus und Phobos JavaScript 3.3.1
von MaxCDN luden. 0.16.0 übernimmt die offiziellen MIT-lizenzierten LESS-,
JavaScript- und Glyphicon-Dateien aus dem exakt festgeschriebenen npm-Paket
Bootstrap 3.4.1. Das JavaScript liegt nun unter `public/vendor/bootstrap`; alle
drei Layouts verwenden diesen lokalen Pfad.

Der Build erzeugt separat Backend, Morpheus, Phobos und das aktive Morpheus-
Frontend. Der Vertragstest prüft Paket- und Lockversion, LESS-Versionsmarker,
CSS-Banner, lokales JavaScript und das vollständige Fehlen der alten CDN-URL.
Alle vier Builds sowie der Vertragstest bestehen lokal unter Node 24.

Bootstrap 3.4.1 ist dennoch kein Sicherheitsziel. GitHubs aktueller Audit
erfasst CVE-2025-1647 in Tooltip/Popover und CVE-2024-6485 im Button-Plugin als
ein moderates direktes Paketfinding; für 3.x existiert keine offizielle
gepatchte Version. Die Brücke muss deshalb im internen Staging bleiben und wird
in der nächsten getrennten Stufe durch Bootstrap 5.3.8 ersetzt. PHP, Laravel,
SunEditor und Anwendungsfunktionen wurden in 0.16.0 nicht geändert.

Der isolierte Kandidat auf Port 8088 meldete PHP 8.5.10, Laravel 13.30.1,
Contentify 0.16.0 und 512 Routen. PHPUnit 12.5.34 bestand 22 Tests mit 74
Assertions; beide eigenständigen Smoke-Skripte bestanden ebenfalls. Startseite,
Backend- und Frontend-CSS, Glyphicons, lokales Bootstrap-JavaScript und
SunEditor antworteten mit HTTP 200. Im authentifizierten Browser renderten
Startseite, Admin-Icons, IP-Menülinks, der Bad-Hippo-Feed an erster Stelle und
beide Editoren. Das durch Contentify ausgelöste Bootstrap-Bildermodal ließ sich
öffnen und schließen.

Beim ersten Kandidatenstart fehlte unter der absichtlich getrennten Logwurzel
`/var/log/contentify-bootstrap-candidate` der gemountete Unterordner `php`.
Diese Einrichtungsmeldung ist als STAGE-007 im Fehlerregister erhalten. Nach
Anlegen des Ordners mit UID/GID 33 blieb das frische Kandidatenintervall sauber.

Anschließend wurde der exakt auf GitHub veröffentlichte Commit
`c70dad8b8335f067af3b9fc6f84e4ba7e8602f3c` als App-, Job- und Nginx-Abbild
`0.16.0` auf Staging installiert; Datenbank und persistente Laufzeitdaten
blieben erhalten. Der Live-Container bestätigte PHP 8.5.10, Laravel 13.30.1,
512 Routen, den Installationsmarker und beide Smoke-Skripte. Alle genannten
Assets antworteten erneut mit HTTP 200. Die vollständige Browserprüfung wurde
auf Port 80 wiederholt. Das danach ausgewertete Container-Logintervall enthielt
keine Fehler, Exceptions, Fatal Errors oder Rechtefehler. Der temporäre
Kandidat wurde ohne Löschen seiner Diagnosevolumes beendet.

## Files added or updated

- `README.md`: local assessment notice and documentation links
- `bugs.md`: confirmed blockers and risks
- `todo.md`: completed audit work and required modernization backlog
- `porting.md`: seven-stage port plan and acceptance gates
- `PROJECT_DOCUMENTATION.md`: complete assessment record
- `config/logging.php` and `.env.example`: central Laravel channels and controls
- `app/Logging/LogContextProcessor.php`: shared environment, host, build and request context
- `app/Logging/JsonLogFormatter.php`: structured JSON Lines formatter
- `app/Logging/ClassicLogFormatter.php`: classic administrator display formatter
- `tests/Unit/LoggingConfigurationTest.php`: split-channel regression coverage
- `contentify/Translator.php`: Laravel-8-compatible placeholder ordering
- `tests/Unit/TranslatorTest.php`: translator replacement regression coverage
- `app/Http/Middleware/CheckForMaintenanceMode.php`: Laravel-8 maintenance middleware bridge
- `app/Modules/Matches/GameMatch.php` und `app/Modules/Cups/CupMatch.php`: PHP-8-kompatible Modellnamen bei unveränderten Tabellen
- `tests/Unit/Php8ModelNamesTest.php`: Tabellen- und Relationsverträge der umbenannten Modelle
- `packages/laravel-steam-auth`: dokumentierte Steam-OpenID-Kompatibilitätskopie
- `packages/laravelcollective-html`: dokumentierte Formular-/HTML-Kompatibilitätskopie
- `tests/Unit/Laravel10DateCastsTest.php`: Laravel-10-Datumsregression
- `tests/Unit/Laravel13CompatibilityTest.php`: Laravel-13-, Cache-, Sitzungs-,
  CSRF- und Speicherpfadvertrag
- `public/vendor/contentify/editor.js`: gemeinsame Contentify-SunEditor-Brücke
- `public/vendor/suneditor`: exakt vendorte SunEditor-3.3.2-Laufzeit und MIT-Lizenz
- `tests/Unit/EditorIntegrationTest.php`: Asset-, Formular- und Workflowverträge
- `package.json` and `package-lock.json`: exact editor/build dependencies and reproducible npm tree
- `scripts/check-frontend.js` and `scripts/watch-less.js`: Node-24 asset contracts and LESS watcher
- `composer.json` and `composer.lock`: reproducible Laravel-13 dependency rung
- `deploy/logging`: PHP, webserver, worker, scheduler, rotation and verification templates
- `deploy/staging`: reproducible Nginx, PHP-FPM, MariaDB and Contentify job stack
- `tests/Unit/UploaderTest.php`: PHPUnit regression for multiple upload fields
- `tests/Smoke/UploaderMultipleFiles.php`: dependency-free staging smoke check
- `contentify/DiskSpace.php`: safe shared disk-space adapter
- `tests/Unit/DiskSpaceTest.php` and `tests/Smoke/DiskSpace.php`: restricted-host regression checks

Die Stabilisierung wird auf `main` des Bad-Hippo-Forks veröffentlicht. Es wurde
kein Pull Request gegen das Original erstellt und kein Branch des ursprünglichen
Repositories verändert.

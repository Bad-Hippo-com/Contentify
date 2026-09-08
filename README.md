# Contentify – Bad Hippo Community-Fork

Stand 2026-09-08 09:47 CEST: **0.19.4 in Kandidatenprüfung, noch nicht auf Staging.**
0.19.3: 33 Tests/200 Assertions bestanden. Browsercache machte einen zentralen
Buildversionsparameter für lokale Assets erforderlich; externe URLs unverändert.
Weitere Befunde im Register: SVG-/PHP-Ausführungsgrenze und Upload-Datenerhalt.
0.19.2: 33 Tests/200 Assertions und Smoke-/Auditprüfungen bestanden.
0.19.3 beseitigt im Browser erkannte jQuery-3-Inkompatibilitäten in Kalender,
Kommentaren und Mitgliederverwaltung. Weitere Restore-Routen bleiben Sicherheitsarbeit.
Nachprüfung: Kalender-Initialisierung korrigiert, AJAX-CSRF nur Same-Origin-
Header, Freundschaftsbestätigung nur durch Empfänger. 0.19.1-Testlauf hatte zwei
Fixture-Fehler; korrigiert, erneute Abnahme erforderlich. Staging unverändert.
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

> **Status: frühe Stabilisierung, noch nicht für öffentliche Produktivsysteme freigegeben.**

Dies ist der deutschsprachig gepflegte Community-Fork von Contentify unter
[`Bad-Hippo-com`](https://github.com/Bad-Hippo-com). Wir stabilisieren zuerst
den Funktionsumfang von Contentify ausgehend von 3.2-dev. Unsere gepflegte
Entwicklungslinie trägt ab jetzt die CMS-Kennung **3.3-dev**. PHP, Laravel,
Bootstrap und Node.js werden weiterhin einzeln, messbar und mit Tests
aktualisiert.

Das ursprüngliche Projekt und die Arbeit von Chris Konnertz bleiben ausdrücklich
genannt. Dieser Fork ist derzeit keine offizielle Fortsetzung des ursprünglichen
Maintainers. Zusammenarbeit, Rückführung geeigneter Änderungen und eine spätere
Übergabe bleiben ausdrücklich willkommen.

Aktueller Arbeitsstand: **Bad Hippo 0.18.3 / Contentify 3.3-dev**.
Die Installation funktioniert auf Staging; ein unabhängiger sauberer Testserver
und die Modernisierung des veralteten Software-Stacks stehen noch aus.

Wichtige Unterlagen:

- [Fehlerregister](bugs.md)
- [Aufgaben und Reihenfolge](todo.md)
- [Portierungsplan](porting.md)
- [Technische Projektdokumentation](PROJECT_DOCUMENTATION.md)
- [Staging mit Nginx](deploy/staging/README.md)

---

## Ursprüngliche Projektbeschreibung

![Contentify Logo](http://www.contentify.org/img/hero_small.png)

## Contentify CMS - v3.2 ALPHA

[![Build Status](https://img.shields.io/travis/Contentify/Contentify.svg?style=flat-square)](https://travis-ci.org/Contentify/Contentify)
[![Laravel](https://img.shields.io/badge/Laravel-8-orange.svg?style=flat-square)](http://laravel.com)
[![Source](http://img.shields.io/badge/source-Contentify/Contentify-blue.svg?style=flat-square)](https://github.com/Contentify/Contentify)
[![License](http://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://tldrlegal.com/license/mit-license)

[Contentify](http://contentify.org/) is an esports CMS based on the PHP framework Laravel 8.
Build your gaming website with a modern CMS.

- [x] Technologically advanced gaming CMS
- [x] High quality code and documentation
- [x] Based upon the most popular PHP framework
- [x] Ready for mobile devices
- [x] Easy to use but yet powerful
- [x] Free and open source
- [x] Tons of features

### Get the production version

**Download it here**: [3.1](https://github.com/Contentify/Contentify/releases/tag/v3.1)

To install Contentify please follow the instructions in the [wiki](https://github.com/Contentify/Contentify/wiki/Installation).

### Get the developer version

Clone this repository via git and switch to the `3.2-dev` branch. 
Via console, go to the Contentify directory and run `php composer.phar install`. 
Then follow the instructions in the [wiki](https://github.com/Contentify/Contentify/wiki/Installation).

**ATTENTION**: PHP 8 is supported but requires running `composer install --ignore-platform-reqs`, because
some dependencies do not officially support PHP 8.

### Update

To update from v3.1 to 3.2:
There is no real update. Install 3.2 and then add all your data and changes from 3.1.

**Changes**: [Changelog.md](changelog.md)

### Demo

Currently, there is no demo version.

### Support

You can get free support via GitHub's [issue](https://github.com/Contentify/Contentify/issues) section 
or via [e-mail](mailto:contact@contentify.org). 

### Contribution

Contributions welcome! [Learn more...](CONTRIBUTING.md)

### Local technical assessment

Local workstream version: **0.18.3**
Last updated: **2026-09-08 08:56 CEST**

This checkout was reviewed against current PHP, Composer, Node.js and Laravel
support levels. The result is **not production-ready without modernization**.
The upstream default branch is the unfinished `3.2-dev` / v3.2 ALPHA branch.
Bad Hippo continues from that baseline as `3.3-dev`; the separate `0.17.2`
identifier versions our individual, staged changes.

Bad Hippo `0.17.2` is installed on the internal staging host behind Nginx.
PHP 8.5/Laravel 13 and MariaDB are isolated in containers; this is the
migration workshop, not a public release. The Node/LESS rung passed 22 tests
with 74 assertions in its isolated candidate. Live staging reports 512 routes,
both regression smoke tests pass, the authenticated dashboard and both German
SunEditor instances render correctly, the locally delivered Bootstrap modal
opens and closes, and no new container error was logged after the browser run.
The first interactive administrator-login failure was diagnosed and corrected
on staging. Account state and session storage are healthy; the secret did not
survive Docker Compose interpolation unchanged during installation. The first
successful browser login also exposed internal-hostname links in the admin
navigation. Both findings and their remaining deployment work are in `bugs.md`.
The staging packaging error that removed Contentify's `public/vendor` assets
and admin icons is resolved in `0.2.3`; Font Awesome, the remaining browser
libraries and the expected Bootstrap Glyphicons are present and browser-tested.
Version `0.2.4` prevents the cached admin navigation from retaining Docker's
internal `nginx` hostname; its base URL is now resolved for each client request.
Version `0.2.5` corrects the shared uploader so every configured file field is
processed. Teams can now save logo and banner together, including a banner when
the earlier logo field is empty. The fix is covered by unit and staging smoke tests.
Version `0.2.6` makes the disk-space check safe on restricted hosting: unavailable
filesystem information is shown as unknown instead of crashing the dashboard.
Version `0.3.0` begins the controlled modernization: Laravel remains on major
version 6 and PHP remains on 7.4, while the framework is raised from 6.20.30 to
the final Laravel-6 patch 6.20.45 together with its resolved dependency set.
The candidate passes Composer validation, Artisan boot, PHP lint, five unit
tests and both staging smoke tests. It is installed on staging; the homepage,
login and required browser assets return HTTP 200. It still has known
advisories and is not a public release.
Version `0.4.0` adds a separately labelled Bad-Hippo dashboard feed next to the
unchanged Contentify original feed. Every Bad-Hippo entry and its source heading
links to `Bad-Hippo-com/Contentify` on GitHub. Remote feed content is validated
before it is rendered.
Version `0.4.1` places the newer Bad-Hippo feed above the historical original
feed in the administrator dashboard.
Version `0.5.0` raises only the framework axis from Laravel 6.20.45 to 7.30.7.
PHP deliberately remains 7.4. The exact Composer candidate passes all focused
unit and staging smoke tests; its production audit is reduced from 39 to 12
advisories, so this remains an internal migration rung rather than a release.
The same image is running on staging: the authenticated dashboard, 512 enabled
routes, login page, required assets and both regression smoke tests pass.
Version `0.5.1` mirrors default Laravel records into two independent outputs:
the detailed daily JSON operations log and the classic `laravel.log` consumed
by the existing administrator log page. Clearing the display copy does not
remove the protected central component logs. The deployed staging image writes
both formats as `www-data`; the administrator viewer displays the verified
classic entry while the matching JSON record retains environment, container,
build and request context.
Version `0.6.0` raises only the framework axis to Laravel 8.83.29 while PHP
remains 7.4. Sentinel and Collision move to their smallest compatible major
lines. Contentify's custom translator no longer calls Laravel's removed
`sortReplacements()` helper. The live staging image passed ten unit tests,
both smoke tests, 686 syntax checks, 512 routes, database inspection and the
authenticated dashboard, news and log views. Three Laravel advisories remain,
so this is still an internal migration rung rather than a public release.
Version `0.7.0` überschreitet getrennt davon ausschließlich die PHP-Grenze von
7.4 auf 8.0.30. Die beiden reservierten Modellnamen `Match` heißen nun
`GameMatch` und `CupMatch`; Tabellen, URLs und sichtbare Begriffe bleiben
unverändert. Das laufende Staging bestand 688 Syntaxprüfungen, zwölf Unit-Tests
mit 42 Assertions, beide Smoke-Tests, 512 Routen, Datenbankabfragen sowie die
authentifizierten Adminseiten für Matches, Cups und Logs. PHP 8.0 und Laravel 8
sind weiterhin abgekündigt; diese Stufe ist nur die kontrollierte Brücke zum
nächsten Framework-Upgrade.
Version `0.8.0` schließt die getrennte PHP-Achse mit PHP 8.5.10 ab, während
Laravel bewusst auf 8.83.29 bleibt. Composer installiert regulär ohne ignorierte
Plattformanforderungen. 735 Syntaxprüfungen sowie zwölf Unit-Tests mit 42
Assertions bestehen; eigene PHP-8.5-Deprecations sind beseitigt. Die weiterhin
protokollierten Deprecations stammen aus Laravel 8 und markieren die nächste
getrennte Framework-Arbeit. Staging läuft mit Nginx, App und Jobrunner auf dem
Image 0.8.0; eine Public-Freigabe ist damit noch nicht verbunden.
Version `0.9.0` hebt anschließend ausschließlich Laravel auf die letzte stabile
9.x-Version 9.52.21 an; PHP bleibt 8.5.10. Sentinel, Collision, Mailer,
Flysystem, Ignition und Proxy-Middleware wurden entlang dieser Framework-Grenze
aktualisiert. Die bisher zentrale, seit 2021 nicht weiter veröffentlichte
Caffeinated-Modulverwaltung liegt nun als dokumentierte MIT-Kompatibilitätskopie
im Projekt und bestand alle Modul- und Browserprüfungen. Vier Laravel-Advisories
und drei weitere aufgegebene Pakete verhindern weiterhin die Public-Freigabe.
Version `0.10.0` führt die nächste getrennte Framework-Stufe auf Laravel
10.50.3 aus; PHP bleibt unverändert 8.5.10. Sentinel steigt auf 7.0.2,
Collision auf 7 und Ignition auf 2. Die aufgegebene Steam-Authentifizierung
liegt nun wie die Modulverwaltung als dokumentierte MIT-Kompatibilitätskopie
im Projekt. Alle 40 alten Eloquent-`$dates`-Definitionen wurden auf explizite
`datetime`-Casts umgestellt. Composer, 14 Tests mit 47 Assertions, 515 Routen,
beide Smoke-Tests und die kritischen Adminseiten bestehen. Drei bekannte
Laravel-Advisories verhindern weiterhin die Public-Freigabe.
Version `0.11.0` hebt ausschließlich die Framework-Achse auf Laravel 11.56.1;
PHP bleibt 8.5.10. Sentinel 8 und Carbon 3 werden regulär aktualisiert. Das
alte Modulsystem, Steam-OpenID und Laravel Collective HTML laufen für diese
Stufe als dokumentierte lokale Kompatibilitätsbrücken. Collective HTML benötigt
dabei zwei rein signaturbezogene PHP-8.5-Korrekturen. Contentifys Carbon-
Unterklasse verwendet statt der in Carbon 3 entfernten statischen Eigenschaft
nun direkt das übersetzte Datumsformat. Der vollständige
Konfigurationsbaum verhindert das
zusätzliche Laden der schlanken Laravel-11-Standardkonfiguration; die einzige
Carbon-3-relevante Zeitdifferenz wurde auf richtungsunabhängiges Verhalten
festgelegt. Der seit der PHP-8-Modellumbenennung falsche Match-Formular-Viewname
ist ebenfalls repariert und getestet. Die Stufe bleibt intern und ist keine
Public-Freigabe. Der finale Kandidat besteht 801 Syntaxprüfungen, 17 Tests mit
52 Assertions, 512 aktive Produktionsrouten, Datenbank-, Log- und
authentifizierte Browserprüfungen.
Version `0.11.1` legt den PHP-FPM-App-Container auf `www-data` fest. Damit
erzeugen auch spätere Laravel-CLI-Prüfungen keine root-eigenen zentralen
Logdateien mehr, die der Webprozess nicht fortschreiben könnte.
Version `0.12.0` hebt ausschließlich Laravel auf 12.69.1 an; PHP bleibt 8.5.10.
Sentinel 9 unterstützt Laravel 12 offiziell. Die drei lokalen Brücken für
Module, Steam-OpenID und Laravel Collective HTML benötigen nur angepasste
Composer-Verträge, ihr PHP-Code bleibt unverändert. PHPUnit 11, 802
Syntaxprüfungen, 18 Tests mit 54 Assertions, 512 Routen, beide Smoke-Tests und
alle 36 Adminbereiche bestehen. Der Composer-Audit meldet erstmals keine
bekannte Sicherheitslücke; nur `oyejorge/less.php` bleibt aufgegeben.
Version `0.12.1` übernimmt Laravels positionsbasierte Controller-Übergabe auch
in Contentifys überschriebenem `callAction()`. Dadurch funktionieren unter PHP
8.5 und Laravel 12 Routenparameter unabhängig vom Namen des Zielarguments. Ein
Regressionstest sowie die erneute Prüfung aller 36 Adminbereiche sichern den Fix.
Version `0.13.0` hebt ausschließlich Laravel auf 13.30.1 an; PHP bleibt 8.5.10.
Sentinel 10 und PHPUnit 12 werden regulär aktualisiert. Contentifys Validierungs-
Basismodell registriert seinen bestehenden Observer direkt, weil Laravel 13 eine
zweite Modellinstanz während des Bootens bewusst ablehnt. Der eigene CSRF-Schutz
delegiert nun an Laravels neuen Origin- und Token-Schutz und behält die vorhandene
Drei-Sekunden-Spamsperre. Für bestehende Feed-Caches ist ausschließlich
`stdClass` zur Deserialisierung zugelassen; Sitzungen bleiben während dieses
getrennten Framework-Schritts im PHP-Format. Der Kandidat besteht 802
Syntaxprüfungen, 19 Tests mit 58 Assertions, 512 Routen, beide Smoke-Tests,
Anmeldung, Dual-Logging und alle 36 Adminbereiche. Der Audit bleibt frei von
bekannten Sicherheitslücken; Public bleibt bis zum separaten Testsystem gesperrt.

Version `0.14.0` ersetzt ausschließlich den veralteten CKEditor 4.3.1 durch
den exakt festgeschriebenen, MIT-lizenzierten SunEditor 3.3.2. PHP 8.5.10,
Laravel 13.30.1, Bootstrap und der übrige Node-Werkzeugbaum bleiben unverändert.

Version `0.15.0` modernisiert anschließend ausschließlich die Frontend-
Baukette: Node.js 24 und npm 11 verwenden Less 4.9.1 direkt sowie einen kleinen
projektlokalen Watcher. Grunt und seine nicht mehr sauber auflösbaren Plugins
sind entfernt. `npm ci` und `npm audit` laufen ohne Sonderparameter und ohne
bekannte Schwachstellen. Bootstrap bleibt bewusst unverändert auf 3.3.7.

Der Kandidatenlauf 0.15.3 blendet den Installationsmarker während PHPUnit
zentral aus. Dadurch bootet die Suite auch auf einem installierten System ohne
Zugriff auf dessen Betriebsdatenbank; der Marker wird anschließend garantiert
wiederhergestellt.

Version `0.16.0` vereinheitlicht den zuvor gemischten Bootstrap-Bestand aus
CSS 3.3.3 und externem JavaScript 3.3.1 auf lokal ausgeliefertes Bootstrap
3.4.1. Backend, Morpheus, Phobos und das aktive Frontend werden reproduzierbar
aus derselben LESS-Quelle gebaut. Dieser kompatible Zwischenschritt ist bewusst
noch nicht Public-fähig: Der aktuelle npm-Audit meldet zwei Bootstrap-3-XSS-
Advisories als ein moderates Paketfinding ohne offiziellen 3.x-Patch. Das
eigentliche Sicherheitsziel ist deshalb Bootstrap 5.3.8 in einer getrennten
Markup- und JavaScript-Stufe.
Contentifys Bilder-, Vorlagen- und Flaggenfunktionen übernimmt ein gemeinsamer
deutscher Editor-Adapter. Erstellen, Bearbeiten und Speichern im Quelltextmodus
wurden im isolierten Staging-Kandidaten im echten Browser geprüft.

See:

- [PROJECT_DOCUMENTATION.md](PROJECT_DOCUMENTATION.md) for evidence and the feasibility decision
- [bugs.md](bugs.md) for confirmed defects and risks
- [todo.md](todo.md) for the modernization backlog
- [porting.md](porting.md) for the proposed migration path

# Contentify porting plan

Stand 2026-09-08 11:00 CEST: **0.21.2 auf Kandidat :8088 installiert und geprüft.**
Sauberer Container: 35 Regressionstests/205 Assertions und zehn Ablauftests/103
Assertions bestanden (45 Tests/308 Assertions), beide Smoke-Tests ebenfalls.
Alle vier Dienste laufen; Protokolle inklusive Fehlversuchen zentral archiviert
unter /var/log/contentify-bootstrap-candidate/deployment/contentify-021*-*.log.
Deployment-Hinweis: Nginx übergibt den maskierten CONTENTIFY_LOG_URI als FastCGI-
Parameter an FPM; dessen access.format nutzt %{CONTENTIFY_LOG_URI}e.
Referenz: https://www.php.net/manual/de/install.fpm.configuration.php
Bei anderen Webservern muss dieser Parameter ebenfalls gesetzt werden.
Geschützter POST setzt ein selbst gewähltes Passwort; GET zeigt nur das Formular.
Token: SHA-256-Digest in der DB, 60 Minuten gültig, einmalig, neuer Antrag ersetzt
alte Links. Benutzerzeilensperre serialisiert Antrag und Abschluss. Kein Passwort
per E-Mail, Sitzungen werden widerrufen. Antwort nennt keine Kontoexistenz;
fünf Anträge je IP/Stunde zusätzlich zum Captcha. Formular: 12–72 Zeichen.
Zehn Controller-/DB-Tests mit 103 Assertions bestanden, inklusive Ablauf,
Wiederverwendung, Ersatzlink, Ratenlimit, GET-Unveränderlichkeit und echtem CSRF-419.
Nginx-/FPM-Zugriffslogs maskieren Reset-Pfade (live bestätigt); Laravel-Pfadkontext
ebenfalls maskiert. Referrer-Policy, No-Store und CSRF-419 live bestätigt.
0.21.1 ergänzt die 72-Byte-Bcrypt-Grenze; 0.21.2 korrigiert den Widerruf der
aktuellen Sentinel-Persistenz bei bereits angemeldeten Benutzern (Test bestanden).
SMTP, Timing-Seitenkanäle, konkurrierende Lasttests und allgemeine Logredaktion
sind nicht vollständig abgenommen. Vorhandene Reset-Links werden ungültig.
GitHub-Arbeitszweig bleibt bad-hippo/stabilisierung-sicherheit; main und :80 unverändert.

Vorheriger Stand 2026-09-08 10:35 CEST: **0.20.5 auf Kandidat :8088; Staging :80 bleibt 0.18.3.**
Sauberer Neubau: 35 Regressionstests/205 Assertions und acht Ablauftests/72
Assertions bestanden, außerdem beide Smoke-Tests, LESS-Neubau und Composer-Audit.
npm-Test und Produktions-Audit bestanden (keine gemeldeten Advisories).
Browser: Beide News-Editoren samt Werkzeugleisten und Kalender-Symbol sichtbar.
Entfernte Browser-LESS-/Kalenderdateien und jQuery 2.2.4 liefern HTTP 404.
Protokolle zentral unter /var/log/contentify-bootstrap-candidate/deployment.
Frontend-Inventar: FRONTEND_DEPENDENCIES.md. Unbenutztes Browser-LESS und
zweiter Kalender entfernt; Glyphicons nicht mehr in aktiven Layouts geladen.
Acht echte Controller-/DB-Ablauftests: 72 Assertions bestanden. Erfasst sind
Registrierung/Login, Nachrichtenrechte, Kommentare, Reset mit abgefangenen Mails,
Forum, Solo-Cup bis zum Sieger, abgelehnter Upload und Matchergebnis-CRUD.
Datensätze werden transaktional zurückgerollt; nur Kandidat :8088 ist freigegeben.
Keine vollständige Browser-/SMTP-/Captcha-/Upload- oder Sicherheitsabnahme.
Kalenderersatz, LESS-Helfer, Tagsinput/Flot und Designmodernisierung bleiben offen.
Die nachfolgenden Browser-/34-Test-Nachweise beziehen sich auf 0.19.5.
GitHub: Arbeitszweig bad-hippo/stabilisierung-sicherheit, noch nicht main.
Browser: Seiteneditor und beide News-Editoren sichtbar, Texteingabe und deutscher
Kalender funktionieren ohne neue JavaScriptfehler; Downloads-Editor ebenfalls.
Container-Endprüfung bestanden: 34 Tests/203 Assertions, beide Smoke-Skripte,
LESS-Neubau und Composer-Audit. Node-Build und npm-Prüfungen bestanden.
Reparaturen: Cup-Siegerwechsel, Kommentar-Kontext/Bedienung, HTTP-Status und
Nachrichten-Einstieg. Sicherheitsmaßnahmen: bestätigte GET-Aktionen auf POST,
Same-Origin-CSRF-Header, Empfängerrechte bei Freundschaften, jQuery 3.7.1,
Moment 2.30.1 und Editor-Standardfilter. Alte Browserdateien per Buildversion erneuert.
Offen: Restore-Routen, Upload-/SVG-Schutz und Datenerhalt, umfassende Objekt-/
Rollenrechte, serverseitiges HTML, Container-Audit und Neuinstallation auf Test.
Befunde zentral in bugs.md; keine vollständige Sicherheits- oder Public-Freigabe.

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

Local workstream version: **0.19.5 (Kandidat); Staging: 0.18.3**
Last updated: **2026-09-08 09:51 CEST**

## Decision

Bootstrap-5-Zwischenstand (2026-09-08): offizielle kompilierte CSS und Bundle-JS
werden lokal eingebunden. Die Themes bleiben vorerst LESS-basiert und nutzen
einen dokumentierten Referenzadapter für alte Mixins und Komponenten.
Bootstrap-Komponenten verwenden native APIs. Als Folgearbeit sind der LESS-Adapter,
verbleibende jQuery-Plugins, Datumsauswähler und Glyphicons schrittweise abzulösen.
Ein erfolgreicher npm-Audit ersetzt keine Prüfung manuell vendorter Bibliotheken.

A current, supportable Contentify deployment is technically feasible, but it
is a **modernization project**, not a normal installation. The historical
application can be used only as a functional reference. Deploying it unchanged
would require end-of-life PHP 7.4 and would retain known vulnerable packages.

## Environment model

Two systems on separate IP addresses are mandatory:

| System | Purpose | Change policy |
| --- | --- | --- |
| Staging, interne Adresse | Development, upgrades, destructive experiments and failure analysis | Historical baseline installed; may be broken and rebuilt while a step is being developed |
| Test, IP pending | Clean installation and acceptance rehearsal for a release candidate | Receives only a version already proven on staging |

The systems must not share their application database, uploads, cache, session
storage, credentials or application key. Promotion means installing the same
versioned change and migration procedure cleanly on test, not copying an
uncontrolled staging filesystem onto test.

Credentials containing dollar signs or other Compose interpolation characters
must be passed by a mechanism that preserves the exact byte sequence. Every
clean installation must compare the supplied secret to the resulting password
hash without logging the secret, then perform an actual browser login. A mere
successful installer exit is not sufficient.

Each host uses its own `/var/log/contentify` root. Laravel application,
security and job records are daily JSON Lines; PHP, webserver, worker, scheduler
and deployment logs have separate filenames in that same directory. Staging
uses debug-level application logging while test starts at warning level. The
prepared templates and verification procedure are in `deploy/logging`.

## Current staging baseline

Version `0.18.1` is installed on staging with Nginx 1.26.3 after passing its
isolated candidate. It uses PHP-FPM 8.5.10, Laravel 13.30.1 and MariaDB 10.11.
The application, database,
public runtime files and uploads are persistent where required. The Contentify
job runner is active. Homepage, login, authenticated administrator backend,
65-table database, writable installer directories and central logs were
verified. This closes stage 2 only; it does not approve a public deployment.

Der Editor wurde zuerst im getrennten Kandidaten auf Port 8088 geprüft und
anschließend auf das produktive Staging an Port 80 übernommen. PHP, Laravel und
Bootstrap blieben dabei unverändert. Eine Public-Freigabe ist weiterhin
ausgeschlossen.

Die Node-/LESS-Stufe 0.15.0 ersetzt danach nur die historische Grunt-Baukette.
Node.js 24, npm 11 und Less 4.9.1 bauen das weiterhin auf Bootstrap 3.3.7
basierende Stylesheet reproduzierbar. Der npm-Audit sinkt von 23 auf null
bekannte Schwachstellen; PHP, Laravel, Editor und Bootstrap bleiben unverändert.
Der isolierte Kandidat bestand 22 Tests mit 74 Assertions und wurde danach aus
dem exakten Git-Stand auf Port 80 ausgerollt. Live wurden 512 Routen, beide
Smoke-Skripte, Dashboard, IP-Menülinks, Feed-Reihenfolge, beide Editoren und die
wirklich eingebundenen SunEditor-Assets geprüft. Seit dem Rollout enthalten die
Containerlogs keinen neuen Fehler. Das Kandidatensystem wurde danach beendet;
seine persistenten Volumes bleiben für eine nachvollziehbare Diagnose erhalten.

Version 0.16.0 vereinheitlicht als nächste getrennte Achse die
Bootstrap-3-Basis auf 3.4.1 und ersetzt drei externe CDN-Einbindungen durch das
versionierte lokale JavaScript. Alle vier CSS-Ziele bauen aus derselben
aktualisierten LESS-Quelle. Da Bootstrap 3.4.1 offiziell abgekündigt ist und der
Audit zwei neuere XSS-Advisories ohne 3.x-Patch meldet, ist dies nur die
kompatible Brücke für die anschließende Migration auf Bootstrap 5.3.8. Der
isolierte Kandidat bestand 22 Tests mit 74 Assertions und beide Smoke-Skripte.
Auf Staging liefern Startseite, beide CSS-Dateien, Glyphicons, Bootstrap- und
SunEditor-JavaScript HTTP 200. Dashboard-Icons, IP-Menülinks, Feed-Reihenfolge,
beide Editoren und das geöffnete und wieder geschlossene Bildermodal wurden im
Browser geprüft; das anschließende Logintervall blieb fehlerfrei.

## Target-selection rule

PHP 8.5.10 and Laravel 13.30.1 have now been reached through separately tested
rungs. They remain a staging result until the independent test system completes
a clean installation and acceptance run. Each further axis must start, pass
its characterization tests and support a clean install before the next runtime
or framework change begins.

PHP 7.4 and other end-of-life intermediate versions may be used only inside the
isolated migration systems to establish or cross a compatibility rung. They are
never approved as the final public production platform.

## Seven-stage implementation

1. **Provision separation.** Create staging and test on their own IPs, pin the
   database/runtime services, restrict network access, and establish independent
   secrets, data and backups.
2. **Make the historical baseline run.** On staging, reproduce the upstream stack
   with PHP 7.4 and Laravel 6.20.30, complete the installer, load representative
   fixtures and repair only defects that prevent normal historical operation.
3. **Freeze behavior.** Add characterization and smoke tests for all retained
   modules. Record the reproducible staging baseline, create the prerequisites
   independently on test, and prove a clean installation there.
4. **Harden within the current framework.** Update Laravel 6 and compatible
   dependencies as far as safely possible, replace the bundled Composer, remove
   immediately reachable advisories and verify the complete step on staging
   before installing that candidate cleanly on test.
5. **Cross the PHP 8 boundary.** Rename both `Match` classes and references,
   resolve PHP 8 language/runtime changes and validate the complete application.
   This change is isolated from the next Laravel-major upgrade.
6. **Raise Laravel and PHP incrementally.** Move through the necessary Laravel
   major versions and their compatible PHP runtimes one rung at a time. At every
   rung: build and diagnose on staging, then cleanly install and accept on test.
7. **Select and release the operating stack.** Stop only on a supported,
   advisory-clean combination that operates cleanly. Public release is allowed
   only after prerequisites, clean installation and representative operation on
   test finish without unresolved errors.

## Acceptance gates

- All first-party PHP files lint on the chosen supported PHP versions.
- `composer install` works without `--ignore-platform-reqs`.
- Composer and npm audits contain no unresolved production advisories.
- `npm ci` and the asset build work without `--legacy-peer-deps`.
- Tests cover real CMS behavior and pass from a clean database.
- The container stack is pinned, persistent, health-checked and contains no
  embedded credentials or world-writable application tree.
- A clean install, backup, restore and rollback are demonstrated.
- Container build ignores are anchored to repository roots so application
  directories such as `public/vendor` cannot be removed accidentally; smoke
  tests require HTTP 200 and correct MIME types for CSS, JavaScript and fonts.
- Controlled Laravel, PHP and webserver errors arrive in the correct central
  files with environment/host context and pass a real rotation test.

## Estimated effort for full functional parity

The estimate assumes that all 44 existing module directories remain in scope,
the current behavior is preserved, and the result must be suitable for an
internet-facing production deployment. One person-day is eight working hours.

| Work package | Person-days |
| --- | ---: |
| Functional inventory and characterization baseline | 10-15 |
| Reproducible test database, installer and fixtures | 8-12 |
| PHP 8 language compatibility and `Match` refactor | 6-10 |
| Incremental Laravel migration through the required major versions | 30-45 |
| Third-party package replacement and integration work | 18-30 |
| Database, installer and data-upgrade path | 10-18 |
| Front-end build replacement | 6-10 |
| Containers, CI/CD and operational configuration | 8-14 |
| Focused security hardening | 10-16 |
| Meaningful automated regression coverage | 25-40 |
| UAT, stabilization, documentation and release | 15-25 |
| **Base estimate** | **146-235** |
| **20% uncertainty reserve** | **29-47** |
| **Planned total** | **175-282 person-days** |

The planned total corresponds to approximately **1,400-2,256 working hours**.
The reserve covers undocumented behavior, packages without a direct maintained
replacement, installer/data surprises and defects revealed only after real
module tests exist. It does not cover new features or a visual redesign.

### Expected calendar duration

| Staffing | Realistic duration | Notes |
| --- | --- | --- |
| 1 experienced full-time developer | 9-14 months | High key-person and interruption risk |
| 2 experienced full-time engineers | 6-9 months | Some framework work remains sequential |
| 3-person team | 4.5-7 months | Recommended: backend lead, second full-stack engineer, QA/DevOps capacity |

A reduced MVP can only be estimated after choosing which modules to retire. A
provisional core-only scope could save roughly 30-45%, but that would explicitly
forfeit full Contentify feature parity.

### Delivery model: user and Codex working together

Codex can take primary responsibility for repository analysis, repetitive code
migration, dependency work, automated tests, container configuration and the
continuous documentation. The user remains responsible for product decisions,
credentials and infrastructure access, visual acceptance, representative data
and final production approval. This is more productive than one developer
working alone, but it is not equivalent to two autonomous full-time engineers.

For full feature parity, this model is planned at **110-175 human-equivalent
engineering days**, a reduction of roughly 30-40% from the conservative total.
Of this, the user's concentrated involvement is expected to be approximately
**20-35 days** across decisions, reviews, manual tests, infrastructure access and
acceptance. The remaining implementation and verification work is driven by
Codex in reviewable stages.

| Collaboration intensity | Expected calendar duration |
| --- | --- |
| Intensive, regular work with quick decisions and available staging/test systems | 4-6 months |
| Steady part-time collaboration with weekly reviews | 6-9 months |
| Irregular availability or delayed infrastructure/data access | 9-12+ months |

The realistic commitment for planning is therefore **six months**, with a
working range of **four to nine months**. The estimate assumes that each stage
can continue without long approval gaps and that a buildable staging server, a
rebuildable clean test server and representative data are available early.

### Baseline stabilization note - 2026-09-06 19:03 CEST

Before framework upgrades, `0.2.4` removes request-host data from the persistent
admin-navigation cache. This is a baseline bug fix and does not alter PHP,
Laravel, routes or module behavior. The fix must remain covered when proxy and
trusted-host handling are modernized in a later port stage.

### Mehrfachupload-Charakterisierung - 2026-09-06 19:37 CEST

Version `0.2.5` behebt vor jeder Framework-Anhebung den in Original-Issue
`#650` beschriebenen Team-Upload. Der gemeinsame Uploader kehrte innerhalb der
Feldschleife zurück und konnte deshalb nie mehr als ein Dateifeld bearbeiten.
Die neue Charakterisierung prüft Logo und Banner gemeinsam sowie einen Banner
bei leerem Logo. Diese Tests müssen bei jeder späteren Laravel- und PHP-Stufe
grün bleiben.

### Eingeschränkte Hosting-Umgebungen - 2026-09-06 19:55 CEST

Version `0.2.6` charakterisiert Original-Issue `#624`: optionale
Systeminformationen dürfen das Backend nicht blockieren. Die gemeinsame
Speicherplatzabfrage liefert bei Host-Beschränkungen `null`; nur ein valider
Messwert kann die Warnschwelle auslösen. Dieses Verhalten ist unabhängig vom
späteren PHP-/Laravel-Ziel und bleibt als Portierungsanforderung erhalten.

### Letzte Laravel-6-Patchstufe - 2026-09-06 20:17 CEST

Version `0.3.0` hält PHP bewusst auf 7.4 und Laravel bewusst auf Major-Version 6.
Nur der erste Modernisierungsschritt wurde ausgeführt: Laravel 6.20.30 wurde auf
6.20.45 und der dazu passend aufgelöste Lockbestand aktualisiert. Der isolierte
Kandidat bestand Composer-Validierung, Artisan-Boot, den Syntaxlauf, fünf
Unit-Tests mit 16 Assertions und beide vorhandenen Smoke-Tests. Der Auditbestand
sank von 47 auf 39 Advisories in acht Paketen. Diese Zwischenstufe bleibt wegen
PHP 7.4, Laravel 6 und der verbleibenden Findings ausschließlich intern.
App, Jobs und Nginx wurden gemeinsam neu erstellt; Startseite, Anmeldung,
Font Awesome, Glyphicons und der tatsächlich eingebundene jQuery-Pfad wurden
anschließend mit HTTP 200 geprüft. In den zentralen Logs entstanden dabei keine
neuen Anwendungs-, PHP- oder Nginx-Fehler.

### Getrennte Projektfeeds und Version 3.3-dev - 2026-09-06 20:28 CEST

Die gepflegte Bad-Hippo-Linie verwendet ab `0.4.0` die sichtbare CMS-Kennung
`3.3-dev`. Der Originalfeed von Contentify bleibt als eigene Quelle bestehen;
der neue Bad-Hippo-Feed wird getrennt bezeichnet und aus der versionierten
GitHub-Datei `public/share/feeds/cms.json` geladen. Ein Ausfall wird je Quelle
separat gecacht und protokolliert. Alle externen Felder werden vor der Ausgabe
normalisiert, damit die zusätzliche Remotequelle keine ungeprüften Links,
Iconnamen oder HTML-Inhalte in den Adminbereich einführt.
Seit `0.4.1` steht die neuere Bad-Hippo-Quelle oberhalb des historischen
Originalfeeds; die Trennung und unabhängige Fehlerbehandlung bleiben erhalten.

### Laravel-7-Migrationsstufe - 2026-09-06 20:51 CEST

Version `0.5.0` hebt ausschließlich Laravel von 6.20.45 auf 7.30.7; PHP bleibt
bewusst auf 7.4. Der Composer-Probelauf identifizierte Sentinel 3 als direkten
Blocker, weshalb Sentinel 4 gemeinsam mit Ignition 2 und Collision 4 aufgelöst
wurde. Der notwendige Anwendungscode beschränkt sich auf `Throwable` im
Exception-Handler, den Laravel-7-Standard für sichere Session-Cookies und
Integer-Rückgabecodes der drei eigenen Artisan-Befehle.

Der isolierte Kandidat startet, listet 540 Routen, besteht acht Unit-Tests mit
27 Assertions, beide bestehenden Smoke-Tests und den vollständigen Syntaxlauf.
Der Produktions-Audit sinkt von 39 Advisories in acht Paketen auf 12 in zwei
Paketen. Die bekannte Platzhalter-Featureprüfung bleibt als Testschuld erfasst;
sie scheiterte bereits vor dieser Stufe mit HTTP 404. Auf Staging laufen das
authentifizierte Dashboard, 512 aktivierte Routen, die echte Anmeldeseite, alle
geprüften Browserassets und beide Smoke-Tests. Laravel 7 ist damit als interne
Zwischenstufe angenommen, nicht als Test- oder Public-Freigabe.

### Getrennte Betriebs- und Anzeige-Logs - 2026-09-06 21:27 CEST

Version `0.5.1` verändert keine Framework- oder PHP-Version. Der Laravel-
Standardkanal ist nun ein Stack aus zwei Ausgaben: `application` bewahrt das
ausführliche tägliche JSON mit Umgebung, Host, Build und Requestkontext unter
`/var/log/contentify`; `legacy` schreibt parallel das klassische Monolog-
Textformat nach `storage/logs/laravel.log`. Nur diese zweite Datei wird von der
historischen Admin-Seite angezeigt und gelöscht. PHP-, FPM-, Nginx-, Security-
und Jobprotokolle bleiben eigenständig und sind von der Admin-Löschung nicht
erreichbar. Der Live-Test auf Staging bestätigt identische Meldungen in beiden
Ausgaben, Dateimodus `0640` mit Eigentümer `www-data:www-data` und die korrekte
Darstellung des klassischen Eintrags im vorhandenen Admin-Logviewer.

### Laravel 8 bei unverändertem PHP 7.4 - 2026-09-06 22:05 CEST

Version `0.6.0` hebt ausschließlich die Framework-Achse von Laravel 7.30.7 auf
8.83.29. Sentinel steigt auf 5.1.0, Cartalyst Support auf 5.1.2 und Collision
auf 5.11.0. Die neue Laravel-Wartungsmodus-Middleware ersetzt die entfernte
Vorgängerklasse. Contentifys eigener Übersetzer übernimmt die bisherige
Sortierung der Platzhalter nun selbst, weil Laravel 8 die dafür verwendete
geschützte Methode entfernt hat. `composer.json` begrenzt diese Stufe bewusst
auf PHP `^7.3`, damit der noch nicht kompatible PHP-8-Pfad nicht länger
fälschlich installierbar erscheint.

Der Kandidat bestand zehn Unit-Tests mit 34 Assertions, beide Smoke-Tests,
Syntaxprüfungen für 686 PHP-Dateien, 512 aktivierte Routen und den lesenden
Datenbanktest mit vier erkannten Migrationen. Auf Staging blieben Anmeldung,
bestehende Admin-Sitzung, Dashboard, Newsverwaltung, Logviewer, Icons,
Navigation und Jobrunner funktionsfähig. Der Produktions-Audit enthält noch
drei Laravel-Advisories; die Stufe ist daher weiterhin nicht public-fähig.

### PHP 8.0 bei unverändertem Laravel 8 - 2026-09-07 06:21 CEST

Version `0.7.0` ändert ausschließlich die PHP-Achse von 7.4.33 auf 8.0.30.
Die reservierten Klassen `App\Modules\Matches\Match` und
`App\Modules\Cups\Match` wurden in `GameMatch` und `CupMatch` umbenannt.
`GameMatch` setzt die Tabelle `matches` nun ausdrücklich; `CupMatch` behält
`cups_matches`. Relationen und Controller verweisen auf die neuen Symbole,
während Tabellen, URLs, Modulnamen und sichtbare Übersetzungen unverändert
bleiben. `composer.json` und der Lockbestand verlangen ab dieser Stufe PHP 8.

Der Umbau bestand zuerst unter PHP 7.4 und danach unter PHP 8.0.30 jeweils 688
Syntaxprüfungen sowie zwölf Unit-Tests mit 42 Assertions. Der PHP-8-Kandidat
bestand außerdem Composer-Validierung und Plattformprüfung, Artisan-Boot,
512 Routen, vier erkannte Migrationen, beide Smoke-Tests und Modellabfragen an
der Staging-Datenbank. Nach dem gemeinsamen Austausch von App, Jobs und Nginx
antworten Startseite, Anmeldung und geprüfte Assets mit HTTP 200. Die bestehende
Admin-Sitzung öffnet Dashboard, Matches, Cups und den Logviewer fehlerfrei.
PHP 8.0 ist selbst abgekündigt und daher nur eine interne Brücke; als nächste
getrennte Stufe folgt Laravel 9, nicht die Public-Freigabe.

### Request-IP-Fix 0.7.1 - 2026-09-07 06:44 CEST

Der isolierte Testserver deckte einen bereits im Original vorhandenen
Umgebungsfehler auf: `getenv('REMOTE_ADDR')` lieferte dort keinen Wert. Der
echte Nginx/FPM-Pfad war nicht betroffen. Besucherstatistik, Kontaktformular
und Bewerbungsformular verwenden nun dennoch einheitlich Laravels Request-IP.
Dieser Fix verändert weder PHP- noch Laravel-Version und wird deshalb als
eigener Fixstand geführt.

### PHP-8.5-Meilenstein 0.8.0 - 2026-09-07 07:35 CEST

PHP wurde entsprechend der festgelegten Zielversion direkt von 8.0.30 auf
8.5.10 angehoben; es gibt keine Freigabestufe auf 8.1 bis 8.4. Laravel bleibt
für diese isolierte Achse unverändert auf 8.83.29. Die Containerbasis wechselt
vom offiziellen PHP-FPM-Bullseye- zum gepinnten PHP-8.5-FPM-Bookworm-Abbild;
Composer steigt von 2.2.25 auf 2.10.3. OPcache ist im Basisabbild bereits
enthalten und wird deshalb nicht erneut kompiliert.

Die erste reguläre Abhängigkeitsauflösung deckte veraltete PHP-Obergrenzen in
`nette/schema` und `nette/utils` auf. Die kleinsten kompatiblen Aktualisierungen
auf 1.3.6 und 4.1.5 wurden in den Lockbestand übernommen. Contentifys eigene
implizit-nullbare Parameter, SimpleXML-Überschreibungen und die alte PDO-MySQL-
Konstante wurden an PHP 8.5 angepasst. 735 First-Party-Dateien laufen ohne
Deprecation, zwölf Unit-Tests mit 42 Assertions sowie Plattform-, Datenbank-
und HTTP-Prüfungen bestehen.

Laravel 8 selbst erzeugt auf PHP 8.5 weiterhin Deprecations. Diese Hinweise
bleiben in den ausführlichen zentralen und klassischen Logs erhalten. Sie sind
kein Anlass, Vendor-Dateien zu patchen oder Meldungen zu unterdrücken, sondern
die messbare Ausgangslage für die nächste getrennte Laravel-Stufe. Drei
Laravel-Advisories verhindern weiterhin eine Public-Freigabe.

### Laravel-9-Migrationsstufe 0.9.0 - 2026-09-07 08:30 CEST

Laravel wurde bei unverändertem PHP 8.5.10 von 8.83.29 auf die letzte stabile
9.x-Ausgabe 9.52.21 angehoben. Sentinel wechselte auf 6.0.1, Collision auf
6.4.0 und Facade Ignition wurde entsprechend Laravels Upgradepfad durch Spatie
Laravel Ignition ersetzt. Die externe Fideloper-Proxy-Middleware entfällt
zugunsten der Framework-Implementierung. Laravel 9 ersetzt außerdem SwiftMailer
durch Symfony Mailer und Flysystem 1 durch Flysystem 3; beide Konfigurationen
wurden aktualisiert und akzeptieren vorerst weiterhin die historischen
Umgebungsvariablen als Rückfallwert.

Die letzte Veröffentlichung von `caffeinated/modules` unterstützt Composer-
seitig nur Illuminate 6 bis 8. Da Contentifys 44 Module an genau dieser API
hängen, wurde v6.3.1 unter Beibehaltung der MIT-Lizenz als lokale Version 6.3.2
übernommen. Nur PHP- und Illuminate-Anforderung wurden erweitert; der Paketcode
blieb unverändert. Herkunft und Abgrenzung stehen in
`packages/caffeinated-modules/BAD_HIPPO.md`. Damit bleibt die Framework-Stufe
klein und ein möglicher späterer Austausch des Modulsystems separat.

Der Kandidat bestand 781 Syntaxprüfungen, zwölf Unit-Tests mit 42 Assertions,
beide Smoke-Tests, 515 Routen, vier Migrationen und Datenbankabfragen über
GameMatch und CupMatch. Dashboard, Matches, Cups, Logs, News und Module wurden
mit der bestehenden Admin-Sitzung geprüft. Der Produktions-Audit meldet vier
Advisories in Laravel 9.52.21 und drei aufgegebene Pakete. Composer 2.10 musste
die bekannte Laravel-9-Stufe beim Erzeugen des Lockbestands einmal mit
`--no-blocking` zulassen; der Audit bleibt aktiv und Public bleibt gesperrt.

### Laravel-10-Migrationsstufe 0.10.0 - 2026-09-07 09:45 CEST

Laravel wurde bei unverändertem PHP 8.5.10 von 9.52.21 auf die stabile Ausgabe
10.50.3 angehoben. Entsprechend dem offiziellen Upgradepfad wechseln Sentinel
auf 7.0.2, Collision auf 7.12.0, Ignition auf 2.9.1 und PHPUnit auf 10.5.64.
Die Composer-Mindeststabilität ist nun `stable`; der Lockbestand hält trotzdem
bewusst exakt Laravel 10.50.3 fest.

Laravel 10 entfernt Eloquent `$dates`. Alle 40 Contentify-Modelle wurden daher
auf explizite `datetime`-Casts umgestellt. Ein neuer Regressionstest prüft die
kritischen Zeitfelder in Matches, Cups und News. Der alte Feature-Platzhalter
charakterisiert jetzt korrekt die frische Installation: `/` leitet auf
`/install.php` weiter.

`invisnik/laravel-steam-auth` endet upstream bei Illuminate 9. Weil Contentify
den Steam-Login direkt verwendet, liegt der exakte MIT-lizenzierte Stand 4.4.0
nun als lokale Kompatibilitätsversion 4.4.1 im Projekt. Nur seine Composer-
Grenzen wurden auf PHP 8.5 und Laravel 10 erweitert; der Paketcode blieb
unverändert. Wie bei der Modulbrücke ist das ein kontrollierter Zwischenschritt
und kein dauerhafter Ersatz für eine gepflegte Abhängigkeit.

Der isolierte Kandidat bestand 792 Syntaxprüfungen, 14 Tests mit 47 Assertions,
515 Routen, vier Migrationen, Datenbankabfragen, beide Smoke-Tests und das
Dual-Logging. Dashboard, Matches, Cups, Logviewer, News und Module wurden im
Browser geprüft; es traten keine Konsolen- oder Laravel-Fehler auf. Der Audit
meldet noch drei Laravel-Advisories und zwei offiziell aufgegebene Pakete.
Public bleibt gesperrt; die nächste getrennte Stufe ist Laravel 11.

### Laravel-11-Migrationsstufe 0.11.0 - 2026-09-07 10:26 CEST

Laravel wird bei unverändertem PHP 8.5.10 auf 11.56.1 angehoben. Sentinel 8,
Collision 8.5 und Carbon 3 folgen den neuen Verträgen. Die von Laravel 11
angebotene schlanke Anwendungsstruktur wird bewusst nicht übernommen: Der
offizielle Upgradepfad unterstützt die bestehende Laravel-10-Struktur, und
Contentify benötigt seinen vollständigen Konfigurationsbaum weiterhin.

`laravelcollective/html` endet upstream bei Illuminate 10. Sein MIT-lizenzierter
Stand liegt für diese Stufe als lokale Version 6.4.2 vor; die Composer-Grenzen
erlauben Illuminate 11 und zwei Konstruktorsignaturen sind explizit PHP-8.5-
kompatibel. Ebenso werden die bereits
dokumentierten Modul- und Steam-Brücken ohne Quellcodeänderung auf Laravel 11
fortgeführt. Diese drei Brücken bleiben getrennte technische Schulden.

Carbon 3 liefert gerichtete und gegebenenfalls nicht-ganzzahlige `diffIn*`-
Ergebnisse. Contentifys einziges entsprechendes Anzeigeprädikat im Forum wird
explizit richtungsunabhängig ausgewertet. Die eigene Carbon-Unterklasse greift
nicht mehr auf die entfernte statische `$toStringFormat`-Eigenschaft zu, sondern
verwendet das übersetzte Contentify-Datumsformat direkt. Der frische Installationstest
entfernt seinen Installationsmarker bereits vor dem Framework-Bootstrap und
prüft die Installer-Route unabhängig vom gemounteten Staging-Speicher.

Der Produktions-Audit meldet weiterhin drei Framework-Advisories. Composer
markiert nur noch Less.php als aufgegeben, weil Collective HTML nun als
dokumentierte lokale Brücke aufgelöst wird. Public bleibt gesperrt; die nächste
getrennte Framework-Stufe ist Laravel 12.

Der Browserlauf prüft zusätzlich die Erstellformulare. Dabei wurde der bereits
unter Laravel 10 vorhandene falsche Match-Viewname aus der früheren PHP-8-
Modellumbenennung gefunden und durch einen expliziten `admin_form`-Vertrag mit
Regressionstest behoben.

Der finale Kandidat besteht 801 Syntaxprüfungen, 17 Tests mit 52 Assertions,
512 aktive Produktionsrouten, vier Migrationen, beide Smoke-Tests, Datenbank-
und Dienstauflösung sowie den authentifizierten Browserlauf. Im abschließenden
frischen Logfenster entstehen keine neuen Anwendung-, PHP- oder Nginx-Fehler.

Version `0.11.1` härtet diese Stufe betrieblich ab: Der App-Container und alle
Laravel-CLI-Prüfungen laufen als `www-data`. Zentrale Logdateien können damit
nicht mehr durch einen Root-CLI-Lauf für PHP-FPM unbeschreibbar werden.

### Laravel-12-Migrationsstufe 0.12.0 - 2026-09-07 12:10 CEST

Laravel wird bei unverändertem PHP 8.5.10 von 11.56.1 auf 12.69.1 angehoben.
Sentinel 9.0.0 unterstützt Illuminate 12 offiziell; PHPUnit steigt entsprechend
dem offiziellen Upgradepfad auf 11.5.56. Die lokalen Brücken für Module,
Steam-OpenID und Laravel Collective HTML erhalten nur neue Illuminate-12-
Metadaten und Patchversionen. Ihr PHP-Quellcode bleibt gegenüber 0.11.1
unverändert.

Die dokumentierten Laravel-12-Änderungen wurden einzeln abgeglichen. Contentify
verwendet keine UUID-Traits, keine eigenen Datenbank-Grammatiken, keine
`mergeIfMissing()`-Dot-Schlüssel und keine Concurrency-Ergebnismappen. Der
lokale Datenträger besitzt bereits ausdrücklich `storage/app` als Wurzel und
wird zusätzlich per Regressionstest festgehalten. SVG-Dateien laufen über
Contentifys eigenen Uploader statt über Laravels geänderte `image`-Regel.
Der einzige doppelte exakte Routenname `admin.` wird nicht zur URL-Erzeugung
verwendet; alle konkreten `admin.<bereich>.*`-Namen und das vollständige
Adminmenü funktionieren im Kandidaten.

Der Kandidat besteht 802 Syntaxprüfungen, 18 Tests mit 54 Assertions, 512
Produktionsrouten, vier Migrationen, beide Smoke-Tests, Match-/Cup-
Datenbankzugriffe, Sentinel- und Steam-Auflösung, Dual-Logging sowie alle 36
Adminbereiche. Das absichtlich geöffnete Matchformular protokolliert nur die
bekannte fachliche Voraussetzung eines fehlenden Teams. Zwei fehlende
Spielbildaufrufe reproduzieren auch auf 0.11.1 den offenen BUG-017 für nicht
vorhandene statische Dateien und sind keine Laravel-12-Regression.

Der Composer-Audit meldet erstmals keine bekannte Sicherheitslücke. Das
aufgegebene `oyejorge/less.php` bleibt als getrennte Frontend-Aufgabe offen.
Public bleibt bis zur unabhängigen sauberen Testinstallation gesperrt.

### Laravel-12-Laufzeitfix 0.12.1 - 2026-09-07 12:42 CEST

Die authentifizierte Live-Browserrunde deckte nach dem technischen Rollout noch
Contentifys eigenen `BaseController::callAction()` auf. Anders als Laravels
Controller reichte er das assoziative Parameterarray unverändert an PHP weiter.
Unter PHP 8.5 wurden Routenschlüssel dadurch zu benannten Argumenten und Routen
mit `user` oder `slug` konnten bei anders benannten Methodensignaturen scheitern.
0.12.1 übergibt bewusst `array_values($parameters)` und entspricht damit dem
Laravel-12-Dispatcher. Der neue Regressionstest erhöht den Stand auf 19 Tests
mit 55 Assertions; alle 36 Adminbereiche und das Benutzerprofil wurden erneut
im isolierten Kandidaten geöffnet.

### Laravel-13-Migrationsstufe 0.13.0 - 2026-09-07 13:49 CEST

Laravel wird bei unverändertem PHP 8.5.10 auf 13.30.1 angehoben. Sentinel 10,
Tinker 3 und PHPUnit 12 bilden die dazu passenden offiziellen Paketstufen. Die
drei lokalen Brücken erhalten ausschließlich Illuminate-13-Verträge; ihr
PHP-Paketcode bleibt gegenüber 0.12.1 unverändert.

Der Laravel-13-Leitfaden wurde vollständig gegen Contentify geprüft. Das
Validierungsmodell registriert seinen Watson-Observer direkt, weil verschachtelte
Modellinstanzen während des Bootens nicht mehr zulässig sind. Die eigene CSRF-
Middleware delegiert an `PreventRequestForgery`, damit Origin-, Header-, Cookie-
und Tokenprüfung des Frameworks gelten; Contentifys Spamsperre bleibt erhalten.
Bestehende Feed-Caches dürfen gezielt `stdClass` deserialisieren, während alle
anderen Objektklassen gesperrt bleiben. Das PHP-Sitzungsformat wird explizit
beibehalten, damit der Framework-Sprung nicht gleichzeitig alle Sitzungen
ungültig macht. Cache-/Session-Fallbacknamen, Upsert-Schlüssel, Domainrouten,
Queue-Ereignisse, Manager-Closures und alte Pagination-Viewnamen wurden gesucht;
Contentify besitzt dort keine weitere betroffene eigene Implementierung.

Der finale Kandidat besteht 802 Syntaxprüfungen, PHPUnit 12.5.34 mit 19 Tests
und 58 Assertions, 512 aktive Routen, vier Migrationen, beide Smoke-Tests,
Sentinel- und Steam-Auflösung, Ab- und Anmeldung, Dual-Logging und alle 36
Adminbereiche. `composer audit --locked` meldet keine bekannte Schwachstelle;
nur das aufgegebene Less.php bleibt als getrennte Frontend-Aufgabe. Public bleibt
bis zur sauberen Installation auf dem unabhängigen Testsystem gesperrt.

Die Live-Abnahme fand anschließend einen veralteten Raw-GitHub-CDN-Treffer für
den Bad-Hippo-Feed. Version 0.13.0 kennzeichnet Feed-URL und Anwendungscache neu;
der Laravel-13-Eintrag wird dadurch unmittelbar als erste Meldung geladen.

### Editor-Migrationsstufe 0.14.0 - 2026-09-07 15:58 CEST

CKEditor 4.3.1 wird vollständig entfernt. Ein Update innerhalb CKEditor 4 wäre
außerhalb des kommerziellen LTS-Zweigs weiterhin abgekündigt und unsicher;
CKEditor 5 würde die MIT-Verteilung dieses Forks mit GPL-2+- beziehungsweise
kommerziellen Lizenzbedingungen belasten. Der exakt festgeschriebene SunEditor
3.3.2 ist MIT-lizenziert, ohne Laufzeitabhängigkeiten und wird deshalb als
separate Editorstufe eingesetzt.

Ein gemeinsamer Contentify-Adapter initialisiert jedes `textarea.editor`, trennt
Desktop- und Mobil-Werkzeugleisten und bindet die bestehenden Endpunkte für
Bilder, Vorlagen und Länderflaggen ein. Er synchronisiert den HTML-Wert auch bei
geöffnetem Quelltextmodus und entfernt ausschließlich SunEditor-interne
Metadaten vor dem Speichern. Der isolierte Kandidat bestand 21 Unit-Tests mit
71 Assertions, die beiden Smoke-Tests, 515 Routen und im echten Browser das
Erstellen und erneute Bearbeiten einer News mit Umlauten, Quelltext und Flaggen.

Der anschließende Staging-Rollout auf Port 80 behielt Datenbank, Uploads,
Anwendungsschlüssel und Logs. Homepage und die drei neuen Editor-Assets liefern
HTTP 200, der entfernte CKEditor-Pfad HTTP 404. Beide Smoke-Tests, 512 auf diesem
Modulbestand aktive Routen, PHP 8.5.10, Laravel 13.30.1, der echte Admin-
Editor und die neue oberste Bad-Hippo-Feedmeldung wurden erneut geprüft.

### Node-/LESS-Stufe 0.15.0 bis 0.15.3 - 2026-09-07 18:33 CEST

Grunt und seine alten LESS-/Watch-Plugins werden entfernt. Less 4.9.1 baut die
vorhandene LESS-Einstiegsdatei direkt, ein kleines Node-24-Skript übernimmt den
Watch-Modus. Die explizite URL-Umschreibung bewahrt die funktionierenden
Glyphicons-Pfade; der Google-Font-Import bleibt netzunabhängig im erzeugten CSS.
Normaler Clean-Install, Audit, Build, Test und Watch-Probe bestehen ohne
Legacy-Auflösung, zwei Builds sind bytegleich. Bootstrap bleibt auf 3.3.7 und
wird erst in einer eigenen folgenden Stufe bearbeitet.

Der finale Kandidat bestand 22 Tests mit 74 Assertions, 515 Kandidatenrouten,
beide Smoke-Skripte und alle Frontend-Verträge. Danach wurde Commit
`0a4e094826b9f091c1a4de57c600b12b77e38ad3` als `0.15.3` auf Staging
installiert. Dort bestehen 512 modulabhängige Routen, beide Smoke-Skripte und
die echten HTTP-/Browserprüfungen; der entfernte CKEditor bleibt HTTP 404.

## Non-viable shortcut

Running the unveränderten upstream code with `--ignore-platform-reqs` is not a
port. It only bypasses dependency checks; upstream PHP 8 still cannot parse its
`Match` declarations. Bad Hippo `0.7.0` repairs that boundary explicitly and
installs without ignored platform requirements, while the remaining vulnerable
Laravel packages still prevent a public release.

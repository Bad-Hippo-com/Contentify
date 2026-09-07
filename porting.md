# Contentify porting plan

Local workstream version: **0.8.0**
Last updated: **2026-09-07 07:35 CEST**

## Decision

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

Version `0.8.0` is installed on staging with Nginx 1.26.3,
PHP-FPM 8.5.10, Laravel 8.83.29 and MariaDB 10.11. The application, database,
public runtime files and uploads are persistent where required. The Contentify
job runner is active. Homepage, login, authenticated administrator backend,
65-table database, writable installer directories and central logs were
verified. This closes stage 2 only; it does not approve a public deployment.

## Target-selection rule

There is deliberately **no fixed immediate target of PHP 8.5 and Laravel 13**.
The final stack will be the newest combination that is supported, secure and
demonstrably compatible after the staged migration. Each rung must start, pass
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

## Non-viable shortcut

Running the unveränderten upstream code with `--ignore-platform-reqs` is not a
port. It only bypasses dependency checks; upstream PHP 8 still cannot parse its
`Match` declarations. Bad Hippo `0.7.0` repairs that boundary explicitly and
installs without ignored platform requirements, while the remaining vulnerable
Laravel packages still prevent a public release.

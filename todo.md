# Contentify modernization backlog

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

Local workstream version: **0.18.3**
Last updated: **2026-09-08 08:56 CEST**

## Nächste Arbeitsreihenfolge

1. Offene Fehler reproduzieren, sortieren und zuerst den Originalumfang stabilisieren.
2. Nach Bootstrap 5 verbleibende LESS-Referenzadapter, Glyphicons, jQuery 2.2.4
   und manuell vendorte Plugins inventarisieren; BUG-043 und BUG-046 abarbeiten.
3. Container-Build und Veröffentlichung über GitHub Container Registry vorbereiten.
4. Einen unabhängigen Testserver installieren und alle Abläufe dort wiederholen.
5. BUG-020 im Cup-Siegerablauf reproduzieren, mit einem Regressionstest
   absichern und getrennt vom PHP-8-Port beheben.

## Completed

- [x] Profiltabellen und Nachrichten an dunkle Themefarben anpassen, inklusive
  Bootstrap-5-Zellfarben und Hover; als 0.18.2 auf Staging visuell abgenommen.
- [x] Bootstrap 5.3.8 als 0.18.1 auf Staging ausrollen und auf GitHub main
  übernehmen; native APIs, reproduzierbare Builds, 23 Tests / 83 Assertions,
  beide Smoke-Skripte und Browserabnahme bestanden (2026-09-08 08:26 CEST).
- [x] Bootstrap 4.6.2 als Version 0.17.2 auf Staging übernehmen: 23 Tests / 83
  Assertions, 35 Admin-Menüziele, beide Themes und mobile Darstellung geprüft;
  PHP-LESS-Compiler, Dateirechte und Glyphicons-Pfade korrigiert (2026-09-08).
- [x] `0.16.0` im getrennten Kandidaten mit 22 Tests und 74 Assertions sowie
  beiden Smoke-Skripten prüfen, danach auf Staging ausrollen und Homepage,
  Admin-Icons, IP-Links, Feed, beide Editoren, Bootstrap-Bildermodal, Assets
  und frische zentrale Logs abnehmen.
- [x] Gemischten Bootstrap-Bestand aus LESS 3.3.3 und CDN-JavaScript 3.3.1 auf
  lokale Version 3.4.1 vereinheitlichen; Backend, Morpheus, Phobos und aktives
  Frontend reproduzierbar bauen und verbleibende XSS-Advisories als BUG-041
  erfassen (`0.16.0`).
- [x] PHPUnit auf installierten Kandidaten vom Betriebsmarker und der realen
  MariaDB entkoppeln (`0.15.3`, BUG-040).
- [x] `0.15.3` nach 22 Tests mit 74 Assertions im isolierten Kandidaten auf
  Staging ausrollen; 512 Live-Routen, beide Smoke-Skripte, echte Editor-Assets,
  Dashboard-Icons, IP-Menülinks, Feed-Reihenfolge und frische Logs prüfen.
- [x] Historischen npm-/Grunt-Baum durch Less 4.9.1 und einen Node-24-Watcher
  ersetzen; `npm ci`, reproduzierbaren Build und Audit mit null bekannten
  Schwachstellen nachweisen (`0.15.0`).
- [x] CKEditor 4.3.1 vollständig durch SunEditor 3.3.2 unter MIT ersetzen,
  Contentifys Sonderfunktionen anbinden und News-Erstellen/-Bearbeiten im
  isolierten Staging-Browser prüfen (`0.14.0`).

- [x] Öffentlichen Fork `Bad-Hippo-com/Contentify` erstellen und den ursprünglichen
  Maintainer sowie den Community-Status sichtbar und respektvoll kennzeichnen.
- [x] Deutsche GitHub-Beschreibung, Themen, Issue-/PR-Vorlagen und Hinweise zur
  vertraulichen Meldung von Sicherheitslücken veröffentlichen.
- [x] Eigene Arbeits-Issues für Upstream-Triage, zweiten Feed, GHCR-Container
  und den späteren PHP-8-Blocker anlegen.
- [x] Ersten Upstream-Block `#645`, `#663`, `#658`, `#613` und `#614` prüfen
  und die Ergebnisse transparent in den Original-Issues veröffentlichen.
- [x] Original-Issue `#650` auf die vorzeitig beendete Upload-Schleife
  zurückführen, mit zwei Regressionstests beheben und auf Staging prüfen.
- [x] Original-Issue `#624` durch eine sichere, gemeinsame Speicherplatzabfrage
  beheben; eingeschränkte Pfade als unbekannt statt als Ausnahme behandeln.
- [ ] Verbleibende 25 Original-Issues einzeln prüfen und nur mit nachweisbaren
  Ergebnissen beantworten.
- [x] Clone the official `Contentify/Contentify` repository.
- [x] Confirm default branch, tags, upstream commit and release state.
- [x] Validate all 726 project PHP files with PHP 8.5.10 and PHP 7.4.33.
- [x] Test Composer validation, dependency resolution and security advisories.
- [x] Test npm dependency resolution, audit results and the legacy Grunt build.
- [x] Run the available PHPUnit suite on PHP 7.4 and PHP 8.5.
- [x] Review Docker, Compose, environment defaults, CI and support policy.
- [x] Record feasibility, defects and a migration route.
- [x] Calculate full-parity effort, risk reserve and calendar duration.
- [x] Calculate the delivery model for direct user and Codex collaboration.
- [x] Adopt a gated staging-to-test-to-public strategy instead of a direct jump.
- [x] Add centralized daily JSON logging for Laravel with retention, severity,
  environment, host and request context.
- [x] Prepare PHP-FPM, Nginx, Apache, Queue, Scheduler and logrotate templates
  under `deploy/logging`.
- [x] Verify Laravel log creation with PHP 7.4 and Laravel 6.20.30.
- [x] Provision the internal staging host with Docker and Compose on Debian 13.
- [x] Install the historical PHP 7.4/Laravel 6.20.30 baseline behind Nginx.
- [x] Install MariaDB 10.11 with a persistent volume and healthy startup gate.
- [x] Complete the Contentify CLI installer and create 65 application tables.
- [x] Verify homepage, login page, administrator login and backend over HTTP.
- [x] Run Contentify jobs continuously and store their output centrally.
- [x] Verify central Laravel JSON, PHP-FPM and Nginx logs on staging.
- [x] Install and dry-run 30-day log rotation on staging.
- [x] Diagnose the first administrator login failure: active IP/user throttle
  plus Docker Compose changing a two-dollar-sign password to one dollar sign.
- [x] Reset the staging administrator to the exact requested password, remove
  only its diagnosed user/client-IP throttles, and verify the admin dashboard
  through a real browser session.
- [x] Record the newly confirmed internal-hostname leak in cached admin links.
- [x] Compare the live icon failure with the repository, image contents and all
  current upstream issue titles; identify `public/vendor` exclusion as its cause.
- [x] Re-include all 272 tracked `public/vendor` files in the Docker build,
  synchronize the existing public volume and rebuild app/Nginx as `0.2.3`.
- [x] Restore the five expected Bootstrap 3.3.7 Glyphicons formats and verify
  their HTTP status, MIME types and visible browser rendering.

## Required before any public or production deployment

- [x] Fork the project and establish a maintained default branch.
- [ ] Record the dedicated test-system address in the private operations inventory.
- [ ] Install and verify `/var/log/contentify` independently on test; staging is complete.
- [ ] Trigger one controlled Laravel, PHP and webserver error on each host and
  confirm file ownership, JSON parsing, rotation and 30-day retention.
- [ ] Build both systems independently with pinned services and no shared
  application database, uploads, cache, session storage or secrets.
- [ ] Correct secret transport so special characters survive Compose unchanged
  during every future clean installation.
- [ ] Add an installation smoke test that verifies the exact supplied password
  through Contentify/Sentinel without printing or storing the secret.
- [x] Identify the cached admin-menu host leak and replace its absolute cached
  root with a per-request base URL in `0.2.4`.
- [x] Recreate the internal-host cache condition and browser-test News, Pages
  and Configuration through the staging IP.
- [x] Process every configured model upload field and verify team logo plus
  banner as well as a banner without a logo in `0.2.5`.
- [x] Record the temporary Nginx 502 after replacing the app container and add
  mandatory Nginx recreation to the staging rollout procedure.
- [x] Preserve low-disk warnings while making unavailable disk-space data safe
  for restricted hosts in `0.2.6`.
- [x] Regenerate `composer.lock` on PHP 7.4, update Laravel within major version
  6 from 6.20.30 to 6.20.45, and verify the exact dependency candidate in an
  isolated container as `0.3.0`.
- [x] Narrow `.dockerignore` so the Composer dependency tree stays excluded
  while every required `public/vendor` resource remains in the image.
- [x] Restore the Glyphicons font output expected below `public/css/fonts`.
- [ ] Automate the verified CSS, JavaScript and font probes in release smoke tests.
- [x] Bring the unchanged historical application up on staging using PHP
  7.4/Laravel 6 only as a temporary compatibility baseline.
- [x] Repair installer/startup defects until the full historical workflow is
  reproducible on staging.
- [ ] Add characterization tests before changing PHP or Laravel.
- [x] Rename both `Match` model classes and all references before crossing from
  PHP 7.4 to PHP 8.
- [x] PHP und Laravel in kleinen, einzeln geprüften Stufen bis PHP 8.5.10 und
  Laravel 13.30.1 aktualisieren; Bootstrap und Node.js bleiben eigene Achsen.
- [x] Regenerate `composer.lock` and make `composer validate --strict` pass.
- [x] Raise the maintained CMS development identifier to `3.3-dev` and add a
  separately labelled Bad-Hippo dashboard feed linked to our GitHub repository
  without replacing Chris' original Contentify feed.
- [x] Place the newer Bad-Hippo feed above the historical original feed in
  dashboard display order.
- [x] Raise Laravel in one isolated rung from 6.20.45 to 7.30.7 while keeping
  PHP 7.4 unchanged; update Sentinel and the required development helpers,
  then verify Artisan boot, 540 routes, eight unit tests, both smoke tests and
  the complete first-party syntax pass.
- [x] Raise Laravel in the next isolated rung from 7.30.7 to 8.83.29 while
  keeping PHP 7.4 unchanged; update Sentinel and Collision, replace the removed
  maintenance middleware and preserve the custom translator behavior (BUG-019).
- [x] Raise only PHP from 7.4.33 to 8.0.30 in `0.7.0`; rename the reserved
  models to `GameMatch` and `CupMatch`, preserve their tables and routes, then
  verify Composer, 688 syntax checks, twelve unit tests, both smoke tests,
  512 routes, live Nginx/FPM, database access and authenticated admin pages.
- [x] PHP in `0.8.0` direkt und ausschließlich auf 8.5.10 abschließen;
  Composer 2.10.3 sowie kompatible Nette-Versionen verwenden, alle eigenen
  PHP-8.5-Deprecations beseitigen und 735 Syntaxprüfungen, zwölf Unit-Tests mit
  42 Assertions, Plattform-, Datenbank-, Nginx- und HTTP-Prüfungen bestehen.
- [x] Laravel in `0.9.0` getrennt von 8.83.29 auf 9.52.21 anheben; Sentinel,
  Collision, Ignition, Proxy, Mailer und Flysystem anpassen, die zentrale
  Caffeinated-Modulverwaltung kontrolliert übernehmen und 781 Syntaxprüfungen,
  zwölf Unit-Tests, beide Smokes sowie fünf Adminbereiche bestehen.
- [x] Laravel 9 in `0.10.0` getrennt auf Laravel 10.50.3 anheben; Sentinel,
  Collision, Ignition und PHPUnit aktualisieren, 40 entfernte `$dates`-
  Definitionen migrieren und die Steam-Authentifizierung kontrolliert lokal
  weiterführen. Der Audit sinkt von vier auf drei Framework-Advisories.
- [x] Laravel 10 in `0.11.0` getrennt auf Laravel 11.56.1 anheben; Sentinel 8,
  Collision 8 und Carbon 3 aktualisieren, die vollständige Contentify-
  Konfiguration beibehalten und Laravel Collective HTML kontrolliert lokal
  weiterführen.
- [x] Laravel 11 in `0.12.0` getrennt auf Laravel 12.69.1 anheben, Sentinel 9
  und PHPUnit 11 verwenden, alle 36 Adminbereiche prüfen und die drei letzten
  Framework-Advisories vollständig beseitigen.
- [x] Contentifys Controller-Aufruf in `0.12.1` an Laravels positionsbasierte
  Übergabe anpassen und benannte PHP-Argumentfehler für `user`/`slug` mit
  Regressionstest und Browserrunde beseitigen (BUG-034).
- [x] Laravel in `0.13.0` getrennt auf 13.30.1, Sentinel auf 10 und PHPUnit auf
  12 anheben; Modellboot, CSRF/Origin-Prüfung, Cache-Deserialisierung und
  Sitzungsformat kompatibel absichern und alle 36 Adminbereiche prüfen.
- [x] Besucher-, Kontakt- und Bewerbungs-IP in `0.7.1` über Laravels Request
  statt über die unter PHP-FPM unzuverlässige Prozessumgebung beziehen
  (BUG-021); wiederholte anonyme HTTP-Aufrufe auf Staging prüfen.
- [x] Replace the pre-existing placeholder feature test with an installation-
  aware HTTP characterization; a fresh system must redirect `/` to
  `/install.php`.
- [ ] Add a focused exception-handler regression and return HTTP 404 rather
  than 500 for unknown routes and absent static files (BUG-017).
- [x] Split ordinary Laravel logging into the detailed daily JSON operations
  log and a classic `storage/logs/laravel.log` display copy, restoring
  `/admin/config/log` without exposing central component logs to its delete
  action; verify both outputs and the authenticated browser view on staging
  (BUG-018).
- [x] Den Composer-Produktionsaudit in `0.12.0` von drei Advisories auf null
  bekannte Sicherheitslücken reduzieren; Less.php bleibt separat als
  aufgegebenes Paket erfasst.
- [x] Replace or fully upgrade the Grunt/LESS toolchain; make plain `npm ci`
  succeed without legacy dependency resolution and make `npm audit` clean.
- [ ] Replace the container definitions with pinned supported images,
  least-privilege permissions, secrets, volumes and health checks.
- [ ] Build an automated installer/database test fixture.
- [ ] Add tests for authentication, roles, every public write route, uploads,
  comments, matches, cups, themes and administrative CRUD.
- [ ] Add CI for PHP lint, coding style, PHPUnit, Composer audit, npm build/audit
  and container build.
- [ ] Perform a focused application-security review after the framework port.
- [ ] Rebuild the separate test system for every release candidate, create all
  prerequisites there, and validate a clean install plus representative use.
- [ ] Approve Public only after the complete test installation remains error-free.

## Optional improvements after the blockers

- [ ] Replace hand-written installation state with repeatable migrations and
  seeders where practical.
- [ ] Define backup, restore, scheduler, queue, mail and log-retention runbooks.
- [ ] Refresh end-user and operator documentation.
- [ ] Decide whether all 44 modules remain in scope or should be retired.

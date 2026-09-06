# Contentify modernization backlog

Local workstream version: **0.4.0**
Last updated: **2026-09-06 20:28 CEST**

## Nächste Arbeitsreihenfolge

1. Offene Fehler reproduzieren, sortieren und zuerst den Originalumfang stabilisieren.
2. Container-Build und Veröffentlichung über GitHub Container Registry vorbereiten.
3. Einen unabhängigen Testserver installieren und alle Abläufe dort wiederholen.
4. PHP, Laravel, Bootstrap und Node.js weiterhin einzeln aktualisieren.

## Completed

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
- [ ] Rename both `Match` model classes and all references before crossing from
  PHP 7.4 to PHP 8.
- [ ] Upgrade Composer packages, PHP and Laravel in small, separately tested
  steps; determine the final supported stack from measured compatibility rather
  than selecting PHP 8.5/Laravel 13 in advance.
- [x] Regenerate `composer.lock` and make `composer validate --strict` pass.
- [x] Raise the maintained CMS development identifier to `3.3-dev` and add a
  separately labelled Bad-Hippo dashboard feed linked to our GitHub repository
  without replacing Chris' original Contentify feed.
- [ ] Reduce the remaining Composer production audit from 39 advisories to zero
  through the following isolated Laravel/PHP migration rungs.
- [ ] Replace or fully upgrade the Grunt/LESS toolchain; make plain `npm ci`
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

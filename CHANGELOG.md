## Bad Hippo 0.10.0 / Contentify 3.3-dev - 2026-09-07

- Laravel als getrennte Migrationsachse von 9.52.21 auf die stabile Version
  10.50.3 angehoben; PHP bleibt unverändert auf 8.5.10.
- Sentinel auf 7.0.2, Collision auf 7.12.0, Ignition auf 2.9.1 und PHPUnit auf
  10.5.64 aktualisiert; Composer-Mindeststabilität auf `stable` gesetzt.
- Alle 40 von Laravel 10 nicht mehr unterstützten Eloquent-`$dates`-
  Definitionen auf explizite `datetime`-Casts umgestellt und mit einem
  Regressionstest für Match-, Cup- und Newsdaten abgesichert.
- Das aufgegebene Steam-Auth-Paket 4.4.0 als dokumentierte MIT-
  Kompatibilitätskopie 4.4.1 für Laravel 10 übernommen; Paketquellcode
  unverändert gelassen.
- Installationsbewussten HTTP-Test ergänzt und PHPUnit-Konfiguration auf das
  aktuelle Schema migriert; 14 Tests mit 47 Assertions bestehen.
- 792 Syntaxprüfungen, 515 Routen, beide Smoke-Tests, Migrationen,
  Datenbankmodelle, Dual-Logging und sechs authentifizierte Adminbereiche im
  isolierten Kandidaten bestanden.
- Der Produktions-Audit sinkt von vier auf drei Laravel-Advisories; zwei von
  Composer markierte aufgegebene Pakete bleiben. Public bleibt gesperrt.

## Bad Hippo 0.9.0 / Contentify 3.3-dev - 2026-09-07

- Laravel als getrennte Migrationsachse von 8.83.29 auf die letzte stabile
  9.x-Version 9.52.21 angehoben; PHP bleibt unverändert auf 8.5.10.
- Sentinel auf 6.0.1 und Collision auf 6.4.0 aktualisiert, Facade Ignition durch
  Spatie Laravel Ignition ersetzt und die Proxy-Middleware auf Laravels eigene
  Implementierung umgestellt.
- SwiftMailer durch Symfony Mailer und Flysystem 1 durch Flysystem 3 ersetzt;
  Mail- und Dateisystemkonfiguration mit Rückwärtskompatibilität für vorhandene
  Umgebungsvariablen aktualisiert.
- Das nicht mehr veröffentlichte `caffeinated/modules` v6.3.1 als MIT-lizenzierte
  lokale Kompatibilitätskopie 6.3.2 übernommen und kontrolliert für Laravel 9
  freigegeben; der eigentliche Paketquellcode blieb unverändert.
- 781 Syntaxprüfungen, zwölf Unit-Tests mit 42 Assertions, beide Smoke-Tests,
  515 Routen, Migrationen, Datenbankmodelle und fünf authentifizierte
  Adminbereiche im isolierten Kandidaten bestanden.
- Der Produktions-Audit weist weiterhin sichtbar vier Laravel-9-Advisories und
  drei aufgegebene Produktionspakete aus; Public bleibt gesperrt.

## Bad Hippo 0.8.0 / Contentify 3.3-dev - 2026-09-07

- PHP als getrennte Migrationsachse direkt von 8.0.30 auf 8.5.10 angehoben;
  Laravel bleibt bewusst unverändert auf 8.83.29.
- Containerbasis auf das gepinnte offizielle PHP-8.5-FPM-Bookworm-Abbild und
  Composer 2.10.3 umgestellt; die bereits enthaltene OPcache-Erweiterung wird
  nicht mehr ein zweites Mal kompiliert.
- Nette Schema auf 1.3.6 und Nette Utils auf 4.1.5 aktualisiert, damit Composer
  die Produktionsabhängigkeiten regulär unter PHP 8.5 installieren kann.
- Eigene implizit-nullbare Signaturen, SimpleXML-Signaturen und die veraltete
  PDO-MySQL-Konstante an PHP 8.5 angepasst; keine First-Party-Deprecations im
  vollständigen Syntaxlauf.
- 735 Syntaxprüfungen, zwölf Unit-Tests mit 42 Assertions, Composer-
  Plattformprüfung, Datenbank-, Nginx- und HTTP-Prüfungen bestanden.
- Laravel 8 erzeugt unter PHP 8.5 weiterhin Deprecation-Hinweise aus dem
  Framework. Sie bleiben vollständig in den getrennten Logs sichtbar und sind
  der Ausgangspunkt für den nächsten Laravel-Migrationsschritt.

## Bad Hippo 0.7.1 / Contentify 3.3-dev - 2026-09-07

- Besucher-IP in Middleware, Kontaktformular und Bewerbung auf Laravels
  Request-API umgestellt; `getenv('REMOTE_ADDR')` lieferte im isolierten
  PHP-Testserver `false` und brach die Besucherstatistik mit SQLSTATE 22007 ab.
- Der bei der Abschlussprüfung gefundene unabhängige Cup-Fehler wurde als
  BUG-020 erfasst und bewusst nicht mit dem PHP-Port vermischt.

## Bad Hippo 0.7.0 / Contentify 3.3-dev - 2026-09-07

- PHP kontrolliert von 7.4.33 auf 8.0.30 angehoben; Laravel bleibt als getrennte
  Migrationsachse unverändert auf 8.83.29.
- Die beiden unter PHP 8 reservierten `Match`-Modelle in `GameMatch` und
  `CupMatch` umbenannt, sämtliche Relationen und Controller angepasst und die
  historischen Tabellen sowie URLs ausdrücklich bewahrt.
- Composer-Anforderung und Lockbestand auf PHP `^8.0` aktualisiert; Installation
  und Plattformprüfung funktionieren ohne `--ignore-platform-reqs`.
- 688 Syntaxprüfungen, zwölf Unit-Tests mit 42 Assertions, beide Smoke-Tests,
  512 Routen, Datenbank- und authentifizierte Browserprüfungen bestanden.
- Staging-App, Jobrunner und Nginx gemeinsam auf 0.7.0 ausgerollt; getrennte
  JSON- und Admin-Logs unter PHP 8 bestätigt.

## Bad Hippo 0.6.0 / Contentify 3.3-dev - 2026-09-06

- Laravel kontrolliert von 7.30.7 auf 8.83.29 angehoben; PHP bleibt für diese
  getrennte Migrationsstufe unverändert auf 7.4.
- Sentinel 5.1.0, Cartalyst Support 5.1.2 und Collision 5.11.0 sowie der
  reproduzierbar neu aufgelöste Composer-Lockbestand übernommen.
- Wartungsmodus-Middleware auf Laravels neue Implementierung umgestellt.
- Contentifys Übersetzer von der in Laravel 8 entfernten internen Methode
  `sortReplacements()` entkoppelt und das bisherige Ersetzungsverhalten mit
  einem Regressionstest bewahrt.
- Zehn Unit-Tests mit 34 Assertions, beide Smoke-Tests, 686 PHP-Dateien,
  512 aktive Routen, Datenbankzugriff und Browserabläufe auf Staging bestanden.
- Produktions-Audit von 12 auf 3 Advisories in einem Paket reduziert.

## Bad Hippo 0.5.1 / Contentify 3.3-dev - 2026-09-06

- Laravel-Standardlogs auf zwei bewusst getrennte Ziele aufgeteilt: tägliches,
  ausführliches JSON unter `/var/log/contentify` und klassisches Monolog-Textlog
  unter `storage/logs/laravel.log` für die bestehende Admin-Anzeige.
- Bestehende Löschfunktion auf das klassische Anzeige-Log begrenzt; zentrale
  Anwendungs-, PHP-, Nginx- und Jobprotokolle bleiben davon unberührt.
- Logging-Konfiguration mit einem neuen Regressionstest abgesichert.

## Bad Hippo 0.5.0 / Contentify 3.3-dev - 2026-09-06

- Laravel kontrolliert von 6.20.45 auf 7.30.7 angehoben; PHP bleibt als
  getrennte Migrationsachse unverändert auf 7.4.
- Sentinel 4, Ignition 2 und Collision 4 sowie der vollständig neu aufgelöste
  Composer-Lockbestand für Laravel 7 übernommen.
- Exception-Handler auf `Throwable`, Session-Cookie-Standard auf `null` und
  eigene Artisan-Befehle auf explizite Integer-Rückgabecodes umgestellt.
- Unit-, Smoke-, Syntax-, Artisan- und Routenprüfungen im isolierten Kandidaten
  bestanden; Produktions-Audit von 39 auf 12 Advisories reduziert.

## Bad Hippo 0.4.1 / Contentify 3.3-dev - 2026-09-06

- Den neueren Bad-Hippo-Newsfeed im Admin-Dashboard vor dem historischen
  Contentify-Originalfeed angeordnet.

## Bad Hippo 0.4.0 / Contentify 3.3-dev - 2026-09-06

- Sichtbare CMS-Entwicklungsversion von `3.2-dev` auf `3.3-dev` angehoben.
- Bad-Hippo-Newsfeed zusätzlich und klar getrennt neben dem weiterhin
  vorhandenen Originalfeed im Admin-Dashboard eingebunden.
- Bad-Hippo-Feed und alle eigenen Meldungen auf das GitHub-Repository
  `Bad-Hippo-com/Contentify` verlinkt.
- Beide Quellen unabhängig gecacht, sodass der Ausfall eines Feeds den anderen
  nicht mehr entfernt.
- Externe Feed-Daten validiert, unsichere URLs und Iconnamen abgefangen und
  Meldungstexte in der Ansicht escaped.
- Drei neue Feed-Unit-Tests ergänzt; der gesamte Unit-Lauf besteht acht Tests
  mit 27 Assertions.

## Bad Hippo 0.3.0 - 2026-09-06

- Laravel innerhalb der bestehenden Major-Version von 6.20.30 auf 6.20.45
  aktualisiert; PHP bleibt für diese getrennte Migrationsstufe auf 7.4.
- `composer.lock` reproduzierbar neu erzeugt und 45 kompatible Paketupdates
  sowie vier neu aufgelöste Hilfspakete festgeschrieben.
- Composer-Validierung, Artisan-Boot, PHP-Syntax, fünf Unit-Tests und beide
  bestehenden Staging-Smoke-Tests erfolgreich ausgeführt.
- Produktions-Audit von 47 auf 39 Advisories reduziert; die Version bleibt
  deshalb ein interner Staging-Kandidat und ist nicht öffentlich freigegeben.

## Bad Hippo 0.2.6 - 2026-09-06

- Speicherplatzabfragen von Dashboard und Diagnose behandeln eingeschränkte
  oder nicht lesbare Pfade jetzt als unbekannt statt als Anwendungsfehler.
- Die Warnung bei tatsächlich wenig freiem Speicher bleibt erhalten.
- Unit- und Staging-Smoke-Tests für lesbare und nicht lesbare Pfade ergänzt.

## Bad Hippo 0.2.5 - 2026-09-06

- Der gemeinsame Modell-Uploader verarbeitet jetzt alle konfigurierten
  Dateifelder statt nach dem ersten Feld zurückzukehren.
- Team-Logo und Team-Banner lassen sich dadurch gemeinsam hochladen.
- PHPUnit-Regressionstest und ausführbarer PHP-7.4-Staging-Smoke-Test ergänzt.

## Changelog - v3.2

**Breaking Changes**: 
- TBA

**Changes**
- TBA

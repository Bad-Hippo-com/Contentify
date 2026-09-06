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

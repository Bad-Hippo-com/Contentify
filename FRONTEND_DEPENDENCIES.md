# Frontend-Abhängigkeiten und Ablösung

Stand: 2026-09-08, Arbeitsstand 0.20.0 (Kandidat, nicht Staging).

| Bestandteil | Tatsächlicher Stand | Entscheidung / Nachweis |
|---|---|---|
| jQuery | 3.7.1, npm-Lock und bytegleiche Auslieferung | 2.2.4 bereits ersetzt; breite Browser-/Ablauftests nötig |
| Bootstrap | 5.3.8 inkl. Popper-Bundle | Ein Runtime-Stylesheet; alte LESS-Referenzen liefern noch Helfer |
| SunEditor | 3.3.2 | Standardfilter aktiv; kein Ersatz für serverseitige HTML-Prüfung |
| Moment | 2.30.1 inkl. Sprachpakete | Nur für alten Kalender; gemeinsam mit ihm ablösen |
| bootstrap-datetimepicker | Manuell gepflegte Altversion | jQuery-3-/Bootstrap-5-Adapter geprüft; Ersatz erst mit Datums-Roundtrip-Tests |
| bootstrap-tagsinput | Manuell eingebunden | Bilderformular nutzt die add-API; nicht blind entfernen |
| Flot | Manuell eingebunden | Besucherstatistik nutzt es weiterhin; Diagrammablauf vor Ersatz prüfen |
| jsnow | Optionaler Schneeeffekt | Eigener Modernisierungsentscheid; derzeit noch eingebunden |
| Font Awesome | Manuell eingebunden, siehe version.txt | Aktives Iconsystem; künftig npm-Sperrdatei und reproduzierbarer Build |
| Glyphicons | Keine direkten Aufrufer in eigenen Ansichten | Layout-Imports entfernt, Kalenderdefaults auf Font Awesome; alte Dateien vorerst Kompatibilitätsreste |
| Browser-LESS 1.3.3 | Keine Quellcode-Referenzen | Unbenutztes Laufzeit-JavaScript entfernt; Node-/PHP-LESS bleiben |
| vendor/datetime/picker | Keine Quellcode-Referenzen | Zweite unbenutzte Kalenderbibliothek entfernt |
| Bootstrap-3-LESS-Helfer | Variablen, Mixins, Referenzimporte | Noch von Themes benötigt; modulweise durch eigene Helfer ersetzen, kein zweites Bootstrap-CSS |

## Prüfregeln

- npm-Audit erfasst manuell vendorte Dateien nicht vollständig.
- Kalenderersatz muss bestehende Datumswerte lesen, ändern, speichern und nach
  erneutem Öffnen unverändert anzeigen: Deutsch, Zeit, Sekunden und leere Werte.
- Nach jeder Entfernung beide Themes, Admin, Editor und Kalender prüfen.
- Veraltete Dateien auch aus dem persistenten Kandidaten-Public-Volume entfernen.
- Neues Design ist ein eigener Arbeitsblock; Funktionsparität vor Redesign.

## Echte Abläufe

Separate Suite: phpunit-workflows.xml. Nur mit CONTENTIFY_RUN_WORKFLOW_TESTS=1
und APP_URL=http://192.168.178.213:8088 ausführbar. Sie benutzt echte Controller
und die Kandidaten-Datenbank, rollt Datenbanktransaktionen zurück und fängt E-Mails
im Array-Transport ab. Keine externen E-Mails, keine Produktivkonten ändern.
CAPTCHA wird als kontrollierte Testsitzung gesetzt; dies ersetzt keine manuelle
CAPTCHA-/Browserprüfung. CSRF-Negativtests bleiben in der separaten Unit-Suite.

Begonnen: Registrierung/Login/Rechte, Nachrichten- und Kommentar-CRUD,
Passwortreset einschließlich Mailer und Codewiederverwendung. Noch keine
Gesamtfreigabe für Forum, Matches, Cups oder Uploads.

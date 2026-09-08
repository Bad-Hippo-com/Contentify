## Bad Hippo 0.21.2 / Contentify 3.3-dev - 2026-09-08 10:58 CEST

Reset bei bereits angemeldetem Benutzer: Sentinel logout mit frisch geladenem
Benutzerobjekt bewahrte die aktuelle Persistenz. Explizites flush entfernt jetzt
auch diese Anmeldung. Im erweiterten Ablauftest reproduziert, Neubau folgt.

## Bad Hippo 0.21.1 / Contentify 3.3-dev - 2026-09-08 10:56 CEST

Bcrypt-Grenze zusätzlich in Bytes prüfen: mehrbyteige Passwörter werden nicht
stillschweigend abgeschnitten. Tests für falsche Bestätigung und Widerruf der
Sentinel-Persistenzen ergänzt. Kandidatenabnahme läuft.

## Bad Hippo 0.21.0 / Contentify 3.3-dev - 2026-09-08 10:53 CEST

Passwortreset über eigenen Passwortdialog und CSRF-geschützten POST. Gehashte,
einmalige Tokens, eine Stunde Laufzeit, Ersatzlink entwertet alte Anträge.
Kein Passwortversand; alte ungenutzte Mailvorlage entfernt (über Git wiederherstellbar).
Kontoneutrale Antwort, Ratenlimit und Widerruf der Sentinel-Persistenzen.
Reset-Pfade in Zugriffslogs maskiert; Referrer-Policy und Cache-Control ergänzt.
Zehn Ablauftests/98 Assertions bestehen. Containerneubau noch in Prüfung.

## Bad Hippo 0.20.5 / Contentify 3.3-dev - 2026-09-08 10:31 CEST

Matchergebnisse erhalten den expliziten Fremdschlüssel match_id; Anlegen,
Ändern und Löschen aktualisieren das Gesamtergebnis. Acht transaktionale
Controller-/DB-Ablauftests bestehen mit 72 Assertions. Sauberer Containerneubau
abgenommen am 2026-09-08 10:35 CEST: zusätzlich 35 Tests/205 Assertions, beide
Smoke-Tests, LESS und Composer-Audit bestanden. News-Editoren im Browser sichtbar.
Nur Kandidat :8088, nicht main oder Staging :80; keine Public-Freigabe.

### Enthaltene Fixstände 0.20.1 bis 0.20.4

- 0.20.1: Ungültiger Ersatz-Upload löscht keinen bestehenden Datensatz mehr.
- 0.20.2: Cup-Events nutzen die eigene Instanz; beide Halbfinals verweisen auf das Finale.
- 0.20.3: Leere ursprüngliche Zeitstempel bleiben beim Eloquent-Vergleich nullable.
- 0.20.4: MatchScore initialisiert Eloquent wieder über parent::boot().

## Bad Hippo 0.20.0 / Contentify 3.3-dev - 2026-09-08

Frontend-Inventar, Entfernung ungenutzter Browser-LESS-/Kalenderdateien und
Glyphicons-Imports. Kalenderdefaults verwenden das aktive Font-Awesome-System.
Separate echte Controller-/DB-Ablauftests mit Rollback und abgefangener E-Mail.
Kandidatenabnahme offen; Designmodernisierung separat vorgemerkt.

## Bad Hippo 0.19.5 / Contentify 3.3-dev - 2026-09-08

Nicht unterstützte tooltips-Option des alten Kalenderplugins entfernt. Die
deutsche Moment-Lokalisierung bleibt aktiv. Dieser Kandidatenfehler verhinderte
Editoren auf Formularseiten; Browserabnahme hat Vorrang vor weiteren Änderungen.

Abnahme 2026-09-08 09:52 CEST: Container gebaut, 34 Tests/203 Assertions und
beide Smoke-Skripte bestanden. Seiten/News/Downloads-Editoren, Texteingabe und
deutscher Kalender bestätigt. Kandidat :8088; nicht main und nicht Staging :80.

## Bad Hippo 0.19.4 / Contentify 3.3-dev - 2026-09-08

Lokale Script-/Stylesheet-URLs erhalten die Buildversion als Cache-Schlüssel.
Der Browser verwendete trotz Neuladen alte Kalenderdateien; externe URLs bleiben
unverändert. Regressionstest ergänzt. Kandidatenprüfung noch offen.

## Bad Hippo 0.19.3 / Contentify 3.3-dev - 2026-09-08

jQuery-3-Kompatibilität: Kalender verwendet length statt size(), Kommentare und
Teammitglieder done() statt success(). Kommentarereignisse werden einmal delegiert,
Bearbeiten/Löschen und anschließendes Neuanlegen überschreiben keine Handler mehr.
Teamnamen werden als Text angehängt; Kommentar-Speicherknopf auf Deutsch.

## Bad Hippo 0.19.2 / Contentify 3.3-dev - 2026-09-08

Kandidatenkorrektur: Kalender verwendet die bereits initialisierte Framework-
Instanz. AJAX-CSRF-Token nur im Same-Origin-Header, nicht in URLs/fremden Anfragen.
Freundschaftsanfragen dürfen nur Empfänger bestätigen, nicht der Absender.
Kommentar-Regressionsfixtures korrigiert; Kandidaten-Abnahme läuft.

## Bad Hippo 0.19.1 / Contentify 3.3-dev - 2026-09-08

Sicherheitsnachprüfung: Bestätigung/POST auch für die erkannten Forum-, Freunde-
und Verwaltungsaktionen. Kommentar-Eigentümerprüfung ohne geladene Beziehung;
Tests für Fremdzugriffe und fehlendes CSRF-Token. HTTP-Fehlertexte auf Deutsch.
Kandidatenprüfung noch nicht abgeschlossen; Staging weiterhin 0.18.3.

## Bad Hippo 0.19.0 / Contentify 3.3-dev - 2026-09-08

In Kandidatenprüfung: Cup-Siegerwechsel, Kommentar-Kontext und HTTP-Fehlerstatus
repariert; /messages führt zum Posteingang. Empfänger/Eingaben werden validiert.
Cup-Aktionslinks zeigen vor schreibenden POST-Aufrufen eine CSRF-Bestätigung.
jQuery 3.7.1 und Moment 2.30.1 mit deutschen Kalendertexten reproduzierbar gebaut;
Editor-Standardfilter wieder aktiv, ungenutzte optionale Plugins ausgeblendet.
Noch kein vollständiger Sicherheitsnachweis oder Public-Release.

## Bad Hippo 0.18.3 / Contentify 3.3-dev - 2026-09-08

- Tabellenrahmen in Morpheus und Phobos auf Benutzerwunsch entfernt, einschließlich
  Zelllinien und mobiler Zeilenrahmen. Farben, Hover und Adminstil bleiben erhalten.
- Alte globale 2-Pixel-Zellrahmen entfernt und CSS-Regressionsprüfung ergänzt.

## Bad Hippo 0.18.2 / Contentify 3.3-dev - 2026-09-08

- Bootstrap-5-Tabellenvariablen an Morpheus/Phobos angepasst: dunkle Flächen,
  helle Schrift und abgestufte Rahmen-, Streifen- und Hoverfarben (BUG-047).
- Nachrichtentabellen und Tabrahmen eingeschlossen, Backend-Farben separat
  auf den hellen Inhaltsbereich abgestimmt; Kalenderfarben nicht verdunkelt.
- Reproduzierbare CSS-Builds und Farb-Regressionsprüfungen bestanden.
- 08:50 CEST: auf Staging installiert, Profil und Posteingang visuell und
  anhand gerenderter Farbwerte abgenommen. Implementierung auf GitHub main.

## Bad Hippo 0.18.1 / Contentify 3.3-dev - 2026-09-08

- 08:26 CEST auf Staging abgenommen und auf GitHub main übernommen; final
  23 Tests / 83 Assertions, beide Smoke-Skripte, LESS-Neubau und Audits bestanden.
- Kalender-Collapse-Instanzen beim Schließen freigeben; bisherige Linkoptik
  erhalten und GitHub-Newsfeed um den Bootstrap-5-Meilenstein ergänzt.
- Kandidat 0.18.0 bestand 23 Tests / 83 Assertions, beide Smoke-Skripte,
  35 Admin-Menüziele, sechs Tabs, Modal, Kalender sowie beide Themes mobil/desktop.
- Prüfaufbau: fehlende Schreibrechte für Dev-Pakete ausschließlich im
  Kandidaten-Vendorverzeichnis korrigiert; erster Fehlversuch wird archiviert.

## Bad Hippo 0.18.0 / Contentify 3.3-dev - 2026-09-08

- Bootstrap 5.3.8 mit Popper 2 lokal eingebunden; Bootstrap-4-JavaScript ersetzt.
- Tabs und Dismiss-Attribute auf data-bs umgestellt; Modal-Lebenszyklus inklusive
  Dispose, Tooltips und Kalender-Collapse auf native Bootstrap-5-API portiert.
- Laravel-Pagination auf Bootstrap 5 umgestellt; Theme-LESS-Referenzadapter
  bleibt bewusst bestehen, Formularabstände und bestehende Breite erhalten.
- Regressionstests prüfen alte Datenattribute und API-Aufrufe. Erstlauf meldete
  nur einen veralteten Bannervergleich (zusätzliches Leerzeichen), korrigiert.
- Kandidatenprüfung läuft; noch keine Staging- oder Public-Freigabe.

## Bad Hippo 0.17.2 / Contentify 3.3-dev - 2026-09-08

- Glyphicons vom Theme-Neubau getrennt; stabile relative Schriftpfade gelten
  für Backend, Morpheus und Phobos, auch bei Installation im Unterverzeichnis.
- Node- und PHP-Prüfungen gegen erneut eingebettete falsche Schriftpfade ergänzt.
- 2026-09-08 07:37 CEST: nach 23 Tests mit 83 Assertions, beiden Smoke-Skripten
  und Browserprüfung auf Staging installiert; Implementierung auf `main`.
  Bootstrap 4 bleibt eine interne Zwischenstufe, keine Public-Freigabe.

## Bad Hippo 0.17.1 / Contentify 3.3-dev - 2026-09-08

- Bootstrap-4-Kandidatenkorrekturen: Phobos-Kopf und -Fuß überlappen mobil nicht
  mehr, alte Uhr-Icons und aktive Theme-Tabs angepasst.
- Nginx-Build übernimmt Public-Dateien mit PHP-Schreibrechten (STAGE-008).
- Defekten PHP-LESS-Compiler durch Wikimedia/less.php 5.5.1 ersetzt; expliziter
  Neubau berücksichtigt Imports und schreibt atomar (BUG-042).
- Regressionstest kompiliert Backend und beide Themes mit der PHP-Laufzeit.

## Bad Hippo 0.17.0 / Contentify 3.3-dev - 2026-09-08

- Bootstrap 4.6.2 mit Popper-Bundle lokal eingebunden, alle vier CSS-Ziele gebaut.
- Raster-Breakpoints, Formularlayout, Tabs, Pagination und Datumsauswähler
  angepasst. Dokumentierte LESS- und Glyphicons-Adapter erhalten alte Themes.
- Kandidat bestand 22 Tests mit 74 Assertions sowie beide Smoke-Skripte.

## Bad Hippo 0.16.0 / Contentify 3.3-dev - 2026-09-07

- Gemischten Bootstrap-Bestand aus LESS 3.3.3 und externem JavaScript 3.3.1
  auf die letzte kompatible 3.x-Version 3.4.1 vereinheitlicht.
- Bootstrap-JavaScript und MIT-Lizenz lokal ausgeliefert; CDN-Abhängigkeit aus
  Backend, Morpheus und Phobos entfernt.
- Backend, beide Themes und das aktive Frontend reproduzierbar aus derselben
  Bootstrap-3.4.1-LESS-Quelle gebaut und durch Node-Verträge abgesichert.
- Kandidat mit 22 Tests, 74 Assertions, beiden Smoke-Skripten und echtem
  Browser-Modal geprüft; anschließend auf Staging ausgerollt und dort erneut
  über Assets, Oberfläche und zentrale Logs abgenommen.
- Zwei aktuelle Bootstrap-3-XSS-Advisories als BUG-041 erfasst. 0.16.0 ist nur
  eine interne Kompatibilitätsbrücke; Bootstrap 5.3.8 bleibt das Sicherheitsziel.

## Bad Hippo 0.15.3 / Contentify 3.3-dev - 2026-09-07

- Installationsmarker während aller PHPUnit-Läufe zentral und ausfallsicher
  ausblenden, damit Tests auch auf einem installierten Kandidaten zustandslos
  booten und den Betriebsdatenbestand nicht verwenden.
- Den in 0.15.1/0.15.2 erprobten SQLite-Umweg wieder entfernt; diese beiden
  Kandidaten wurden nicht auf Staging übernommen.
- Finalen Kandidaten mit 22 Tests und 74 Assertions, 515 Routen sowie beiden
  Smoke-Skripten bestanden und anschließend auf Staging installiert.
- Live 512 modulabhängige Routen, PHP 8.5.10, Laravel 13.30.1, Dashboard-Icons,
  IP-Menülinks, Feed-Reihenfolge, beide SunEditor-Instanzen, HTTP-Assets und
  frische Containerlogs geprüft; CKEditor bleibt erwartungsgemäß HTTP 404.

## Bad Hippo 0.15.2 / Contentify 3.3-dev - 2026-09-07

- Für den PDO-SQLite-Bau im Debian-Prüfimage die fehlenden SQLite-Header
  ergänzt; der erste 0.15.1-Kandidatenbau hatte deshalb kontrolliert gestoppt.

## Bad Hippo 0.15.1 / Contentify 3.3-dev - 2026-09-07

- Von PHPUnit bereits erzwungene SQLite-In-Memory-Verbindung tatsächlich in
  Laravel registriert und die PDO-Erweiterung im Prüfimage installiert.
- MariaDB bleibt unverändert die einzige Staging-/Betriebsdatenbank; SQLite ist
  ausschließlich die zustandslose Testverbindung.

## Bad Hippo 0.15.0 / Contentify 3.3-dev - 2026-09-07

- Historische Grunt-Baukette vollständig durch das exakt festgeschriebene
  Less 4.9.1 und einen kleinen Node-24-Watcher ersetzt; Bootstrap bleibt 3.3.7.
- `npm ci`, normaler CSS-Build, Einmal-Watcher und Frontend-Vertragstest laufen
  ohne `--legacy-peer-deps` und ohne veraltete Grunt-Plugins.
- npm-Abhängigkeitsbaum von 23 bekannten Schwachstellen auf null reduziert.
- Google-Font-Import wird nicht mehr beim Build aus dem Netz aufgelöst; zwei
  aufeinanderfolgende Builds erzeugen denselben SHA-256-Hash.

## Bad Hippo 0.14.0 / Contentify 3.3-dev - 2026-09-07

- Den seit 2013 ausgelieferten CKEditor 4.3.1 vollständig durch den exakt
  festgeschriebenen, MIT-lizenzierten SunEditor 3.3.2 ersetzt.
- Alle Rich-Text-Felder über einen gemeinsamen Initialisierer angebunden;
  deutsche Desktop-/Mobil-Werkzeugleisten sowie Contentifys Bilder-, Vorlagen-
  und Flaggenauswahl bleiben erhalten.
- Formularwerte werden auch bei geöffnetem Quelltextmodus sicher synchronisiert;
  editorinterne Attribute und Klassen werden vor dem Speichern entfernt.
- Die 236 alten CKEditor-Dateien entfernt und Lizenz, Assets, Adapter und
  Nichtvorhandensein des Alteditors mit Regressionstests abgesichert.
- Nginx liefert fehlende statische Dateien direkt als 404, sodass entfernte
  Bibliotheken nicht mehr Laravels generische 500-Seite und Fehlerlogs auslösen.
- 21 Unit-Tests mit 71 Assertions, zusätzlich 3 fokussierte Editor-Tests mit
  16 Assertions, 515 Routen, beide Smoke-Tests sowie echtes Erstellen und
  Bearbeiten einer News im isolierten Browserkandidaten geprüft.
- Der Produktions-Audit des neuen Editors meldet keine bekannte Schwachstelle.
  Der historische Grunt-Entwicklungsbaum bleibt eine getrennte Frontend-Aufgabe;
  Public bleibt bis zur sauberen Testinstallation gesperrt.
- Derselbe Stand läuft auf Staging an Port 80: neue Assets und Homepage liefern
  HTTP 200, der entfernte CKEditor-Pfad HTTP 404; Admin-Editor, oberster
  Bad-Hippo-Feed, 512 Live-Routen und beide Smoke-Tests wurden erneut geprüft.

## Bad Hippo 0.13.0 / Contentify 3.3-dev - 2026-09-07

- Laravel getrennt auf 13.30.1 angehoben; PHP bleibt unverändert auf 8.5.10.
- Sentinel auf 10.0.0, Tinker auf 3.0.2 und PHPUnit auf 12.5.34 aktualisiert.
- Validierungs-Observer ohne verschachtelte Modellinstanz registriert und den
  Laravel-13-Modellboot dadurch kompatibel gemacht.
- Contentifys CSRF-Spamschutz auf Laravels neuen Origin- und Token-Schutz
  aufgesetzt; bestehendes Verhalten der Drei-Sekunden-Sperre erhalten.
- Cache-Deserialisierung ausschließlich für die vom Dashboard-Feed benötigte
  `stdClass` freigegeben und PHP-Sitzungsserialisierung für die Migration
  ausdrücklich beibehalten.
- Bad-Hippo-Feed-URL und Anwendungscache mit dem Stand 0.13.0 versioniert, damit
  GitHubs Raw-CDN nicht weiterhin die ältere Laravel-12-Meldung ausliefert.
- 802 Syntaxprüfungen, 19 Tests mit 58 Assertions, 512 Routen, vier Migrationen,
  beide Smoke-Tests, Anmeldung, Dual-Logging und alle 36 Adminbereiche geprüft.
- Composer-Audit ohne bekannte Sicherheitslücke; Public bleibt bis zur
  unabhängigen sauberen Testinstallation gesperrt.

## Bad Hippo 0.12.1 / Contentify 3.3-dev - 2026-09-07

- Contentifys Controller-Dispatcher an Laravels positionsbasierte Übergabe von
  Routenparametern angepasst; `Unknown named parameter $user/$slug` behoben.
- Regressionstest für voneinander abweichende Routen- und Argumentnamen ergänzt.
- 802 Syntaxprüfungen, 19 Tests mit 55 Assertions, 512 Routen, beide Smoke-Tests
  und alle 36 Adminbereiche erneut geprüft.
- Public bleibt bis zur getrennten sauberen Testinstallation gesperrt.

## Bad Hippo 0.12.0 / Contentify 3.3-dev - 2026-09-07

- Laravel getrennt von 11.56.1 auf 12.69.1 angehoben; PHP bleibt unverändert
  auf 8.5.10.
- Sentinel offiziell von 8.0.0 auf 9.0.0 und PHPUnit auf 11.5.56 aktualisiert.
- Die dokumentierten Modul-, Steam- und Collective-HTML-Brücken mit
  unverändertem PHP-Code auf Illuminate 12 erweitert.
- Laravels Änderungen an UUIDs, Container-Defaults, lokaler Dateisystemwurzel,
  SVG-Validierung und Routennamens-Priorität gegen Contentifys Nutzung geprüft;
  den bisherigen lokalen Speicherpfad durch einen Regressionstest abgesichert.
- 802 Syntaxprüfungen, 18 Tests mit 54 Assertions, 512 Routen, vier
  Migrationen, beide Smoke-Tests, Datenbank-, Authentifizierungs-, Log- und
  Browserprüfungen bestanden.
- Alle 36 Adminbereiche im isolierten Kandidaten geöffnet. Der Audit meldet
  keine bekannte Sicherheitslücke mehr; nur Less.php bleibt aufgegeben.
- Public bleibt gesperrt, bis der getrennte Testserver die saubere Installation
  und die repräsentativen Abläufe bestanden hat.

## Bad Hippo 0.11.1 / Contentify 3.3-dev - 2026-09-07

- PHP-FPM-App-Container verbindlich als `www-data` ausführen, damit Laravel-
  CLI-Prüfungen keine root-eigenen zentralen Logdateien hinterlassen.
- Eigentümer der vorhandenen Laravel-Tageslogs korrigiert und beide
  Logausgaben erneut mit demselben Live-Marker geprüft.
- Staging-Anleitung für alle anwendungsbezogenen Containerbefehle auf
  `www-data` festgelegt.

## Bad Hippo 0.11.0 / Contentify 3.3-dev - 2026-09-07

- Laravel getrennt von 10.50.3 auf 11.56.1 angehoben; PHP bleibt unverändert
  auf 8.5.10.
- Sentinel 8.0.0, Collision 8.5.0 und Carbon 3.13.2 eingeführt.
- Laravel Collective HTML 6.4.1 als dokumentierte lokale MIT-
  Kompatibilitätsversion 6.4.2 für Laravel 11 fortgeführt und zwei
  Konstruktorsignaturen ohne Verhaltensänderung an PHP 8.5 angepasst.
- Bestehenden vollständigen Contentify-Konfigurationsbaum beibehalten und das
  doppelte Laden der schlanken Laravel-11-Frameworkkonfiguration verhindert.
- Foren-Zeitdifferenz an die gerichteten Carbon-3-Ergebnisse angepasst und den
  entfernten Carbon-Formatzugriff der Formularansichten ersetzt; den frischen
  Installationstest gegen vorhandene Laufzeitmarker isoliert.
- Den seit der PHP-8-Modellumbenennung falschen Match-Formular-Viewnamen
  repariert und den bestehenden `admin_form`-Vertrag getestet.
- Composer-Auflösung, 801 Syntaxprüfungen, 17 Tests mit 52 Assertions und 512
  aktive Produktionsrouten geprüft; drei
  Framework-Advisories bleiben sichtbar, Public bleibt gesperrt.

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

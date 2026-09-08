# Contentify staging deployment

Stand 2026-09-08 09:42 CEST: **0.19.3 in Kandidatenprüfung, noch nicht auf Staging.**
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

Version: **0.18.3 / Contentify 3.3-dev**
Last updated: **2026-09-08 08:56 CEST**

This deployment continues from the historical Contentify 3.2-dev baseline as
Bad Hippo 3.3-dev behind Nginx. PHP 8.5 is isolated in a container and is not an
approved public target.

## Components

- `nginx`: public HTTP endpoint on port 80
- `app`: PHP-FPM 8.5.10, Laravel 13.30.1 and Contentify 3.3-dev
- `database`: MariaDB 10.11 with a persistent data volume
- `jobs`: Contentify's `php artisan jobs` executor, run once per minute

All upstream container bases are pinned by image digest so the same baseline
can later be rebuilt on the separate test host.

Application state, database data and the public runtime tree use separate
Docker volumes. Host logs are stored below `/var/log/contentify`. The real
`deploy/staging/.env.staging` contains secrets, must stay mode 0600 and is
excluded from both Git and the Docker build context.

The root Composer dependency tree remains excluded from the image context,
while Contentify's tracked browser libraries below `public/vendor` are
explicitly re-included. Bootstrap 3.4.1's five original Glyphicons font files
are stored in `public/css/fonts`, matching the paths already emitted by the
historical compiled backend CSS.

## Normal operation

Beim Kopieren aus dem Nginx-Abbild müssen PHP-Schreibrechte erhalten bleiben.
Ab 0.17.1 verwendet die Buildstufe `COPY --chown=33:33`. Nach Aktualisierung
eines bestehenden Public-Volumes dessen Eigentümer auf UID/GID 33:33 prüfen
und korrigieren; anschließend echten Theme-Wechsel und `php artisan less:compile`
testen. Eine reine HTTP-200-Assetprüfung erkennt STAGE-008 nicht.

From `deploy/staging`:

Ab 0.18.1 gilt Bootstrap 5.3.8. Vor der Übernahme müssen native Tabs, Modal-
Schließen samt DOM-/Backdrop-Cleanup, Kalender-Datum/Uhrzeit-Umschaltung und
Phobos-Dropdown funktionieren. Beide Themes sind mobil und auf Desktop zu prüfen.
Dev-Pakete nur im isolierten Kandidaten installieren, nicht im Staging-Image.

```sh
sudo docker compose --env-file .env.staging up -d
sudo docker compose --env-file .env.staging ps
sudo docker compose --env-file .env.staging logs --since 10m --no-color
```

Wenn der App-Container neu gebaut oder ersetzt wurde, muss Nginx im selben
Rollout neu erstellt werden, damit sein PHP-Upstream nicht auf die alte
Container-IP zeigt:

```sh
sudo docker compose --env-file .env.staging up -d --force-recreate app jobs nginx
```

After a build, verify at minimum that the homepage, Font Awesome CSS and WOFF2,
jQuery (`/vendor/jquery/jquery-2.2.4.min.js`) and the Glyphicons WOFF2 return
HTTP 200 with their expected MIME types.

Ab `0.14.0` müssen zusätzlich `/vendor/suneditor/suneditor.min.js`,
`/vendor/suneditor/suneditor.min.css` und `/vendor/contentify/editor.js` mit
HTTP 200 antworten. Der persistente `public-data`-Datenträger darf keine alte
`vendor/ckeditor`-Kopie behalten; der Rollout entfernt dieses Verzeichnis
ausdrücklich und prüft, dass der alte JavaScript-Inhalt nicht mehr ausgeliefert
wird. Die konkrete Negativprobe `/vendor/ckeditor/ckeditor.js` muss HTTP 404
liefern.

Ab `0.15.0` wird das Backend-Stylesheet außerhalb der Laufzeitcontainer mit
Node.js 24, npm 11 und dem exakt festgeschriebenen Less 4.9.1 gebaut. Vor einem
Rollout müssen `npm ci`, `npm audit`, `npm run build` und `npm test` erfolgreich
laufen. Bootstrap bleibt in dieser Stufe unverändert auf 3.3.7.

Ab `0.15.3` blendet die gemeinsame PHPUnit-Basis den Installationsmarker für
jeden Test aus und stellt ihn anschließend wieder her. Dadurch greift die Suite
auch im installierten Container weder auf dessen MariaDB noch auf Betriebsdaten
zu; zusätzliche SQLite-Pakete im Laufzeitimage sind nicht erforderlich.

Ab `0.16.0` wird Bootstrap 3.4.1 vollständig lokal ausgeliefert. Neben den vier
CSS-Builds muss `/vendor/bootstrap/bootstrap.min.js` HTTP 200 liefern; keine
Layoutdatei darf mehr `maxcdn.bootstrapcdn.com/bootstrap` referenzieren. Diese
Version ist wegen BUG-041 ausschließlich eine interne Kompatibilitätsbrücke.
Der Browser-Abnahmetest muss zusätzlich das Contentify-Bildermodal öffnen und
wieder schließen, damit die echte Bootstrap-JavaScript-Laufzeit geprüft wird.

Der Mehrfachupload-Smoke-Test für Original-Issue `#650` läuft innerhalb des
App-Containers mit:

```sh
sudo docker compose --env-file .env.staging exec -u www-data app \
  php tests/Smoke/UploaderMultipleFiles.php
```

Er muss sowohl Logo und Banner gemeinsam als auch einen Banner bei leerem
Logo-Feld bestätigen.

Die fehlertolerante Speicherplatzabfrage aus Original-Issue `#624` wird geprüft
mit:

```sh
sudo docker compose --env-file .env.staging exec -u www-data app php tests/Smoke/DiskSpace.php
```

Alle Artisan-, Tinker- und anwendungsbezogenen PHP-Befehle müssen im
App-Container als `www-data` laufen. Der App-Dienst ist ab `0.11.1` auch selbst
auf diesen Benutzer festgelegt. So können CLI-Prüfungen keine root-eigenen
Laravel-Logdateien erzeugen, die PHP-FPM anschließend nicht fortschreiben kann.
Vor dem ersten Start von `0.11.1` werden vorhandene Anwendungs- und PHP-Logs
einmalig auf UID/GID 33 (`www-data`) zurückgesetzt; Nginx-Logs bleiben davon
getrennt.

Do not copy staging volumes or secrets to test. The test host will receive the
same versioned source and procedure, then perform a clean installation with
fresh volumes and independent credentials.

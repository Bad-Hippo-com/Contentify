# Contentify staging deployment

Stand 2026-09-08 07:34 CEST: **0.17.2 mit Bootstrap 4.6.2 auf Staging abgenommen.**
23 Tests mit 83 Assertions, beide Smoke-Tests, PHP-LESS-Neubau und Browserprüfung
bestanden. Theme-LESS und Glyphicons bleiben dokumentierte Übergangsschnittstellen.
Bootstrap 4 ist EOL; Ziel bleibt Bootstrap 5. Kalendertexte und optionale
SunEditor-Pluginwarnungen sind als BUG-043 noch offen.

Version: **0.17.2 / Contentify 3.3-dev**
Last updated: **2026-09-08 07:34 CEST**

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

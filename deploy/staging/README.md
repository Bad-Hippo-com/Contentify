# Contentify staging deployment

Version: **0.15.2 / Contentify 3.3-dev**
Last updated: **2026-09-07 17:56 CEST**

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
explicitly re-included. Bootstrap 3.3.7's five original Glyphicons font files
are stored in `public/css/fonts`, matching the paths already emitted by the
historical compiled backend CSS.

## Normal operation

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

Das Image enthält ab `0.15.2` außerdem PDO-SQLite für die durch `phpunit.xml`
festgelegte In-Memory-Testdatenbank. Dieser Treiber ändert die Betriebsdatenbank
nicht; Staging verwendet weiterhin ausschließlich MariaDB. Der Build benötigt
dazu unter Debian `libsqlite3-dev`.

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

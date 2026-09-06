# Contentify staging deployment

Version: **0.5.1 / Contentify 3.3-dev**
Last updated: **2026-09-06 21:27 CEST**

This deployment continues from the historical Contentify 3.2-dev baseline as
Bad Hippo 3.3-dev behind Nginx. PHP 7.4 is isolated in a container and is not an
approved public target.

## Components

- `nginx`: public HTTP endpoint on port 80
- `app`: PHP-FPM 7.4, Laravel 7.30.7 and Contentify 3.3-dev
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

Der Mehrfachupload-Smoke-Test für Original-Issue `#650` läuft innerhalb des
App-Containers mit:

```sh
sudo docker compose --env-file .env.staging exec app \
  php tests/Smoke/UploaderMultipleFiles.php
```

Er muss sowohl Logo und Banner gemeinsam als auch einen Banner bei leerem
Logo-Feld bestätigen.

Die fehlertolerante Speicherplatzabfrage aus Original-Issue `#624` wird geprüft
mit:

```sh
sudo docker compose --env-file .env.staging exec app php tests/Smoke/DiskSpace.php
```

Do not copy staging volumes or secrets to test. The test host will receive the
same versioned source and procedure, then perform a clean installation with
fresh volumes and independent credentials.

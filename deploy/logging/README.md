# Central logging for Contentify

Version: **0.5.0**
Last updated: **2026-09-06 21:05 CEST**

Each test or staging server uses its own local `/var/log/contentify` directory.
All relevant logs are therefore in one predictable location without sharing
data between the two environments.

Known UI limitation: Contentify's historical `/admin/config/log` page still
looks only for `storage/logs/laravel.log`. It does not display these central
files yet; see BUG-018. Diagnose from the files below until the bounded admin
viewer has been migrated.

## File layout

| File pattern | Producer |
| --- | --- |
| `application-YYYY-MM-DD.log` | Laravel application and uncaught exceptions |
| `security-YYYY-MM-DD.log` | Explicit Laravel security/audit events |
| `jobs-YYYY-MM-DD.log` | Explicit Laravel job events |
| `php/php-error.log` | PHP errors |
| `php/fpm-error.log` | PHP-FPM process errors |
| `php/fpm-access.log` | PHP-FPM request records |
| `nginx/error.log` | Nginx errors |
| `nginx/access.log` | Nginx access records |
| `jobs-runner.log` | Contentify `artisan jobs` output |
| `deployment.log` | Installation and deployment scripts |

Laravel files use JSON Lines so they can be filtered by timestamp, channel,
severity and context. System component logs retain their native formats.

## Installation order on each host

1. Run `sudo bash deploy/logging/prepare-log-directory.sh`.
2. Merge the matching `test.env.example` or `staging.env.example` values into
   the environment-specific `.env` file.
3. Install `php-errors.ini.example` into the active PHP-FPM `conf.d` directory,
   replacing the log-root placeholder if the default path is changed.
4. Merge `php-fpm-pool.conf.example` into the active FPM pool.
5. Use either the Nginx or Apache logging snippet in the site configuration.
6. Install `logrotate-contentify.conf.example` as
   `/etc/logrotate.d/contentify`.
7. Restart PHP-FPM and the webserver, then run the verification script.

Do not enable `display_errors` in HTTP responses. Test diagnostics remain fully
available in the central files without disclosing paths, SQL or credentials to
a browser.

## Environment separation

Test and staging have the same filenames but reside on their respective hosts
and IPs. They must never mount a shared `/var/log/contentify`. When logs are
later forwarded to a collector, attach `environment=test` or
`environment=staging` and the hostname at the collector boundary.

## Laravel channel usage

Normal exceptions automatically use `application`. Code that records an
explicit security or job event should select the prepared channel:

```php
Log::channel('security')->notice('Authentication denied', $context);
Log::channel('jobs')->info('Job completed', $context);
```

Never write passwords, application keys, session IDs, reset tokens, database
connection strings or complete request bodies into any log context.

Deployment commands can be recorded without losing their console output:

```sh
your-deployment-command 2>&1 | tee -a /var/log/contentify/deployment.log
```

Only use this for commands whose output does not expose secrets.

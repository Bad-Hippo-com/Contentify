# Contentify – Bad Hippo Community-Fork

> **Status: frühe Stabilisierung, noch nicht für öffentliche Produktivsysteme freigegeben.**

Dies ist der deutschsprachig gepflegte Community-Fork von Contentify unter
[`Bad-Hippo-com`](https://github.com/Bad-Hippo-com). Wir stabilisieren zuerst
den Funktionsumfang von Contentify ausgehend von 3.2-dev. Unsere gepflegte
Entwicklungslinie trägt ab jetzt die CMS-Kennung **3.3-dev**. PHP, Laravel,
Bootstrap und Node.js werden weiterhin einzeln, messbar und mit Tests
aktualisiert.

Das ursprüngliche Projekt und die Arbeit von Chris Konnertz bleiben ausdrücklich
genannt. Dieser Fork ist derzeit keine offizielle Fortsetzung des ursprünglichen
Maintainers. Zusammenarbeit, Rückführung geeigneter Änderungen und eine spätere
Übergabe bleiben ausdrücklich willkommen.

Aktueller Arbeitsstand: **Bad Hippo 0.5.1 / Contentify 3.3-dev**.
Die Installation funktioniert auf Staging; ein unabhängiger sauberer Testserver
und die Modernisierung des veralteten Software-Stacks stehen noch aus.

Wichtige Unterlagen:

- [Fehlerregister](bugs.md)
- [Aufgaben und Reihenfolge](todo.md)
- [Portierungsplan](porting.md)
- [Technische Projektdokumentation](PROJECT_DOCUMENTATION.md)
- [Staging mit Nginx](deploy/staging/README.md)

---

## Ursprüngliche Projektbeschreibung

![Contentify Logo](http://www.contentify.org/img/hero_small.png)

## Contentify CMS - v3.2 ALPHA

[![Build Status](https://img.shields.io/travis/Contentify/Contentify.svg?style=flat-square)](https://travis-ci.org/Contentify/Contentify)
[![Laravel](https://img.shields.io/badge/Laravel-7-orange.svg?style=flat-square)](http://laravel.com)
[![Source](http://img.shields.io/badge/source-Contentify/Contentify-blue.svg?style=flat-square)](https://github.com/Contentify/Contentify)
[![License](http://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://tldrlegal.com/license/mit-license)

[Contentify](http://contentify.org/) is an esports CMS based on the PHP framework Laravel 7.
Build your gaming website with a modern CMS.

- [x] Technologically advanced gaming CMS
- [x] High quality code and documentation
- [x] Based upon the most popular PHP framework
- [x] Ready for mobile devices
- [x] Easy to use but yet powerful
- [x] Free and open source
- [x] Tons of features

### Get the production version

**Download it here**: [3.1](https://github.com/Contentify/Contentify/releases/tag/v3.1)

To install Contentify please follow the instructions in the [wiki](https://github.com/Contentify/Contentify/wiki/Installation).

### Get the developer version

Clone this repository via git and switch to the `3.2-dev` branch. 
Via console, go to the Contentify directory and run `php composer.phar install`. 
Then follow the instructions in the [wiki](https://github.com/Contentify/Contentify/wiki/Installation).

**ATTENTION**: PHP 8 is supported but requires running `composer install --ignore-platform-reqs`, because
some dependencies do not officially support PHP 8.

### Update

To update from v3.1 to 3.2:
There is no real update. Install 3.2 and then add all your data and changes from 3.1.

**Changes**: [Changelog.md](changelog.md)

### Demo

Currently, there is no demo version.

### Support

You can get free support via GitHub's [issue](https://github.com/Contentify/Contentify/issues) section 
or via [e-mail](mailto:contact@contentify.org). 

### Contribution

Contributions welcome! [Learn more...](CONTRIBUTING.md)

### Local technical assessment

Local workstream version: **0.5.1**
Last updated: **2026-09-06 21:27 CEST**

This checkout was reviewed against current PHP, Composer, Node.js and Laravel
support levels. The result is **not production-ready without modernization**.
The upstream default branch is the unfinished `3.2-dev` / v3.2 ALPHA branch.
Bad Hippo continues from that baseline as `3.3-dev`; the separate `0.4.0`
identifier versions our individual, staged changes.

The historical baseline is installed on an internal staging host behind
Nginx. PHP 7.4/Laravel 7 and MariaDB are
isolated in containers; this is the migration workshop, not a public release.
The first interactive administrator-login failure was diagnosed and corrected
on staging. Account state and session storage are healthy; the secret did not
survive Docker Compose interpolation unchanged during installation. The first
successful browser login also exposed internal-hostname links in the admin
navigation. Both findings and their remaining deployment work are in `bugs.md`.
The staging packaging error that removed Contentify's `public/vendor` assets
and admin icons is resolved in `0.2.3`; Font Awesome, the remaining browser
libraries and the expected Bootstrap Glyphicons are present and browser-tested.
Version `0.2.4` prevents the cached admin navigation from retaining Docker's
internal `nginx` hostname; its base URL is now resolved for each client request.
Version `0.2.5` corrects the shared uploader so every configured file field is
processed. Teams can now save logo and banner together, including a banner when
the earlier logo field is empty. The fix is covered by unit and staging smoke tests.
Version `0.2.6` makes the disk-space check safe on restricted hosting: unavailable
filesystem information is shown as unknown instead of crashing the dashboard.
Version `0.3.0` begins the controlled modernization: Laravel remains on major
version 6 and PHP remains on 7.4, while the framework is raised from 6.20.30 to
the final Laravel-6 patch 6.20.45 together with its resolved dependency set.
The candidate passes Composer validation, Artisan boot, PHP lint, five unit
tests and both staging smoke tests. It is installed on staging; the homepage,
login and required browser assets return HTTP 200. It still has known
advisories and is not a public release.
Version `0.4.0` adds a separately labelled Bad-Hippo dashboard feed next to the
unchanged Contentify original feed. Every Bad-Hippo entry and its source heading
links to `Bad-Hippo-com/Contentify` on GitHub. Remote feed content is validated
before it is rendered.
Version `0.4.1` places the newer Bad-Hippo feed above the historical original
feed in the administrator dashboard.
Version `0.5.0` raises only the framework axis from Laravel 6.20.45 to 7.30.7.
PHP deliberately remains 7.4. The exact Composer candidate passes all focused
unit and staging smoke tests; its production audit is reduced from 39 to 12
advisories, so this remains an internal migration rung rather than a release.
The same image is running on staging: the authenticated dashboard, 512 enabled
routes, login page, required assets and both regression smoke tests pass.
Version `0.5.1` mirrors default Laravel records into two independent outputs:
the detailed daily JSON operations log and the classic `laravel.log` consumed
by the existing administrator log page. Clearing the display copy does not
remove the protected central component logs. The deployed staging image writes
both formats as `www-data`; the administrator viewer displays the verified
classic entry while the matching JSON record retains environment, container,
build and request context.

See:

- [PROJECT_DOCUMENTATION.md](PROJECT_DOCUMENTATION.md) for evidence and the feasibility decision
- [bugs.md](bugs.md) for confirmed defects and risks
- [todo.md](todo.md) for the modernization backlog
- [porting.md](porting.md) for the proposed migration path

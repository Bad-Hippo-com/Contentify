# Contentify defect and risk register

Local workstream version: **0.13.0**
Last updated: **2026-09-07 14:44 CEST**
Scope: upstream commit `5bd21fb7879cf0fbede159a6dc71d0554c8d2bde`

## Open blockers

### BUG-038 - GitHubs Raw-CDN hält alten Bad-Hippo-Feed fest

Severity: **medium**
Status: **resolved in 0.13.0, 2026-09-07 14:33 CEST**

Nach dem Push lieferte GitHubs Raw-CDN dem Staging-Server weiterhin die vorige
Feed-Datei, obwohl `main` bereits den Laravel-13-Eintrag enthielt. Der Browser
zeigte deshalb Laravel 12 weiterhin als neueste Meldung. Die Feed-URL trägt nun
die Standkennung `0.13.0`, und der unabhängige Anwendungscache steigt auf `v3`.
Damit wird der neue Inhalt sofort geladen, ohne den Originalfeed zu verändern.

### BUG-037 - Alter CSRF-Eigenbau umgeht Laravels neuen Origin-Schutz

Severity: **high**
Status: **resolved in 0.13.0, 2026-09-07 13:49 CEST**

Contentifys eigene Middleware implementierte nur den historischen Tokenvergleich
und hätte Laravels neuen Schutz gegen fremde Request-Origins nicht übernommen.
Sie erweitert nun `PreventRequestForgery` und delegiert die Standardprüfung an
Laravel 13. Die bestehende Drei-Sekunden-Spamsperre bleibt als schmale Hülle
erhalten. Ab- und Anmeldung wurden im echten Browser erneut geprüft.

### BUG-036 - Sicherer Laravel-13-Cache blockiert bestehenden Feed-Cache

Severity: **high**
Status: **resolved in 0.13.0, 2026-09-07 13:49 CEST**

Laravel 13 deserialisiert Cache-Objekte nur noch anhand einer ausdrücklichen
Klassenliste. Contentifys bereits gespeicherter Dashboard-Feed enthält normale
`stdClass`-Objekte und erzeugte deshalb zunächst unvollständige Objekte. Die
Konfiguration erlaubt gezielt nur `stdClass`, nicht beliebige PHP-Klassen. Der
kopierte bestehende Cache funktioniert danach ohne Löschen; der Fehler bleibt
im Kandidatenlog als nachvollziehbarer Befund erhalten.

### BUG-035 - Watson-Observer erzeugt Modell während Laravel-13-Modellboot

Severity: **high**
Status: **resolved in 0.13.0, 2026-09-07 13:49 CEST**

`watson/validating` registrierte seinen Observer über `static::observe(new
ValidatingObserver)`. Laravel 13 verhindert zu Recht eine zweite Instanz
desselben Modells während seines Bootvorgangs und brach mit einer
`LogicException` ab. Contentifys gemeinsame Modellbasis registriert die beiden
benötigten Observer-Methoden nun direkt als Modellereignisse. Paketquellcode und
Validierungsverhalten bleiben unverändert; Modell-, Datenbank- und Browsertests
decken die Korrektur ab.

### BUG-034 - Benannte PHP-Argumente brechen Contentifys Controller-Aufruf

Severity: **high**
Status: **resolved in 0.12.1, 2026-09-07 12:42 CEST**

Contentifys überschriebene `BaseController::callAction()` übergab Laravels
assoziative Routenparameter direkt an `call_user_func_array()`. Unter PHP 8
wurden Schlüssel wie `user` und `slug` dadurch als benannte Argumente
interpretiert. Controller mit abweichend benannten Zielargumenten brachen mit
`Unknown named parameter` ab. Die Übergabe verwendet nun wie Laravels eigener
Controller positionsbasierte Werte. Ein Regressionstest und die erneute
Browserprüfung aller 36 Adminbereiche sowie des Benutzerprofils sichern dies ab.

### BUG-033 - Sentinel 8 blockiert Laravel 12

Severity: **high**
Status: **resolved in 0.12.0, 2026-09-07 12:10 CEST**

Der erste Laravel-12-Löserlauf scheiterte nachvollziehbar, weil Sentinel 8.0.0
`illuminate/support ^11` verlangt. Eine lokale Änderung ist nicht erforderlich:
Die offizielle Sentinel-Version 9.0.0 unterstützt PHP ab 8.3 und Illuminate 12.
Contentify verwendet deshalb regulär Sentinel 9; Dienstauflösung, Benutzerzugriff,
Anmeldeseite und bestehende authentifizierte Sitzung wurden im Kandidaten geprüft.

### BUG-032 - Root-CLI-Prüfung sperrt das zentrale Laravel-Log

Severity: **high**
Status: **resolved in 0.11.1, 2026-09-07 11:10 CEST**

Eine als Container-Root ausgeführte Artisan-/Tinker-Prüfung erzeugte die
tägliche zentrale Logdatei mit Besitzer `root:root`. PHP-FPM lief als
`www-data` und konnte diese Datei danach weder öffnen noch korrigieren; beim
nächsten Fehler überlagerte eine `UnexpectedValueException` die eigentliche
Ausnahme. Die betroffenen Anwendungs- und PHP-FPM-Dateien wurden auf `www-data`
zurückgesetzt. Der
App-Dienst läuft ab 0.11.1 vollständig als `www-data`, und die Betriebsanleitung
schreibt denselben Benutzer für alle Laravel-CLI-Prüfungen vor.

### BUG-031 - Umbenanntes Match-Modell erzeugt falschen Formular-Viewnamen

Severity: **high**
Status: **resolved in 0.11.0, 2026-09-07 11:00 CEST**

`/admin/matches/create` antwortete sowohl auf Laravel 10 als auch im ersten
Laravel-11-Kandidaten mit HTTP 500, weil der generische Controller nach der
PHP-8-Umbenennung von `Match` zu `GameMatch` den nicht vorhandenen View
`admin_matches_form` ableitete. Der Matches-Controller bindet nun wie der
ursprüngliche Ablauf ausdrücklich `admin_form`. Ein Regressionstest hält diesen
Viewvertrag unabhängig vom Modellnamen fest.

### BUG-030 - Contentifys Carbon-Unterklasse liest entfernte Carbon-3-Eigenschaft

Severity: **high**
Status: **resolved in 0.11.0, 2026-09-07 10:50 CEST**

Die Formularansichten riefen `Contentify\Carbon::date()` auf. Diese Methode las
direkt Carbons frühere statische Eigenschaft `$toStringFormat`, die Carbon 3
entfernt hat. Dadurch antworteten unter anderem die Erstellseiten für News,
Matches und Seiten mit HTTP 500. Die Contentify-Unterklasse verwendet nun
direkt das bereits benutzerspezifisch übersetzte Datumsformat. Ein
Regressionstest prüft Datum, Datum/Uhrzeit und gerichtete Carbon-3-Differenzen.

### BUG-029 - Laravel Collective HTML endet upstream bei Laravel 10

Severity: **high**
Status: **resolved as controlled bridge in 0.11.0, 2026-09-07 10:26 CEST**

`laravelcollective/html` 6.4.1 erlaubt Illuminate nur bis Laravel 10 und
blockierte die Laravel-11-Auflösung. Contentify verwendet die Form- und
HTML-Fassaden in zahlreichen bestehenden Templates. Ein gleichzeitiger
Template-Umbau würde die isolierte Framework-Stufe unnötig vergrößern. Der
exakte MIT-lizenzierte Upstream-Commit liegt daher als lokale Version 6.4.2 im
Projekt. Neben Composer-Metadaten und Herkunftsdokumentation wurden genau zwei
Konstruktorsignaturen ohne Verhaltensänderung an PHP 8.5 angepasst.
Der Austausch durch eine gepflegte Lösung bleibt eine eigene Aufgabe.

### BUG-028 - Laravel 11 lädt unter PHP 8.5 doppelte Framework-Konfiguration

Severity: **medium**
Status: **resolved in 0.11.0, 2026-09-07 10:26 CEST**

Laravel 11 ergänzt standardmäßig die Konfiguration seiner neuen schlanken
Anwendungsstruktur. Contentify besitzt bereits einen vollständigen, gepflegten
Konfigurationsbaum. Das zusätzliche Laden der Framework-Datenbankvorgaben
wertete unter PHP 8.5 zweimal die veraltete PDO-MySQL-Konstante aus. Der
vollständige Contentify-Konfigurationsbaum wird nun ausdrücklich allein
verwendet; Artisan startet danach ohne diese Deprecations.

### BUG-027 - Laravel 10 entfernt die Eloquent-Eigenschaft `$dates`

Severity: **high**
Status: **resolved in 0.10.0, 2026-09-07 09:45 CEST**

Contentify verwendete die entfernte `$dates`-Eigenschaft in 40 Modellen. Unter
Laravel 10 wären Felder wie `played_at`, `published_at` und `start_at` dadurch
nicht mehr zuverlässig als Datumsobjekte behandelt worden. Alle Definitionen
wurden ohne Änderung der Datenbankspalten auf explizite `datetime`-Casts
überführt. Ein Regressionstest prüft die kritischen Match-, Cup- und Newsfelder.

### BUG-026 - Steam-Authentifizierung endet upstream bei Laravel 9

Severity: **high**
Status: **resolved as controlled bridge through 0.11.0, 2026-09-07 10:26 CEST**

Das aufgegebene Paket `invisnik/laravel-steam-auth` 4.4.0 erlaubt Illuminate
nur bis Laravel 9 und blockierte die Laravel-10-Auflösung. Weil Contentify den
Steam-OpenID-Ablauf direkt verwendet, wäre ersatzloses Entfernen ein
Funktionsverlust. Der exakte MIT-lizenzierte Upstream-Stand liegt daher als
lokale Version 4.4.1 im Projekt; geändert wurden ausschließlich Composer-
Metadaten für PHP 8.5/Laravel 10 und anschließend Laravel 11. Die lokale
Brückenversion ist nun 4.4.2; der PHP-Paketcode blieb unverändert. Ein
späterer Austausch gegen eine gepflegte Implementierung bleibt erforderlich.

### BUG-025 - Caffeinated Modules endet offiziell bei Laravel 8

Severity: **high**
Status: **resolved as controlled bridge through 0.11.0, 2026-09-07 10:26 CEST**

Contentifys 44 Module hängen an `caffeinated/modules`. Die letzte Ausgabe 6.3.1
stammt von 2021 und erlaubt Illuminate nur bis Version 8, wodurch Composer den
Laravel-9-Kandidaten korrekt blockierte. Ein unmittelbarer Wechsel des gesamten
Modulsystems wäre keine isolierte Framework-Stufe. Version 0.9.0 übernimmt daher
den MIT-lizenzierten Stand unverändert als lokale Version 6.3.2 und erweitert
nur dessen Plattformvertrag auf PHP 8.5 und anschließend Illuminate 10. Die
lokale Brückenversion ist jetzt 6.3.4 und erlaubt Illuminate 11. Die Herkunft ist im
Paket dokumentiert. Modulverwaltung, 44 Modul-Bootstraps, 515 Routen und die
authentifizierte Admin-Modulseite funktionieren im Kandidaten. Ein späterer
Austausch bleibt als eigene Architekturaufgabe offen.

### BUG-024 - Laravel 8 erzeugt unter PHP 8.5 Framework-Deprecations

Severity: **high**
Status: **resolved by Laravel 9 rung 0.9.0, 2026-09-07 08:30 CEST**

Die Anwendung läuft mit PHP 8.5.10, Laravel 8.83.29 erzeugt dabei jedoch viele
Hinweise zu implizit-nullbaren Parametern aus dem Framework-Code. Eigene
Vorkommen wurden beseitigt; der vollständige First-Party-Syntaxlauf meldet null
Deprecations. Die Framework-Hinweise werden bewusst weder unterdrückt noch im
Vendor-Verzeichnis gepatcht, sondern über die vorhandenen PHP- und Laravel-Logs
gespeichert. Der isolierte Laravel-9.52.21-Kandidat erzeugt diese Framework-
Deprecations nicht mehr; 781 Syntaxprüfungen und die vollständige Browserstrecke
blieben ohne neue Warnung.

### BUG-023 - PHP-8.5-Abbild versuchte OPcache doppelt zu installieren

Severity: **high**
Status: **resolved in 0.8.0, 2026-09-07 07:35 CEST**

Das offizielle PHP-8.5-FPM-Abbild enthält Zend OPcache bereits. Die bisherige
Erweiterungsliste versuchte OPcache erneut zu kompilieren und brach den Build
mit fehlenden Moduldateien ab. OPcache wurde aus `docker-php-ext-install`
entfernt; die eingebaute Version 8.5.10 ist zur Laufzeit aktiv.

### BUG-022 - Alte Nette-Abhängigkeiten sperrten PHP 8.5

Severity: **high**
Status: **resolved in 0.8.0, 2026-09-07 07:35 CEST**

`nette/schema` 1.2.5 erlaubte höchstens PHP 8.3 und `nette/utils` 3.2.10 nur PHP
kleiner 8.4. Ein normaler Composer-Lauf auf PHP 8.5 war deshalb nicht möglich.
Der gezielte Lock-Update auf Schema 1.3.6 und Utils 4.1.5 beseitigt die Schranke.
Die finale Anwendung besteht `composer check-platform-reqs --no-dev` ohne
ignorierte Plattformanforderungen.

### BUG-021 - Besucher-IP hängt von der Prozessumgebung ab

Severity: **high**
Status: **resolved in 0.7.1, 2026-09-07 06:44 CEST**

Die historische Middleware las die Besucher-IP mit `getenv('REMOTE_ADDR')`.
Im isolierten PHP-Testserver lieferte dies `false`, obwohl Laravel im Request
eine IP führen kann. MariaDB verglich deshalb die Textspalte `visits.ip` mit
dem Zahlenwert `0` und brach mit SQLSTATE 22007 ab. Der echte Nginx/FPM-Pfad
war davon nicht betroffen. Version 0.7.1 verwendet für Besucher-, Kontakt- und
Bewerbungsdaten dennoch einheitlich Laravels Request-IP. Startseite und die
echte Anmeldung `/auth/login` antworten in getrennten Sitzungen wiederholt mit
HTTP 200. Der zusätzliche Aufruf `/login` bestätigt unabhängig davon weiterhin
BUG-017: unbekannte Routen liefern derzeit fälschlich HTTP 500 statt 404.

### BUG-020 - Cup-Siegeraktualisierung verwendet eine undefinierte Variable

Severity: **high**
Status: **open; bei der PHP-8-Abschlussprüfung am 2026-09-07 06:37 CEST erfasst**

Die bestehende Methode `CupMatch::updateWinner()` ruft mehrfach Methoden und
Eigenschaften über `$match` auf, obwohl diese Variable in der Methode nicht
definiert wird. Der betroffene Cup-Ablauf kann deshalb beim Aktualisieren eines
Siegers mit einer Ausnahme abbrechen. Der Befund stammt bereits aus dem
Originalcode und ist unabhängig von der Umbenennung des Modells. Die Reparatur
erfolgt getrennt mit einem gezielten Cup-Regressionsfall.

### BUG-001 - PHP 8 is not supported despite the documentation claim

Severity: **blocker**
Status: **resolved as migration rung 0.7.0, 2026-09-07 06:21 CEST**

PHP 8 reserves `match` as a language keyword. Contentify declares two classes
named `Match` and references them throughout the Matches and Cups modules.
PHP 8.5.10 therefore reports nine syntax failures among 726 project PHP files.
Representative failure:

`app/Modules/Matches/Match.php:38: unexpected token "match", expecting identifier`

`php artisan --version` exits with code 255 on PHP 8.5.10. The upstream README
and installation wiki nevertheless claim PHP 8 support when Composer is run
with `--ignore-platform-reqs`. Ignoring dependency constraints cannot repair
invalid PHP syntax.

Version `0.6.0` entfernt deshalb die irreführende PHP-8-Freigabe aus
`composer.json` und erlaubt für diese Laravel-8-Stufe bewusst nur PHP `^7.3`.
Version `0.7.0` benennt die beiden PHP-Modelle gezielt in `GameMatch` und
`CupMatch` um. Das normale Modell hält seine historische Tabelle `matches`
explizit fest; das Cup-Modell verwendet weiterhin `cups_matches`. Sämtliche
Controller, Relationen, Typangaben und statischen Aufrufe wurden angepasst,
ohne URLs oder Datenbanktabellen umzubenennen. Unter PHP 7.4 bestand der Umbau
zunächst 688 Syntaxprüfungen und zwölf Unit-Tests mit 42 Assertions. Danach
bestand derselbe Stand unter PHP 8.0.30 Composer-Installation ohne ignorierte
Plattformanforderungen, dieselben Syntax- und Unit-Prüfungen, beide Smoke-Tests,
512 Routen und echte Datenbankabfragen über beide Modelle. BUG-001 ist damit
für PHP 8.0 behoben. Version `0.8.0` bestätigt den reparierten Stand zusätzlich
auf PHP 8.5.10 mit 735 Syntaxprüfungen, regulärer Composer-Installation und
laufendem Nginx/FPM-Staging.

### BUG-002 - Production dependencies contain known vulnerabilities

Severity: **blocker**
Status: **resolved in 0.12.0, 2026-09-07 12:10 CEST**

Nach der kontrollierten Laravel-12-Stufe meldet `composer audit --locked`
**keine bekannte Sicherheitslücke** mehr. Die drei zuletzt in Laravel 11.56.1
vorhandenen Meldungen werden durch Laravel 12.69.1 geschlossen. Die technische
Public-Freigabe bleibt dennoch bis zur sauberen Installation und Abnahme auf dem
getrennten Testsystem gesperrt.

### BUG-003 - Current Composer installation is not reproducible

Severity: **high**
Status: **partially resolved; modern Composer required, 2026-09-06 20:17 CEST**

- The bundled `composer.phar` is obsolete, emits extensive deprecation output
  on PHP 8.5 and does not provide the `audit` command.
- Version `0.3.0` regenerates `composer.lock`; current Composer now reports
  `composer.json` and the lock as valid with `--strict`.
- Version `0.8.0` aktualisiert die beiden blockierenden Nette-Pakete und setzt
  die PHP-Anforderung auf `~8.5.0`; Installation und Plattformprüfung laufen
  nun ohne ignorierte Anforderungen.
- Composer meldet noch ein aufgegebenes Produktionspaket: `oyejorge/less.php`.
  Laravel Collective HTML und Steam-Authentifizierung werden seit 0.11.0 als
  kontrollierte lokale Brücken geführt und bleiben als technische Schuld
  dokumentiert.

### BUG-013 - Laravel-6 patch level was below available security fixes

Severity: **high**
Status: **resolved as migration rung 2026-09-06 20:17 CEST**

The historical lock selected Laravel 6.20.30 although compatible security fixes
exist in the same framework major. Version `0.3.0` regenerates the lock on the
real PHP 7.4 baseline and selects Laravel 6.20.45 plus its compatible dependency
set. The isolated candidate passed strict Composer validation, Artisan boot,
five unit tests with 16 assertions, both smoke tests and a complete first-party
PHP syntax pass. This resolves only the outdated Laravel-6 patch level; BUG-001
and BUG-002 remain open until the later PHP/Laravel rungs remove the unsupported
stack and all advisories.

### BUG-016 - Laravel 7 required explicit application compatibility changes

Severity: **high**
Status: **resolved as migration rung 0.5.0, 2026-09-06 20:51 CEST**

The first Laravel-7 dependency probe stopped during package discovery because
Contentify's exception handler still type-hinted `Exception`, while Laravel 7
requires `Throwable`. The application handler now uses `Throwable` for report
and render. The Laravel-7 session-cookie default is adopted and all three own
Artisan commands return an explicit integer success code. Sentinel is raised
from 3.0.4 to 4.0.0 because the former restricts Illuminate Support to version
6. The isolated PHP-7.4 candidate boots as Laravel 7.30.7, lists 540 routes,
passes eight focused unit tests with 27 assertions, both smoke tests and the
complete first-party syntax pass. The sole full-suite failure remains the
pre-existing placeholder feature test that expects the uninstalled test root
to answer with HTTP 200.

### BUG-019 - Eigener Übersetzer verwendete entfernte Laravel-Interna

Severity: **high**
Status: **resolved as migration rung 0.6.0, 2026-09-06 22:05 CEST**

Contentifys `Translator::makeReplacements()` rief die geschützte Framework-
Methode `sortReplacements()` auf. Laravel 8 entfernte diese Methode, wodurch
der erste echte Seitenaufruf mit HTTP 500 und `BadMethodCallException` endete.
Version `0.6.0` sortiert die Platzhalter lokal weiterhin nach absteigender
Namenslänge. Damit bleibt das alte Verhalten erhalten und kurze Schlüssel
überschreiben keine Präfixe längerer Schlüssel. Ein fokussierter Unit-Test
sichert genau diesen Fall ab; Startseite und Login antworten danach mit HTTP
200 und die authentifizierten Admin-Seiten funktionieren weiter.

### BUG-017 - Unknown routes are returned as server errors

Severity: **medium**
Status: **open; confirmed on staging 2026-09-06 21:01 CEST**

The original production exception handler renders its generic HTTP-500 view
for every exception other than `ModelNotFoundException`. Consequently an
unknown route such as `/login` and an absent static file return HTTP 500 instead
of 404. The real Contentify login route `/auth/login` and the Font Awesome 5
assets are present and return HTTP 200, so this is not a Laravel-7 or missing-
asset regression. A focused exception-handler test and correct handling of
Symfony's `NotFoundHttpException` are still required.

### BUG-018 - Admin log page reads an obsolete file

Severity: **medium**
Status: **resolved in 0.5.1, 2026-09-06 21:27 CEST**

`AdminConfigController::LOG_FILE` is hard-coded to
`storage/logs/laravel.log`. Since the central logging work, Laravel writes
daily structured files such as
`/var/log/contentify/application-2026-09-06.log`; PHP-FPM, Nginx and the jobs
runner use their own files below the same root. The legacy file does not exist,
so `/admin/config/log` always displays the translated empty-log notice even
though the central files are populated. The page needs a read-only, bounded
viewer for the current application log. Version `0.5.1` keeps the detailed
daily JSON application log and adds a classic single-file `legacy` channel.
The default stack writes each ordinary Laravel record to both channels. The
existing page and its clear action therefore operate only on the display copy;
central application, PHP, Nginx and job logs cannot be deleted through it. The
staging browser check displays the clean 84-byte validation record; the matching
central JSON record contains the `0.5.1` build and runtime context.

### BUG-014 - Remote dashboard feed was rendered without validation

Severity: **high**
Status: **resolved in 0.4.0, 2026-09-06 20:28 CEST**

The original dashboard trusted remote JSON fields and rendered message text and
icon names as raw HTML. A compromised or malformed feed could therefore inject
markup into an authenticated administrator page. The previous single cache key
also meant that one failed source could suppress the entire feed output.

Version `0.4.0` normalizes each remote message, accepts only HTTP(S) links and
safe icon identifiers, skips incomplete records and escapes message text in the
Blade view. Original Contentify and Bad Hippo now use independent cache entries
and failure handling. This preserves the original feed while adding our clearly
labelled GitHub-backed feed. Three focused regression tests pass as part of the
eight-test, 27-assertion unit suite.

### BUG-015 - Newer Bad-Hippo feed appeared below the historical feed

Severity: **low**
Status: **resolved in 0.4.1, 2026-09-06 20:38 CEST**

Both feeds rendered correctly in `0.4.0`, but the original source was declared
first and therefore occupied the upper dashboard position despite its newest
message dating from 2020. Version `0.4.1` makes the maintained Bad-Hippo source
the first dashboard feed and keeps Contentify Original directly below it.

### BUG-004 - Front-end dependency installation fails by default

Severity: **high**
Status: **open**

With Node.js 24.15.0 and npm 11.12.1, `npm install` fails with `ERESOLVE`:
`grunt-contrib-watch@0.6.1` requires Grunt `~0.4.0`, while the project requests
Grunt `^1.3.0`. Installation only succeeds with `--legacy-peer-deps`.

The generated npm dependency tree contains **23 vulnerabilities**: 3 critical,
15 high and 5 moderate. Both direct build dependencies
`grunt-contrib-less@1.x` and `grunt-contrib-watch@0.6.x` require breaking
upgrades. Several transitive packages are deprecated or unsupported.

### BUG-005 - The automated tests do not validate the CMS

Severity: **high**
Status: **open**

Only two placeholder tests exist. On the last syntactically compatible PHP
release, PHP 7.4.33, the unit test `assertTrue(true)` passes and the sole feature
test fails because `/` returns 404 instead of 200. There are no meaningful
module, authorization, installer, upload, database, or security regression
tests.

### BUG-006 - Container definitions are unsafe and non-reproducible

Severity: **high**
Status: **open**

- `php:7-apache`, `mysql:latest`, `adminer` and `contentify:latest` are unpinned.
- PHP 7 is end-of-life; PHP 7.4 lost security support in November 2022.
- The Dockerfile recursively grants mode `777` to `storage`, `bootstrap/cache`
  and all of `public`.
- The Compose file publishes the CMS on host port 80 and Adminer on 8080,
  while embedding development database credentials.
- There is no container health check, persistent database volume, application
  environment contract, migration/installer gate, or CI build verification.

### BUG-007 - Production defaults and installation documentation are stale

Severity: **medium**
Status: **open**

`.env.example` defaults to `APP_ENV=local`, `APP_DEBUG=true`, a blank app key,
root database access without a password, Mailtrap host settings and obsolete
Laravel 6 mail variable names. The wiki was last edited in 2021, contains the
impossible requirement `PHP 7.6.5`, recommends broad CHMOD 777 permissions and
claims PHP 8 compatibility contradicted by the source.

### BUG-008 - Comment update passes undefined view variables

Severity: **low**
Status: **open**

`Contentify\Comments::update()` returns the `comments.comment` view using
`compact('comment', 'foreignType', 'foreignId')`, but the method defines only
`$comment`. This can emit undefined-variable warnings and indicates an
uncovered code path.

### BUG-009 - Docker Compose silently changes dollar signs in staging secrets

Severity: **high**
Status: **mitigated on staging; deployment path open, 2026-09-06 18:43 CEST**

The requested administrator password ended in two dollar signs. During the
staging installation Docker Compose reduced that sequence to one dollar sign
while loading `.env.staging`. The installer therefore created a valid password
hash for a different 11-character value. The account itself is active,
unbanned, assigned to `super-admins`, and its session directory is writable.

Five failed login attempts then activated Sentinel's IP and user throttles.
The default throttle interval is 900 seconds and the threshold is five
attempts. The browser correctly displayed the remaining lock time, but the
English exception text is exposed unchanged on the German login page. Secrets
containing Compose interpolation characters require an explicit round-trip
check before the installer is considered successful.

The staging administrator hash was reset to the exact requested value and only
the five user plus six client-IP throttle records were removed. A real browser
login and the authenticated admin dashboard then succeeded. The deployment
path remains defective until clean installs preserve such secrets unchanged.

## Prüfung der Original-Issues

### Erste Prüfung der offenen Original-Issues

Status: **laufend, sieben Issues geprüft 2026-09-06 19:55 CEST**

- `#645` und `#663`: derselbe bestätigte PHP-8-Blocker durch die reservierte
  Klasse `Match`; noch nicht behoben, bei uns als Issue `#4` geführt.
- `#658`: Docker-/Installationsweg im Fork grundsätzlich reproduziert; saubere
  Testinstallation und GHCR-Veröffentlichung fehlen noch.
- `#613`: OpenGraph-Typfehler wurde bereits von Chris in Upstream-Commit
  `7e0294ec` für `3.2-dev` korrigiert; Browser-Charakterisierung folgt.
- `#614`: Valorant- und weitere Spielsymbole wurden bereits von Chris in
  Upstream-Commit `4630a7aa` für `3.2-dev` ergänzt.
- `#650`: reproduzierter Mehrfachupload-Fehler. `Uploader::uploadModelFiles()`
  beendete seine Schleife bereits nach dem ersten konfigurierten Dateifeld.
  Der Fix ist in Bad Hippo `0.2.5` enthalten und auf Staging getestet.
- `#624`: die ungefangene Abfrage von `disk_free_space()` konnte auf
  eingeschränktem Hosting das Dashboard abbrechen. Bad Hippo `0.2.6` behandelt
  nicht verfügbare Angaben als unbekannt und behält echte Platzwarnungen bei.

In den ersten fünf Original-Issues wurde der Befund veröffentlicht; die
verifizierte Lösung für `#650` folgt mit dem öffentlichen Commit. Jeder Hinweis
nennt den inoffiziellen Community-Status sowie die aktuelle Baseline aus PHP
7.4 und Laravel 6.20.30. Eine PHP-8- oder Produktionsfreigabe wurde ausdrücklich
nicht behauptet.

## Resolved application defects

### BUG-010 - Cached admin navigation exposes the internal Nginx hostname

Severity: **high**
Status: **resolved 2026-09-06 19:03 CEST**

After successful authentication, the admin dashboard itself loads through
the external staging address, but many links in the left navigation point to
`http://nginx/admin/...`. Quick-access links on the same page correctly use the
external address. This makes part of the backend navigation unusable from a
client and suggests that absolute URLs generated during an internal request
were cached without a safe canonical application URL or host boundary.

Resolution: version `0.2.4` caches a neutral base-URL placeholder instead of
the host seen while the menu HTML is generated. The placeholder is replaced
with the current request's application root only when the menu is returned.
This retains support for installations in a subdirectory while preventing an
internal Docker hostname from becoming permanent shared cache content. The
legacy navigation cache entry must be refreshed once during deployment.
The staging regression check generated the cache while forcing
`http://nginx`, confirmed that the cached value contains no such hostname,
then reused that cache with an external staging URL. Browser tests opened
News, Pages and Configuration from the left menu at the staging IP.

### BUG-011 - Only the first configured upload field is processed

Severity: **medium**
Status: **resolved 2026-09-06 19:37 CEST**
Original issue: `Contentify/Contentify#650`

`Uploader::uploadModelFiles()` returned from inside its `foreach` loop. A model
with several configured upload fields therefore processed only the first field,
whether or not that field contained a file. For teams, this meant that `image`
worked but `banner` was ignored; no exception occurred, so Laravel and Nginx had
nothing to log. Permissions and a separate banner folder were not the cause.

Version `0.2.5` returns only after the loop has examined every configured field.
The PHPUnit regression test covers logo plus banner and banner without logo;
PHPUnit 9.5.8 passed both tests with 11 assertions on PHP 7.4. A standalone
smoke test also ran successfully inside the real PHP 7.4/Laravel 6.20.30
staging container for both cases.

### BUG-012 - Disk-space warning can crash on restricted hosting

Severity: **medium**
Status: **resolved 2026-09-06 19:55 CEST**
Original issue: `Contentify/Contentify#624`

The dashboard checked only whether `disk_free_space()` exists, then called it
twice without handling warnings, exceptions or a `false` result. The diagnostics
page used the same unsafe pattern. Hosting restrictions such as `open_basedir`
or an unreadable path can therefore turn an informational check into a Laravel
exception. Removing the warning entirely would also remove useful monitoring.

Version `0.2.6` adds `Contentify\DiskSpace`, calls the native function once with
warning suppression and exception handling, validates the result, and returns
`null` when the host cannot provide it. Dashboard and diagnostics now share the
same behavior. Unit and staging smoke tests cover a readable and a deliberately
missing path.

## Staging defects

### STAGE-006 - Entwicklungsserver lieferte keine REMOTE_ADDR-Umgebung

Severity: **nur Testaufbau**
Status: **resolved during 0.7.0 validation, 2026-09-07 06:21 CEST**

Der isolierte HTTP-Vorlauf mit `php artisan serve` lieferte zunächst HTTP 500,
weil PHPs Entwicklungsserver die Clientadresse zwar in `$_SERVER`, aber nicht
für Contentifys historischen `getenv('REMOTE_ADDR')`-Zugriff bereitstellte.
Dadurch wurde `false` als Besucher-IP an MariaDB gebunden. Beide Ausnahmen
liegen vollständig im zentralen JSON-Log und in der klassischen Admin-Kopie.
Mit einer ausdrücklich gesetzten Test-IP antworteten Startseite und Anmeldung
mit HTTP 200. Der echte Nginx/PHP-FPM-Betrieb setzt die Variable korrekt und
bestand denselben Test ohne Sonderbehandlung; es war kein PHP-8-Laufzeitfehler.

### STAGE-005 - Nginx retains the replaced app container address

Severity: **high during deployment**
Status: **operationally mitigated 2026-09-06 19:44 CEST**

After only `app` and `jobs` were recreated for version `0.2.5`, the already
running Nginx worker continued using the removed app container's resolved IP.
Requests temporarily returned HTTP 502 although the new PHP-FPM container was
healthy. Recreating Nginx resolved `app` again and restored HTTP 200.

The staging runbook now requires Nginx recreation whenever the app container is
replaced. A later delivery-hardening step should make upstream resolution
dynamic or add an atomic deployment procedure so this cannot be omitted.

### STAGE-004 - Admin icon and vendor assets are absent from the image

Severity: **high**
Status: **resolved 2026-09-06 18:57 CEST**

The backend correctly includes Font Awesome from
`vendor/font-awesome/css/all.min.css`, and the repository contains the CSS plus
webfont files. The staging build context does not contain them: the broad
`.dockerignore` entry `vendor` excludes both Composer's root `vendor` directory
and the required public directory `public/vendor`.

Live requests for the Font Awesome CSS and `fa-solid-900.woff2` therefore return
HTTP 500 with Contentify's HTML crash page instead of CSS/font content. The same
exclusion removes jQuery, CKEditor and other browser assets below
`public/vendor`, so the visible missing icons are only the first symptom.
`backend.css` itself returns HTTP 200. Its separate Glyphicons font URL also
returns HTTP 500 because `public/css/fonts` is absent from the upstream tree.

This is a staging packaging defect, not GitHub issue #614: that old issue is
about a Valorant game icon and the current `3.2-dev` source already contains the
corresponding game-icon change.

Resolution: `.dockerignore` still excludes the root dependency tree but now
explicitly re-includes `public/vendor` and all descendants. The 272 tracked
browser assets were restored to the remote source and persistent public volume.
The five missing Glyphicons files were taken unchanged from the official
Bootstrap 3.3.7 npm package. Rebuilt `0.2.3` app and Nginx images both contain
the required assets. Font Awesome CSS/font, jQuery, CKEditor, backend CSS and all
five Glyphicons formats return HTTP 200 with suitable MIME types. A browser
reload after full service recreation displays icons throughout the dashboard.

## Closed during staging installation

### STAGE-001 - PHP-FPM rejected the initial access-log duration format

Severity: **deployment blocker**
Status: **resolved 2026-09-06 18:12 CEST**

PHP-FPM 7.4 rejected the original duration modifier and restarted with exit
code 78. The access format was changed to the PHP 7.4-compatible `%d` field.
The rebuilt app container then remained up and request logging succeeded.

### STAGE-002 - Public runtime data was not shared with Nginx

Severity: **high**
Status: **resolved 2026-09-06 18:17 CEST**

The first container layout shared only `public/uploads`. Contentify can also
write compiled CSS, RSS and share data below `public`. A persistent
`public-data` volume is now mounted read/write by PHP-FPM and read-only by
Nginx, and all installer directory checks pass.

### STAGE-003 - Staging baseline installation

Status: **verified 2026-09-06 18:25 CEST**

Contentify installed 65 database tables, created the administrator and
installation marker, serves the homepage and login page with HTTP 200, accepts
an administrator login, and serves the authenticated backend with HTTP 200.
No new container errors appeared during the final validation interval.

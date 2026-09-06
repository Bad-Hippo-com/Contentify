# Contentify porting plan

Local workstream version: **0.2.4**
Last updated: **2026-09-06 19:23 CEST**

## Decision

A current, supportable Contentify deployment is technically feasible, but it
is a **modernization project**, not a normal installation. The historical
application can be used only as a functional reference. Deploying it unchanged
would require end-of-life PHP 7.4 and would retain known vulnerable packages.

## Environment model

Two systems on separate IP addresses are mandatory:

| System | Purpose | Change policy |
| --- | --- | --- |
| Staging, interne Adresse | Development, upgrades, destructive experiments and failure analysis | Historical baseline installed; may be broken and rebuilt while a step is being developed |
| Test, IP pending | Clean installation and acceptance rehearsal for a release candidate | Receives only a version already proven on staging |

The systems must not share their application database, uploads, cache, session
storage, credentials or application key. Promotion means installing the same
versioned change and migration procedure cleanly on test, not copying an
uncontrolled staging filesystem onto test.

Credentials containing dollar signs or other Compose interpolation characters
must be passed by a mechanism that preserves the exact byte sequence. Every
clean installation must compare the supplied secret to the resulting password
hash without logging the secret, then perform an actual browser login. A mere
successful installer exit is not sufficient.

Each host uses its own `/var/log/contentify` root. Laravel application,
security and job records are daily JSON Lines; PHP, webserver, worker, scheduler
and deployment logs have separate filenames in that same directory. Staging
uses debug-level application logging while test starts at warning level. The
prepared templates and verification procedure are in `deploy/logging`.

## Current staging baseline

Version `0.2.0` is installed on the internal staging host with Nginx 1.26.3,
PHP-FPM 7.4.33, Laravel 6.20.30 and MariaDB 10.11. The application, database,
public runtime files and uploads are persistent where required. The Contentify
job runner is active. Homepage, login, authenticated administrator backend,
65-table database, writable installer directories and central logs were
verified. This closes stage 2 only; it does not approve a public deployment.

## Target-selection rule

There is deliberately **no fixed immediate target of PHP 8.5 and Laravel 13**.
The final stack will be the newest combination that is supported, secure and
demonstrably compatible after the staged migration. Each rung must start, pass
its characterization tests and support a clean install before the next runtime
or framework change begins.

PHP 7.4 and other end-of-life intermediate versions may be used only inside the
isolated migration systems to establish or cross a compatibility rung. They are
never approved as the final public production platform.

## Seven-stage implementation

1. **Provision separation.** Create staging and test on their own IPs, pin the
   database/runtime services, restrict network access, and establish independent
   secrets, data and backups.
2. **Make the historical baseline run.** On staging, reproduce the upstream stack
   with PHP 7.4 and Laravel 6.20.30, complete the installer, load representative
   fixtures and repair only defects that prevent normal historical operation.
3. **Freeze behavior.** Add characterization and smoke tests for all retained
   modules. Record the reproducible staging baseline, create the prerequisites
   independently on test, and prove a clean installation there.
4. **Harden within the current framework.** Update Laravel 6 and compatible
   dependencies as far as safely possible, replace the bundled Composer, remove
   immediately reachable advisories and verify the complete step on staging
   before installing that candidate cleanly on test.
5. **Cross the PHP 8 boundary.** Rename both `Match` classes and references,
   resolve PHP 8 language/runtime changes and validate the complete application.
   This change is isolated from the next Laravel-major upgrade.
6. **Raise Laravel and PHP incrementally.** Move through the necessary Laravel
   major versions and their compatible PHP runtimes one rung at a time. At every
   rung: build and diagnose on staging, then cleanly install and accept on test.
7. **Select and release the operating stack.** Stop only on a supported,
   advisory-clean combination that operates cleanly. Public release is allowed
   only after prerequisites, clean installation and representative operation on
   test finish without unresolved errors.

## Acceptance gates

- All first-party PHP files lint on the chosen supported PHP versions.
- `composer install` works without `--ignore-platform-reqs`.
- Composer and npm audits contain no unresolved production advisories.
- `npm ci` and the asset build work without `--legacy-peer-deps`.
- Tests cover real CMS behavior and pass from a clean database.
- The container stack is pinned, persistent, health-checked and contains no
  embedded credentials or world-writable application tree.
- A clean install, backup, restore and rollback are demonstrated.
- Container build ignores are anchored to repository roots so application
  directories such as `public/vendor` cannot be removed accidentally; smoke
  tests require HTTP 200 and correct MIME types for CSS, JavaScript and fonts.
- Controlled Laravel, PHP and webserver errors arrive in the correct central
  files with environment/host context and pass a real rotation test.

## Estimated effort for full functional parity

The estimate assumes that all 44 existing module directories remain in scope,
the current behavior is preserved, and the result must be suitable for an
internet-facing production deployment. One person-day is eight working hours.

| Work package | Person-days |
| --- | ---: |
| Functional inventory and characterization baseline | 10-15 |
| Reproducible test database, installer and fixtures | 8-12 |
| PHP 8 language compatibility and `Match` refactor | 6-10 |
| Incremental Laravel migration through the required major versions | 30-45 |
| Third-party package replacement and integration work | 18-30 |
| Database, installer and data-upgrade path | 10-18 |
| Front-end build replacement | 6-10 |
| Containers, CI/CD and operational configuration | 8-14 |
| Focused security hardening | 10-16 |
| Meaningful automated regression coverage | 25-40 |
| UAT, stabilization, documentation and release | 15-25 |
| **Base estimate** | **146-235** |
| **20% uncertainty reserve** | **29-47** |
| **Planned total** | **175-282 person-days** |

The planned total corresponds to approximately **1,400-2,256 working hours**.
The reserve covers undocumented behavior, packages without a direct maintained
replacement, installer/data surprises and defects revealed only after real
module tests exist. It does not cover new features or a visual redesign.

### Expected calendar duration

| Staffing | Realistic duration | Notes |
| --- | --- | --- |
| 1 experienced full-time developer | 9-14 months | High key-person and interruption risk |
| 2 experienced full-time engineers | 6-9 months | Some framework work remains sequential |
| 3-person team | 4.5-7 months | Recommended: backend lead, second full-stack engineer, QA/DevOps capacity |

A reduced MVP can only be estimated after choosing which modules to retire. A
provisional core-only scope could save roughly 30-45%, but that would explicitly
forfeit full Contentify feature parity.

### Delivery model: user and Codex working together

Codex can take primary responsibility for repository analysis, repetitive code
migration, dependency work, automated tests, container configuration and the
continuous documentation. The user remains responsible for product decisions,
credentials and infrastructure access, visual acceptance, representative data
and final production approval. This is more productive than one developer
working alone, but it is not equivalent to two autonomous full-time engineers.

For full feature parity, this model is planned at **110-175 human-equivalent
engineering days**, a reduction of roughly 30-40% from the conservative total.
Of this, the user's concentrated involvement is expected to be approximately
**20-35 days** across decisions, reviews, manual tests, infrastructure access and
acceptance. The remaining implementation and verification work is driven by
Codex in reviewable stages.

| Collaboration intensity | Expected calendar duration |
| --- | --- |
| Intensive, regular work with quick decisions and available staging/test systems | 4-6 months |
| Steady part-time collaboration with weekly reviews | 6-9 months |
| Irregular availability or delayed infrastructure/data access | 9-12+ months |

The realistic commitment for planning is therefore **six months**, with a
working range of **four to nine months**. The estimate assumes that each stage
can continue without long approval gaps and that a buildable staging server, a
rebuildable clean test server and representative data are available early.

### Baseline stabilization note - 2026-09-06 19:03 CEST

Before framework upgrades, `0.2.4` removes request-host data from the persistent
admin-navigation cache. This is a baseline bug fix and does not alter PHP,
Laravel, routes or module behavior. The fix must remain covered when proxy and
trusted-host handling are modernized in a later port stage.

## Non-viable shortcut

Running the current code with `--ignore-platform-reqs` is not a port. It only
bypasses dependency checks; PHP 8 still cannot parse the `Match` declarations,
and the vulnerable locked packages remain installed.

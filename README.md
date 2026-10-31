# Grow

[![CI](https://github.com/dan-powell/grow/actions/workflows/ci.yml/badge.svg)](https://github.com/dan-powell/grow/actions/workflows/ci.yml)

A self-hosted dashboard for monitoring sensor devices. Devices post readings to an
API, Grow stores and charts them, and emails alerts when a value goes out of range
or a device stops reporting.

## Features

- **Readings API**: devices `POST` batches of readings, which are processed on the queue.
- **Dashboard**: current figures and history charts per device (Inertia + Vue 3 + Chart.js).
- **Alerts**: per-figure threshold alerts and late-reading alerts, with emails sent when
  an alert triggers and when it resolves. `reading:check` runs hourly.
- **Admin panel**: Filament manages devices, figures, alerts, users and the event log.

## Stack

| Layer      | Tech                                                       |
| :--------- | :--------------------------------------------------------- |
| Backend    | PHP 8.5, Laravel 13, Octane, Horizon (Redis queues)        |
| Admin      | Filament 3                                                 |
| Frontend   | Vue 3, Inertia 2, Vite, Bulma, Chart.js                    |
| Tooling    | PHPUnit 12, Pint, Rector, Telescope, Debugbar              |
| Dev / Ops  | DDEV, Deployer, Ansible (Docker provisioning), GitHub Actions |

## Local development

Requires [DDEV](https://ddev.com).

```bash
ddev start
ddev composer install
cp .env.example .env
ddev exec php artisan key:generate
ddev exec php artisan migrate --seed
ddev exec npm ci
ddev exec npm run build      # or `npm run dev` for Vite HMR
ddev exec php artisan user:add <username> <password>
```

### Checks

These are the same checks CI runs on every pull request:

```bash
ddev exec vendor/bin/pint --test
ddev exec vendor/bin/phpunit
ddev exec npm run build
```

## Readings API

```http
POST /api/reading
Authorization: Basic <user credentials>
Content-Type: application/json

{
  "nickname": "greenhouse-1",
  "timestamp": "2026-10-31T12:00:00Z",
  "readings": { "temperature": 21.4, "humidity": 58 }
}
```

`nickname` identifies the device. Each key in `readings` is matched to one of that
device's configured figures, and unknown keys are ignored.

## Deployment

- **Provisioning**: Ansible playbooks in [`provision/`](provision/) set up the Docker
  host. Copy `inventory.example.yml` to `inventory.yml` to use them.
- **Deploys**: [Deployer](https://deployer.org) ([`deploy.php`](deploy.php)). Copy
  `hosts.yml.example` to `hosts.yml`, then run `vendor/bin/dep deploy`.

## Git workflow

- `master` is the main line. All changes go through short-lived branches
  (`feat/…`, `fix/…`, `chore/…`) and pull requests, and CI must pass before merging.
- Commit messages follow [Conventional Commits](https://www.conventionalcommits.org).
- `production` is the deploy branch Deployer pulls from. Fast-forward it from `master`
  to release.
- Releases are tagged with [SemVer](https://semver.org) and recorded in
  [`CHANGELOG.md`](CHANGELOG.md).

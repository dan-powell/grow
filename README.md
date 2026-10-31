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

### Branches

| Branch       | Purpose                                                                        |
| :----------- | :----------------------------------------------------------------------------- |
| `master`     | Main line. Protected: changes arrive only through pull requests that pass CI.   |
| `production` | Deploy branch Deployer pulls from. Only fast-forwarded to a release tag.       |
| `feat/…`, `fix/…`, `chore/…`, `docs/…` | Short-lived work branches cut from `master`.         |
| `release/vX.Y.Z` | Prepares a release (changelog only).                                       |

### Making a change

1. **Branch** from an up-to-date `master`:
   ```bash
   git switch master && git pull --ff-only
   git switch -c feat/short-description
   ```
2. **Commit** in small, self-contained steps using
   [Conventional Commits](https://www.conventionalcommits.org), for example
   `fix(alerts): resolve late-reading alert on next reading`. Commits are signed.
   Before pushing, tidy up with `git rebase -i master` (or `--fixup` and `--autosquash`).
3. **Update the changelog**: add an entry under `## [Unreleased]` in
   [`CHANGELOG.md`](CHANGELOG.md) for anything user-facing.
4. **Run the checks** locally (see [Checks](#checks)).
5. **Push and open a PR** against `master`. Fill in the template (what, why, how tested).
   - The **PR title must be a Conventional Commit**, because it becomes the commit message on `master`.
6. **Wait for CI**. The `PHP (Pint + PHPUnit)` and `Frontend build` checks are required,
   and the branch must be up to date with `master`.
7. **Squash and merge**, then delete the branch.

### Releasing

Versions follow [SemVer](https://semver.org).

1. Cut `release/vX.Y.Z` from `master`. In `CHANGELOG.md`, rename `## [Unreleased]` to
   `## [X.Y.Z] - YYYY-MM-DD`, add a new empty `## [Unreleased]` above it, and update the
   compare links at the bottom.
2. Open a PR titled `chore(release): prepare vX.Y.Z` and squash-merge it once CI passes.
3. Tag the merge commit on `master` and push the tag:
   ```bash
   git switch master && git pull --ff-only
   git tag -a vX.Y.Z -m "vX.Y.Z"
   git push origin vX.Y.Z
   ```
4. Create a GitHub Release from the tag, using that version's changelog section as the notes.
5. Deploy by fast-forwarding `production` to the tag:
   ```bash
   git switch production && git merge --ff-only vX.Y.Z && git push
   vendor/bin/dep deploy
   ```

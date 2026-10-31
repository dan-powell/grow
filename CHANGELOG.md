# Changelog

All notable changes to this project are documented here.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- `.mailmap` to unify commit author identity.
- README documentation of the pull request and release process.

### Changed

- Upgraded Filament from v3 to v5 (via v4), bringing Livewire 4. Admin resources were
  migrated with Filament's Rector upgrade rules, the "View" user menu item moved to the
  panel configuration, and v3's full-width section layout is preserved.

### Removed

- Unused StyleCI configuration (style is enforced by Pint).

## [1.0.0] - 2026-10-31

First tagged release.

### Added

- GitHub Actions CI running Pint, PHPUnit and the Vite build.
- Pull request template.
- Project README.
- Tests for trusted proxies, API rate limiting, JSON API errors and the `/up` health route.

### Changed

- Adopted the Laravel 13 application structure: routing, middleware and exceptions are
  configured in `bootstrap/app.php`, and the HTTP/console kernels, `RouteServiceProvider`
  and default-only providers, middleware and config files are removed.
- Replaced the Laravel skeleton changelog with this project changelog.

## History before this changelog

Earlier milestones, taken from the git history:

- **2026-10**: upgraded to Laravel 13 and PHP 8.5; added Laravel Boost.
- **2026-01 – 2026-03**: Deployer and Docker deploys, Ansible provisioning, Octane, and a
  test notification command.
- **2023-08**: upgraded to Filament 3; added figures, sortable columns and toggles to the dashboard.
- **2023-05**: upgraded to Inertia 1.0; added log pagination and icons.
- **2023-03**: upgraded to Laravel 10; added the Filament admin panel, switched to Vite,
  moved reading processing onto the queue, and added figure alert notifications.
- **2022-08**: initial build with device readings, charts, alert notifications and timeouts.

[Unreleased]: https://github.com/dan-powell/grow/compare/v1.0.0...HEAD
[1.0.0]: https://github.com/dan-powell/grow/releases/tag/v1.0.0

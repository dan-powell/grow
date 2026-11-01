# AGENTS.md - Repository Execution Guidelines

This document outlines the conventions, build commands, and style guides for agents operating within this Laravel/Vue/Vite codebase managed by DDEV. This file is designed to be comprehensive, aiming for approximately 150 lines of instruction for autonomous execution.

---

## 1. Execution Environment & Setup

All development, testing, and linting operations MUST occur inside the DDEV web container (`ddev-web` service) unless the command is explicitly designed to run on the host (e.g., running `ddev` itself, or host-level Node installation if DDEV's Node environment is insufficient/conflicting).

- **Container Service:** `web` (Invoked via `ddev exec -s web ...`).
- **PHP Version:** 8.5 (In container).
- **Node Version:** 24 (In container, managed by DDEV; set via `nodejs_version` in `.ddev/config.yaml`). Vite 8 requires Node 20.19+.
- **Document Root:** `public` (Laravel's entry point).
- **Container Code Path:** `/var/www/html` (The standard working directory for DDEV commands).

### Essential Commands (Run via DDEV)

All shell commands requiring the project environment (PHP, Composer, Node) must be prefixed with `ddev exec -s web`.

| Purpose | Command Example (within DDEV) | Single Test/Lint Equivalent | Notes |
| :--- | :--- | :--- | :--- |
| **Run All Tests (PHP)** | `vendor/bin/phpunit` | `vendor/bin/phpunit --filter <FQCN>` | PHPUnit is the standard. FQCN = Fully Qualified Class Name. |
| **Run Feature Tests** | `vendor/bin/phpunit --testdox --group feature` | N/A | Tests are tagged with the `#[Group('feature')]` attribute (PHPUnit 12 no longer reads `@group` docblocks). Tests use an in-memory SQLite DB. |
| **Run Unit Tests** | `vendor/bin/phpunit --testdox --group unit` | N/A | Tag with `#[Group('unit')]`. Consistency in tagging tests is mandatory. |
| **Format Code (PHP)** | `vendor/bin/pint` | `vendor/bin/pint --test` | **MUST** be run before any commit. |
| **Refactor (PHP)** | `vendor/bin/rector process` | `vendor/bin/rector process --dry-run` | Config in `rector.php` (PHP 8.5 + Laravel 13 sets). Run Pint afterwards. |
| **Frontend Build** | `npm run build` | N/A | Compiles assets via Vite. |
| **Frontend Watch** | `npm run dev` | N/A | Starts Vite HMR server, typically run outside DDEV if host Node is used. |
| **Dependency Install** | `composer install --no-dev` | `npm ci` | Use `npm ci` for dependency consistency if lockfiles are present. |
| **Database Setup** | `php artisan migrate --seed` | `php artisan migrate:fresh --seed` | Use `--force` only when absolutely certain, never in development scripts. |

---

## 2. Code Style & Conventions

Agents must strictly adhere to styles enforced by existing tools and established Laravel/Vue conventions.

### 2.1. PHP / Backend (Laravel)

1.  **Formatting:** **Laravel Pint** standards are non-negotiable. Use `vendor/bin/pint` to automatically enforce style upon code modification.
2.  **Typing:** All new and substantially modified PHP files **MUST** begin with `declare(strict_types=1);`. Utilize modern PHP 8.5 features (e.g., named arguments, match expressions) idiomatically where appropriate.
3.  **Imports:** Adhere to **PSR-4** structure (`App\\`). Imports must be correctly grouped and ordered: Core, Framework (Laravel/Filament), Library (Spatie, etc.), Project-specific. Sort imports alphabetically within groups.
4.  **Naming:**
    -   Classes, Interfaces, Enums: PascalCase (`UserProfileManager`).
    -   Methods, Properties (public/protected/private): camelCase (`getUserData`).
    -   Constants: UPPER_SNAKE_CASE.
5.  **Error Handling:** Employ standard Laravel exception handling. Wrap external/I/O calls in `try/catch` blocks. Log critical failures using the `Log` facade. Avoid catching bare exceptions; specify exception types (e.g., `QueryException`, `FileNotFoundException`).
6.  **Directory Placement:** New logic must reside in idiomatic locations: Controllers in `app/Http/Controllers`, Services in `app/Services`, Models in `app/Models`, Enums in `app/Enum`, Filament panels/resources in `app/Filament`. Always check existing files in the target directory for existing patterns.

### 2.2. JavaScript / Vue / Frontend

1.  **Tooling:** **Vite** is the mandated build tool. Ensure `package.json` scripts are used for all compilation (`npm run build`).
2.  **Framework:** Vue 3 **Composition API** (`<script setup>`) is the current standard. Older Options API components should only be modified minimally to maintain consistency. Never mix API styles within a single component file.
3.  **State Management:** Inertia.js passes server-side data via props. Avoid complex, separate global state solutions unless absolutely necessary and explicitly justified by performance or complexity metrics.
4.  **HTTP Client:** Use Inertia's router, `<Link>` and `useForm` for page visits and form submissions (Inertia v3 ships its own HTTP client). Axios and lodash are **not** installed; for standalone JSON requests use native `fetch` rather than adding a new HTTP or utility library.
5.  **Styling:** No CSS framework is used. Global styles live in `resources/css` (organised into `abstracts`, `base`, `utilities` and `layout`) and are processed by PostCSS (`postcss-nested`, `postcss-mixins`); component styles belong in the `.vue` file. Avoid inline styles unless dynamic calculations are required.

### 2.3. Configuration & Agent Directives

1.  **DDEV Context:** All environment-specific ports (Vite on 5173) are managed via `.ddev/config.yaml`. Agents must not attempt to run services bound to arbitrary ports without verifying routing/exposure in this file. If a port conflict arises, an agent should suggest modifying `.ddev/config.yaml` via explicit user instruction.
2.  **Agent Rules:** **NO** specific agent configuration files (like `.cursorrules`) were detected. Agents must rely solely on the established codebase patterns and the rules in this document.
3.  **File Creation:** When scaffolding new features, adhere strictly to Laravel/Vue structure. Frontend code lives in `resources/inertia` (aliased as `@` in `vite.config.js`): pages in `resources/inertia/Pages`, reusable components in `resources/inertia/Components`, layouts in `resources/inertia/Layouts`, and Vue directives in `resources/inertia/Directives`. Global CSS lives in `resources/css`.
4.  **Documentation:** When adding new features, maintain clear documentation inline, particularly for complex business logic. This `AGENTS.md` must be updated if new standard commands or style deviations are introduced.

---

## 3. Dependency & Tooling Overview

This context guides the selection and invocation of tools based on project dependencies.

### 3.1. PHP Dependencies (Key Frameworks/Tools)
- **Laravel Framework (v13.x):** Core MVC structure using the Laravel 11+ slim application layout: middleware, routing and exception handling are configured in `bootstrap/app.php`, service providers are registered in `bootstrap/providers.php`, and scheduled tasks live in `routes/console.php`. There are no HTTP/console kernels or `RouteServiceProvider`. Prefer Laravel 13 model/command attributes (`#[Table]`, `#[Fillable]`, `#[Signature]`, etc.) over properties.
- **Filament (v5.x):** Major admin framework. When editing backend forms or tables, prioritize Filament conventions over raw Blade/Vue if within an admin context.
- **Rector (v2.x + driftingly/rector-laravel):** Indicates active project maintenance for PHP/Laravel upgrades; agents should use modern syntax where possible but avoid aggressive refactoring unless explicitly tasked.
- **Database Abstraction:** Eloquent only (Doctrine DBAL was removed; Laravel 11+ no longer needs it).

### 3.2. Frontend Dependencies (Key Libraries)
- **Vite (v8):** Primary asset pipeline tool, with `laravel-vite-plugin` v3 and `@vitejs/plugin-vue`. The project is ESM (`"type": "module"`), so config files must use `import`/`export`, not `require()`. Asset imports must use the Vite helper if running via PHP routes, or standard ES module imports if running client-side.
- **Vue 3 / Inertia.js (v3):** Vue 3 components are the standard. Data flow is primarily via Inertia props. Client (`@inertiajs/vue3`) and server (`inertiajs/inertia-laravel`) must stay on the same major version.
- **Font Awesome (v7 Free):** Icons, imported via `resources/css/app.css`.
- **Chart.js (v4) / vue-chartjs (v5):** Used for visualization; pass `:data` and `:options` to chart components. Note the presence of `chartjs-adapter-moment` for time-series data.

### 3.3. Agent Operation & Verification Loop
1.  **Verification:** Every code change (PHP or JS/Vue) **MUST** be followed by running the appropriate verification commands: PHP unit/feature tests OR frontend build (`npm run build`). Linting (`vendor/bin/pint --test` or equivalent JS lint) is mandatory before any commit action.
2.  **Idempotency:** Commands should be structured to be safe to re-run without causing cumulative damage. Use specific migration flags or re-seeding cautiously.
3.  **Background Processes:** Avoid using `&` in DDEV commands unless absolutely necessary for long-running services (like Vite's dev server, which is often run externally). Sequential execution is the default.
4.  **Debugging Artifacts:** Utilize `dd()` or temporary logging for complex debugging, but these **MUST** be cleaned up before any final verification or commit steps.

---

## 4. General Agent Principles Summary

- **Conciseness:** Minimize conversational text; prioritize tool use and direct answers.
- **Safety:** Never commit configuration files containing environment-specific data (e.g., `.env`, DDEV config sensitive data).
- **Adherence:** Follow the style of the file being edited. When in doubt, search for existing patterns (`grep`/`glob`).
- **Completeness:** Ensure all aspects of the user request are addressed, including implied verification steps like linting.
- **Trust Boundary:** Trust the environment setup provided by DDEV (PHP 8.5, Node 24).
- **Code Exploration:** Use `read` tool to confirm context before *any* edit; do not rely on memory alone for file contents.
- **Final Output:** Always end the execution chain with a verification command (test/build) if code was modified.

===

<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

## Foundational Context

This application is a Laravel application running on PHP 8.5. Always use the APIs that match the installed major version of each package — do not assume a version.

Before relying on a package's API, confirm its installed version:
- PHP packages: run `composer show --direct` to list direct dependencies with versions, or `composer show <vendor/package>` for a single package.
- JS packages: check `package.json` for the installed versions.

## Skills Activation

This project has domain-specific skills available in `**/skills/**`. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If a frontend change doesn't show in the UI or you get a "Unable to locate file in Vite manifest" error, run `npm run build` or ask the user to run `npm run dev`.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

=== boost rules ===

# Laravel Boost

## Tools

- Laravel Boost is an MCP server with tools designed specifically for this application. Prefer Boost tools over manual alternatives like shell commands or file reads.
- Use `database-query` to run read-only queries against the database instead of writing raw SQL in tinker.
- Use `database-schema` to inspect table structure before writing migrations or models.
- Use `get-absolute-url` to resolve the correct scheme, domain, and port for project URLs. Always use this before sharing a URL with the user.
- Use `browser-logs` to read browser logs, errors, and exceptions. Only recent logs are useful, ignore old entries.

## Searching Documentation (IMPORTANT)

- Use `search-docs` before changes that depend on Laravel ecosystem APIs, behavior, configuration, or version-specific syntax. Skip it for copy-only edits and other changes where package documentation is irrelevant. Reuse sufficient results already in context instead of searching again.
- Pass a `packages` array to scope results when you know which packages are relevant.
- Use multiple broad, topic-based queries: `['rate limiting', 'routing rate limiting', 'routing']`. Expect the most relevant results first.
- Do not add package names to queries because package info is already shared. Use `test resource table`, not `filament 4 test resource table`.

### Search Syntax

1. Use words for auto-stemmed AND logic: `rate limit` matches both "rate" AND "limit".
2. Use `"quoted phrases"` for exact position matching: `"infinite scroll"` requires adjacent words in order.
3. Combine words and phrases for mixed queries: `middleware "rate limit"`.
4. Use multiple queries for OR logic: `queries=["authentication", "middleware"]`.

## Project Rules

- This project contains committed, area-grouped rules in `.ai/rules` when that directory exists, including path-scoped framework guidelines under `.ai/rules/boost`. Before you enter plan mode or create/edit any file, you MUST first: open @.ai/rules/index.md (it maps file globs to rule files), read every rule file whose globs cover the path(s) in scope, and run `grep -rin 'keyword' .ai/rules` to catch what a path match alone misses. Do not write code until you have read and are following every matching rule. If `.ai/rules` does not exist, continue without it.

## Artisan

- Run Artisan commands directly via the command line (e.g., `php artisan route:list`). Use `php artisan list` to discover available commands and `php artisan [command] --help` to check parameters.
- Inspect routes with `php artisan route:list`. Filter with: `--method=GET`, `--name=users`, `--path=api`, `--except-vendor`, `--only-vendor`.
- Read configuration values using dot notation: `php artisan config:show app.name`, `php artisan config:show database.default`. Or read config files directly from the `config/` directory.

## Tinker

- Execute PHP in app context for debugging and testing code. Do not create models without user approval, prefer tests with factories instead. Prefer existing Artisan commands over custom tinker code.
- Always use single quotes to prevent shell expansion: `php artisan tinker --execute 'Your::code();'`
  - Double quotes for PHP strings inside: `php artisan tinker --execute 'User::where("active", true)->count();'`

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.
- Use PHP 8 constructor property promotion: `public function __construct(public GitHub $github) { }`. Do not leave empty zero-parameter `__construct()` methods unless the constructor is private.
- Use explicit return type declarations and type hints for all method parameters: `function isAccessible(User $user, ?string $path = null): bool`
- Follow existing application Enum naming conventions.
- Prefer PHPDoc blocks over inline comments. Only add inline comments for exceptionally complex logic.
- Use array shape type definitions in PHPDoc blocks.

=== deployments rules ===

# Deployment

- Laravel can be deployed using [Laravel Cloud](https://cloud.laravel.com/), which is the fastest way to deploy and scale production Laravel applications.

=== tests rules ===

# Test Enforcement

- Add or update tests for behavior and logic changes when a test provides meaningful regression coverage.
- Pure copy, styling, and layout-only changes do not require new or updated tests.
- When test coverage applies, run the affected tests and ensure they pass.
- Test the changed behavior and its important failure modes, but do not add tests beyond them.
- Read the `testing-best-practices` skill before writing tests.

=== inertia-laravel/core rules ===

# Inertia

- Inertia creates fully client-side rendered SPAs without modern SPA complexity, leveraging existing server-side patterns.
- Components live in `resources/inertia/Pages` (unless specified in `vite.config.js`). Use `Inertia::render()` for server-side routing instead of Blade views.
- ALWAYS use `search-docs` tool for version-specific Inertia documentation and updated code examples.
- IMPORTANT: Activate `inertia-vue-development` when working with Inertia Vue client-side patterns.

# Inertia v3

- Use all Inertia features from v1, v2, and v3. Check the documentation before making changes to ensure the correct approach.
- New v3 features: standalone HTTP requests (`useHttp` hook), optimistic updates with automatic rollback, layout props (`useLayoutProps` hook), instant visits, simplified SSR via `@inertiajs/vite` plugin, custom exception handling for error pages.
- Carried over from v2: deferred props, infinite scroll, merging props, polling, prefetching, once props, flash data.
- When using deferred props, add an empty state with a pulsing or animated skeleton.
- Axios has been removed. Use the built-in XHR client with interceptors, or install Axios separately if needed.
- `Inertia::lazy()` / `LazyProp` has been removed. Use `Inertia::optional()` instead.
- Prop types (`Inertia::optional()`, `Inertia::defer()`, `Inertia::merge()`) work inside nested arrays with dot-notation paths.
- SSR works automatically in Vite dev mode with `@inertiajs/vite` - no separate Node.js server needed during development.
- Event renames: `invalid` is now `httpException`, `exception` is now `networkError`.
- `router.cancel()` replaced by `router.cancelAll()`.
- The `future` configuration namespace has been removed - all v2 future options are now always enabled.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using `php artisan list` and check their parameters with `php artisan [command] --help`.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `php artisan make:model --help` to check the available options.

## APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

=== laravel-octane/core rules ===

# Laravel Octane

This application uses Laravel Octane, a long-running PHP server. The application bootstraps once and handles many requests within the same process.

- Never store request-specific state in singletons or static properties, because it can leak across requests.
- Use `config('octane.server')` to detect the active driver (`swoole`, `roadrunner`, or `frankenphp`).
- Prefer scoped bindings (`$this->app->scoped()`) over singletons for per-request services.

When working on Octane-specific features (concurrency, shared tables, memory, driver configuration, testing), invoke `octane-development` for detailed rules.

=== pint/core rules ===

# Laravel Pint Code Formatter

- If you have modified any PHP files, you must run `vendor/bin/pint --dirty --format agent` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test --format agent`, simply run `vendor/bin/pint --format agent` to fix any formatting issues.

=== phpunit/core rules ===

# PHPUnit

- This project uses PHPUnit. Create tests with `php artisan make:test --phpunit {name}`.
- Do not include the test suite directory in `{name}`. Use `SomeFeatureTest`, not `Feature/SomeFeatureTest`.
- Read the `testing-best-practices` skill for guidance on coverage, naming, structure, dependency isolation, and review.

## Running Tests

- Run the narrowest set of tests that covers the change. Pass a file path or `--filter=testName` to `php artisan test --compact`.
- Rerun a test after each change to it.
- Run `vendor/bin/phpunit` to call the test runner directly. It accepts the same file path and `--filter=testName` arguments.

=== inertia-vue/core rules ===

# Inertia + Vue

Vue components must have a single root element.
- IMPORTANT: Activate `inertia-vue-development` when working with Inertia Vue client-side patterns.

</laravel-boost-guidelines>

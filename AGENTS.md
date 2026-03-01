# AGENTS.md - Repository Execution Guidelines

This document outlines the conventions, build commands, and style guides for agents operating within this Laravel/Vue/Vite codebase managed by DDEV. This file is designed to be comprehensive, aiming for approximately 150 lines of instruction for autonomous execution.

---

## 1. Execution Environment & Setup

All development, testing, and linting operations MUST occur inside the DDEV web container (`ddev-web` service) unless the command is explicitly designed to run on the host (e.g., running `ddev` itself, or host-level Node installation if DDEV's Node environment is insufficient/conflicting).

- **Container Service:** `web` (Invoked via `ddev exec -s web ...`).
- **PHP Version:** 8.4 (In container).
- **Node Version:** 16 (In container, managed by DDEV).
- **Document Root:** `public` (Laravel's entry point).
- **Container Code Path:** `/var/www/html` (The standard working directory for DDEV commands).

### Essential Commands (Run via DDEV)

All shell commands requiring the project environment (PHP, Composer, Node) must be prefixed with `ddev exec -s web`.

| Purpose | Command Example (within DDEV) | Single Test/Lint Equivalent | Notes |
| :--- | :--- | :--- | :--- |
| **Run All Tests (PHP)** | `vendor/bin/phpunit` | `vendor/bin/phpunit --filter <FQCN>` | PHPUnit is the standard. FQCN = Fully Qualified Class Name. |
| **Run Feature Tests** | `vendor/bin/phpunit --testdox --group feature` | N/A | Assumes tests are tagged via `@group`. |
| **Run Unit Tests** | `vendor/bin/phpunit --testdox --group unit` | N/A | Consistency in tagging tests is mandatory. |
| **Format Code (PHP)** | `vendor/bin/pint` | `vendor/bin/pint --test` | **MUST** be run before any commit. |
| **Frontend Build** | `npm run build` | N/A | Compiles assets via Vite. |
| **Frontend Watch** | `npm run dev` | N/A | Starts Vite HMR server, typically run outside DDEV if host Node is used. |
| **Dependency Install** | `composer install --no-dev` | `npm ci` | Use `npm ci` for dependency consistency if lockfiles are present. |
| **Database Setup** | `php artisan migrate --seed` | `php artisan migrate:fresh --seed` | Use `--force` only when absolutely certain, never in development scripts. |

---

## 2. Code Style & Conventions

Agents must strictly adhere to styles enforced by existing tools and established Laravel/Vue conventions.

### 2.1. PHP / Backend (Laravel)

1.  **Formatting:** **Laravel Pint** standards are non-negotiable. Use `vendor/bin/pint` to automatically enforce style upon code modification.
2.  **Typing:** All new and substantially modified PHP files **MUST** begin with `declare(strict_types=1);`. Utilize modern PHP 8.4 features (e.g., named arguments, match expressions) idiomatically where appropriate.
3.  **Imports:** Adhere to **PSR-4** structure (`App\\`). Imports must be correctly grouped and ordered: Core, Framework (Laravel/Filament), Library (Spatie, etc.), Project-specific. Sort imports alphabetically within groups.
4.  **Naming:**
    -   Classes, Interfaces, Enums: PascalCase (`UserProfileManager`).
    -   Methods, Properties (public/protected/private): camelCase (`getUserData`).
    -   Constants: UPPER_SNAKE_CASE.
5.  **Error Handling:** Employ standard Laravel exception handling. Wrap external/I/O calls in `try/catch` blocks. Log critical failures using the `Log` facade. Avoid catching bare exceptions; specify exception types (e.g., `QueryException`, `FileNotFoundException`).
6.  **Directory Placement:** New logic must reside in idiomatic locations: Controllers in `app/Http/Controllers`, Services in `app/Services`, Models in `app/Models`, Livewire components in `app/Http/Livewire`. Always check existing files in the target directory for existing patterns.

### 2.2. JavaScript / Vue / Frontend

1.  **Tooling:** **Vite** is the mandated build tool. Ensure `package.json` scripts are used for all compilation (`npm run build`).
2.  **Framework:** Vue 3 **Composition API** (`<script setup>`) is the current standard. Older Options API components should only be modified minimally to maintain consistency. Never mix API styles within a single component file.
3.  **State Management:** Inertia.js passes server-side data via props. Avoid complex, separate global state solutions unless absolutely necessary and explicitly justified by performance or complexity metrics.
4.  **HTTP Client:** **Axios** is the preferred library for all data fetching operations. All requests should be configured to work with the server's base URL automatically.
5.  **Styling:** The **Bulma** framework is present. New UI work must match existing Bulma class usage or adhere to standard CSS organization within `.vue` files. Avoid inline styles unless dynamic calculations are required.

### 2.3. Configuration & Agent Directives

1.  **DDEV Context:** All environment-specific ports (Vite on 5173) are managed via `.ddev/config.yaml`. Agents must not attempt to run services bound to arbitrary ports without verifying routing/exposure in this file. If a port conflict arises, an agent should suggest modifying `.ddev/config.yaml` via explicit user instruction.
2.  **Agent Rules:** **NO** specific agent configuration files (like `.cursorrules`) were detected. Agents must rely solely on the established codebase patterns and the rules in this document.
3.  **File Creation:** When scaffolding new features, adhere strictly to Laravel/Vue structure. Place frontend assets in `resources/js/Assets`, reusable components in `resources/js/Components`, and main pages/views in `resources/js/Pages`.
4.  **Documentation:** When adding new features, maintain clear documentation inline, particularly for complex business logic. This `AGENTS.md` must be updated if new standard commands or style deviations are introduced.

---

## 3. Dependency & Tooling Overview

This context guides the selection and invocation of tools based on project dependencies.

### 3.1. PHP Dependencies (Key Frameworks/Tools)
- **Laravel Framework (v10.x):** Core MVC structure. Be aware of service container behavior.
- **Filament (v3.x):** Major admin framework. When editing backend forms or tables, prioritize Filament conventions over raw Blade/Vue if within an admin context.
- **Rector:** Indicates active project maintenance for PHP upgrades; agents should use modern syntax where possible but avoid aggressive refactoring unless explicitly tasked.
- **Database Abstraction:** Doctrine DBAL is present alongside Eloquent. Always prefer Eloquent for standard operations.

### 3.2. Frontend Dependencies (Key Libraries)
- **Vite:** Primary asset pipeline tool. Asset imports must use the Vite helper if running via PHP routes, or standard ES module imports if running client-side.
- **Vue 3 / Inertia.js:** Vue 3 components are the standard. Data flow is primarily via Inertia props.
- **Bulma:** Primary CSS utility framework. Consult existing component styles for correct class application.
- **Chart.js:** Used for visualization. Note the presence of `chartjs-adapter-moment` for time-series data.

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
- **Trust Boundary:** Trust the environment setup provided by DDEV (PHP 8.4, Node 16).
- **Code Exploration:** Use `read` tool to confirm context before *any* edit; do not rely on memory alone for file contents.
- **Final Output:** Always end the execution chain with a verification command (test/build) if code was modified.

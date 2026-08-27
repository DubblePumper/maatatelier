# Laravel Repository Instructions

Last reviewed: 2026-07-28

These instructions apply to the entire repository unless a more specific nested `AGENTS.md` overrides them.

## 1. Core principles

Build production-ready software that is:

- accessible and semantic by default;
- secure and privacy-conscious by default;
- reliable, testable, observable, and maintainable;
- performant in real-world conditions;
- simple enough for the next developer to understand;
- compatible with keyboard users, screen readers, zoom, reduced motion, slower devices, and unreliable connections.

Prefer clear, proven Laravel and web-platform conventions over clever abstractions or fashionable complexity. Every dependency, abstraction, background job, SPA feature, animation, and cache layer must have a concrete benefit.

## 2. Sources of truth and version policy

Before changing code, inspect at minimum:

- this file and any nested `AGENTS.md` files;
- `composer.json` and `composer.lock`;
- `package.json` and the active JavaScript lockfile;
- `README.md`, relevant architecture notes, CI workflows, and existing tests;
- nearby code to learn the repository's established patterns.

Treat lockfiles and installed package versions as the source of truth. Never guess APIs from memory when version-specific documentation is available.

For existing projects:

- preserve the current supported stack unless an upgrade is explicitly requested;
- do not modify lockfiles unless dependencies intentionally change;
- do not introduce a second package manager, formatter, linter, test framework, or competing architectural style;
- use project-defined Composer and package scripts before inventing commands.

For new projects:

- use the latest stable Laravel 13.x release with the constraint `^13.0`;
- use the latest stable PHP 8.5 patch; do not use preview, RC, nightly, or unreleased runtimes;
- use Composer 2 and a committed lockfile;
- use Tailwind CSS 4 when Tailwind is appropriate;
- use Vite and the latest stable compatible first-party Laravel Vite integration;
- select one JavaScript package manager and commit its lockfile;
- prefer npm unless the project or user explicitly chooses pnpm or Yarn.

Laravel 13 supports PHP 8.3 through 8.5, but new projects in this repository target PHP 8.5. Do not claim compatibility with a PHP version that CI does not test.

Use Laravel Boost as a development dependency when AI-assisted development is used. Search its version-aware documentation before implementing unfamiliar Laravel or ecosystem behavior. Keep generated Boost resources current with `php artisan boost:update` after relevant dependency updates.

## 3. Agent workflow

For every non-trivial task:

1. Understand the requested outcome and inspect the relevant code paths.
2. Identify security, authorization, data-migration, accessibility, and backward-compatibility risks.
3. Make the smallest coherent change that fully solves the task.
4. Preserve unrelated user changes and avoid broad cleanup unless requested.
5. Add or update tests that would fail before the fix and pass afterward.
6. Run focused checks first, then the relevant broader checks.
7. Review the final diff for accidental changes, debug code, generated artifacts, secrets, and inconsistent formatting.
8. Report what changed, which checks ran, and any unresolved risk or unverified assumption.

Never hide failures behind silent fallbacks. Do not fabricate command output, test results, package APIs, files, routes, migrations, or configuration. If a required check cannot run, state the exact reason.

Do not run destructive commands, reset databases, delete user data, force-push, rewrite Git history, commit, or push unless explicitly requested or required by higher-priority execution instructions.

## 4. Project structure and architecture

- Keep controllers focused on HTTP concerns: accept the request, authorize, delegate, and return a response.
- Use Form Request classes for non-trivial validation and request authorization.
- Use Policies and Gates for authorization. Enforce authorization server-side for every protected action and object.
- Put meaningful business operations in clearly named Actions, domain services, or model methods when that improves cohesion. Do not create a service layer for trivial CRUD.
- Prefer Eloquent and the Query Builder. Do not introduce repositories merely to wrap Eloquent.
- Use route model binding where it improves clarity, including scoped bindings for nested resources.
- Keep domain boundaries understandable; avoid deep folder trees and one-class-per-line abstractions without value.
- Prefer composition over inheritance.
- Use events and listeners only when loose coupling is genuinely useful.
- Use queues only for work that is slow, retryable, scheduled, or safely asynchronous.
- Do not perform database queries or business logic in Blade templates.
- Do not edit `vendor/`, `node_modules/`, generated assets, or framework files.
- Update `README.md` only when setup, commands, configuration, architecture, deployment, or user-visible behavior changes.

## 5. PHP and Laravel coding standards

- Follow the repository's existing style and Laravel conventions.
- Format PHP with Laravel Pint.
- Use Laravel Pint as the formatting authority and align custom rules with PER Coding Style 3.0 / PSR-12-compatible conventions.
- Add scalar, parameter, property, and return types where they improve correctness and match project conventions.
- Prefer precise types, value objects, enums, and readonly state where they reduce invalid states; do not over-engineer simple data.
- Avoid `mixed`, global helpers, and magic behavior when a clearer type or explicit dependency is practical.
- Use constructor injection where it improves testability and clarity; Laravel facades are acceptable when they are idiomatic and easy to fake.
- Use named arguments cautiously when calling Laravel framework methods because parameter names are not covered by Laravel's backward-compatibility promise.
- Use Carbon and Laravel date helpers consistently. Store timestamps in UTC and localize only at display or integration boundaries.
- Represent money with integer minor units or a suitable decimal/value object, never binary floating point.
- Use `config()` outside configuration files. Call `env()` only inside files under `config/`.
- Use mass assignment deliberately. Never pass untrusted request payloads directly to `create()`, `update()`, or `fill()` without an explicit validated shape.
- Prefer explicit API Resources or JSON:API Resources over returning models directly.
- Use first-party Laravel 13 features when they simplify the implementation, but do not mix attributes and conventional configuration inconsistently. Follow the repository's dominant style.

## 6. Frontend architecture

Choose the least complex frontend that satisfies the product need:

- Blade for server-rendered pages and content-first websites;
- Blade plus small Alpine.js enhancements for local interaction;
- Livewire 4 with Flux UI for reactive interfaces best kept in PHP;
- Inertia 3 with TypeScript and React 19, Vue 3, or Svelte 5 for genuinely application-like interfaces.

Do not convert a server-rendered application into an SPA without a clear product and maintenance benefit. Public and SEO-relevant pages must contain meaningful server-rendered HTML.

When TypeScript exists:

- keep strict type checking enabled;
- avoid `any` unless documented and narrowly contained;
- share generated route or API types where the stack supports it;
- preserve Wayfinder-generated route usage in Laravel starter-kit projects;
- use the repository's existing linter and formatter rather than adding a competing tool.

## 7. CSS, Tailwind, design system, and responsive behavior

- Use Tailwind CSS 4 according to its CSS-first configuration model when Tailwind is installed.
- Do not add Sass, Less, or Stylus to a Tailwind 4 project.
- Centralize colors, spacing, typography, radii, shadows, breakpoints, motion, and surface styles as design tokens.
- Avoid repeated arbitrary values and inconsistent one-off component variants.
- Build mobile-first and test small phones, tablets, laptops, wide screens, 200% zoom, long content, empty states, and validation errors.
- Avoid horizontal page scrolling unless the content inherently requires it.
- Reserve dimensions for images and media to prevent layout shift.
- Prefer SVG for logos and icons, responsive images with `srcset`/`sizes`, and modern formats such as AVIF or WebP where supported by the media pipeline.
- Provide complete favicon, app-icon, manifest, theme-color, and social-preview metadata for production public sites when relevant.
- Never ship framework placeholders, broken images, fake content, or default branding.

Tailwind 4 targets modern browsers. If the product requires browsers older than Tailwind's supported baseline, document that requirement and choose a compatible approach instead of relying on broken fallbacks.

## 8. Accessibility: WCAG 2.2 AA

Target WCAG 2.2 Level AA for every complete user flow, not merely isolated components.

- Use native HTML semantics before ARIA.
- Use real links for navigation and real buttons for actions.
- Provide one clear page-level heading and a logical heading hierarchy.
- Provide skip links and correct landmarks where useful.
- Every form control needs a persistent accessible label; placeholders are not labels.
- Connect descriptions and validation errors programmatically.
- Preserve safe user input after validation failures.
- Use correct `type`, `autocomplete`, `inputmode`, `name`, and error semantics.
- Make all functions usable with keyboard only and without timing-sensitive pointer gestures.
- Ensure focus is visible, logical, and never obscured by sticky UI.
- Restore focus after dialogs and manage focus only when necessary.
- Provide alternatives to dragging interactions.
- Avoid asking users to re-enter information already supplied in the same process unless required for security.
- Do not rely on color, icons, position, hover, sound, or motion alone to communicate meaning.
- Meet contrast requirements for text, controls, icons, and focus indicators.
- Make pointer targets at least WCAG's minimum size and preferably larger for touch interfaces.
- Respect `prefers-reduced-motion`, `forced-colors`, zoom, text resizing, and high-contrast usage.
- Authentication must work with password managers, paste, autofill, and assistive technology. Do not introduce cognitive-function tests without an accessible alternative.
- Provide captions, transcripts, and audio descriptions when required by the media and context.

Automated accessibility tools are required where practical, but they do not replace manual keyboard, screen-reader, zoom, and visual checks.

## 9. Motion and modern browser features

Motion is optional progressive enhancement, not a default requirement.

- Prefer `transform` and `opacity`; avoid expensive layout animation.
- Never apply global `transition: all`.
- Keep transitions short, purposeful, interruptible, and disabled or simplified for reduced-motion users.
- Prefer native `<details>`, `<dialog>`, and the Popover API when their semantics match the interaction and testing confirms accessibility.
- Use View Transitions, Speculation Rules, CSS Anchor Positioning, container queries, scroll-driven animations, `content-visibility`, and discrete transitions only when they improve the experience and have safe feature-detected fallbacks.
- Never prerender authentication, admin, checkout, logout, mutation, permission-request, or user-sensitive pages.
- Never perform state changes on `GET` requests.
- Avoid scroll hijacking, custom inertia, unnecessary autoplay, and essential information hidden only in tooltips.

## 10. Security baseline

Use OWASP ASVS 5.0 Level 2 as the default verification baseline for normal production applications and the OWASP Top 10:2025 as an awareness checklist. Apply stronger controls for health, financial, administrative, identity, or other high-risk data.

### Configuration and transport

- Terminate HTTPS correctly at the web server, proxy, load balancer, or platform edge.
- Configure trusted proxies and forwarded headers correctly. Do not blindly force HTTPS in application code to compensate for incorrect proxy configuration.
- Set `APP_ENV=production` and `APP_DEBUG=false` in production.
- Keep secrets out of source control, logs, exception pages, client bundles, screenshots, fixtures, and prompts.
- Prefer a managed secret store or deployment-platform secrets over long-lived plaintext `.env` files in production.
- Rotate compromised secrets and invalidate dependent sessions or tokens.
- Configure secure session cookies: `Secure`, `HttpOnly`, an appropriate `SameSite` policy, scoped domain/path, and suitable expiry.
- Apply HSTS only after HTTPS is correct for all intended subdomains.

### Authentication and sessions

- Prefer passkeys or another phishing-resistant option for privileged and high-risk accounts where feasible.
- Require multi-factor authentication according to risk; do not force a specific package or method without product justification.
- Password policy must favor length and breached-password blocking over composition rules. Allow password managers, paste, Unicode, and long passphrases. Do not require periodic changes without evidence of compromise.
- Hash passwords using Laravel's supported password hashing configuration with an appropriate work factor.
- Rate-limit login, password-reset, verification, OTP, recovery, and other abuse-prone operations using keys that account for both account and network context.
- Regenerate session identifiers after authentication and privilege changes.
- Revoke sessions and tokens after password reset, account compromise, or relevant security changes.
- Protect account recovery at least as strongly as login and avoid knowledge-based security questions.

### Authorization and data access

- Deny by default.
- Authorize every action and every object, including nested resources, downloads, exports, background jobs, broadcasts, and API endpoints.
- Never rely on hidden UI, route names, client-side checks, IDs, UUIDs, or tenant filters alone as authorization.
- Scope tenant-owned queries explicitly and test cross-tenant access.
- Prevent over-posting and mass-assignment vulnerabilities with validated DTO-like input shapes.
- Return only necessary fields through Resources; hide secrets and internal metadata.

### Input, output, requests, and integrations

- Validate input for type, format, range, length, allowed values, and business invariants.
- Encode output for its destination context. Blade escaping is the default; raw HTML requires an explicit trusted and sanitized source.
- Do not apply vague blanket "sanitization" that corrupts valid data. Sanitize only when a defined content format requires it.
- Use parameterized database APIs; never concatenate untrusted SQL fragments.
- Protect state-changing browser requests against request forgery using Laravel 13's framework protections.
- Use strict allowlists for CORS, redirects, outbound URLs, callback URLs, and file paths.
- Prevent SSRF by validating scheme, host, resolved addresses, redirects, ports, and network destinations for server-side fetches.
- Verify webhook signatures before parsing trusted fields; enforce timestamp/replay windows and idempotency.
- Use idempotency keys or equivalent safeguards for payments and retryable mutations.
- Set timeouts, size limits, retry limits, and circuit-breaking behavior for external services.
- Do not log tokens, passwords, authorization headers, session IDs, health data, payment data, or unnecessary personal information.

### Files and media

- Validate size, extension, MIME type, and actual file content.
- Generate server-side filenames and store private uploads outside the public web root.
- Serve private files through authorized controllers or expiring signed URLs.
- Re-encode untrusted images when appropriate and remove unnecessary metadata.
- Scan high-risk uploads for malware when the threat model requires it.
- Prevent path traversal, archive bombs, decompression bombs, executable uploads, and unsafe SVG/HTML rendering.

### Browser security headers

Use headers appropriate to the application, normally including:

- a tested Content Security Policy, introduced in report-only mode before enforcement when practical;
- `X-Content-Type-Options: nosniff`;
- `Referrer-Policy`;
- `Permissions-Policy`;
- clickjacking protection through CSP `frame-ancestors`;
- secure caching rules for sensitive responses.

Do not copy a generic CSP that breaks the app or permits broad unsafe sources without review.

## 11. Privacy and data lifecycle

- Collect only data needed for a defined purpose.
- Document sensitive fields, retention periods, deletion behavior, exports, subprocessors, and access controls.
- Prefer pseudonymous identifiers in logs and analytics.
- Do not expose personal data in URLs, filenames, client-side storage, analytics events, or error messages.
- Implement deletion, correction, export, consent, and retention behavior when the product or applicable requirements need it.
- Use cookies and tracking only with a documented purpose and appropriate consent behavior.
- Treat production data as unavailable for local development unless an approved anonymized process exists.

## 12. Database and data integrity

- Use migrations for every schema change.
- Use foreign keys, unique constraints, check constraints where supported, indexes, and appropriate nullability to enforce invariants at the database layer.
- Index based on real query patterns; verify with query plans for important queries.
- Avoid N+1 queries through eager loading and tests or development safeguards.
- Paginate or stream large result sets; do not load unbounded collections into memory.
- Use transactions for multi-step integrity boundaries.
- Protect concurrency with unique constraints, atomic operations, row locks, optimistic locking, or idempotency as appropriate.
- Keep migrations deploy-safe. For zero/minimal-downtime deployments, use expand-and-contract changes, backward-compatible releases, and separate backfills.
- Never perform large data rewrites in a blocking schema migration without an explicit deployment plan.
- Test against the production database engine when engine-specific behavior matters. SQLite is acceptable only when its differences cannot invalidate the test.
- Backups are incomplete until restore procedures are documented and tested.

## 13. Queues, events, cache, and scheduling

- Queued jobs must be idempotent where retries can occur.
- Define realistic timeout, retry, backoff, and failure behavior.
- Avoid serializing large or sensitive payloads into jobs.
- Dispatch after database commit when a job depends on committed data.
- Use unique-job or overlap-prevention controls when duplicate execution is unsafe.
- Route jobs to explicit queues when priority or resource isolation matters.
- Scheduled tasks must use overlap and single-server controls where required.
- Cache only when correctness, invalidation, ownership, TTL, and stampede behavior are understood.
- Never cache authorization decisions or user-sensitive responses under ambiguous keys.
- Use Redis/Horizon only when their operational value justifies them.

## 14. APIs

- Use conventional HTTP methods and status codes.
- Keep error responses consistent and machine-readable; use Problem Details semantics where appropriate.
- Use Laravel API Resources by default. Use Laravel 13 JSON:API Resources only when clients require JSON:API behavior.
- Version externally consumed APIs when compatibility cannot otherwise be maintained.
- Authenticate first-party SPAs with Sanctum unless another approach is clearly required.
- Use short-lived, scoped tokens and least privilege for machine access.
- Validate content type, request size, pagination limits, filters, sorts, sparse fields, and includes.
- Prevent BOLA/IDOR through object-level authorization on every endpoint.
- Document public or partner APIs with OpenAPI 3.2 when supported by the project tooling; otherwise use the newest compatible 3.x revision.
- Add contract tests for important integrations.

## 15. AI features, semantic search, and MCP

Laravel 13 includes first-party AI and semantic/vector-search capabilities. Use them only when the product requires them.

- Keep providers replaceable when practical, but do not build abstractions before they are needed.
- Treat prompts, retrieved documents, tool results, uploaded files, and model output as untrusted data.
- Protect against prompt injection, excessive agency, data exfiltration, unsafe tool calls, and cross-tenant retrieval.
- Restrict tools by allowlist, least privilege, tenant, and explicit authorization.
- Validate model output before using it in queries, commands, HTML, emails, payments, or state changes.
- Require human confirmation for irreversible or high-impact actions.
- Apply token, latency, concurrency, and monetary budgets.
- Do not send secrets or unnecessary personal data to model providers.
- Log model/provider/version and safety-relevant metadata without storing sensitive prompt content unnecessarily.
- Evaluate quality and safety with representative test cases before release.
- Use vector search only with explicit tenancy filters, authorization, appropriate indexes, and measurable relevance tests.

## 16. Testing strategy

Laravel supports both Pest and PHPUnit. Use the framework already installed. For new projects, prefer Pest 4 unless the team chooses PHPUnit.

- Write feature tests for important HTTP, authentication, authorization, validation, database, and integration flows.
- Write unit tests for isolated domain logic with meaningful branching.
- Test the failure path, unauthorized path, boundary values, race-sensitive behavior, retries, and idempotency.
- Use factories, fakes, frozen time, and deterministic fixtures.
- Do not call real payment, email, SMS, AI, storage, or other external services in the normal test suite.
- Add browser tests for critical JavaScript, Livewire, or Inertia flows using the project's chosen browser runner.
- Test responsive layouts and at least the critical keyboard journey.
- Add automated accessibility checks, then manually verify semantics, focus, keyboard, zoom, and screen-reader announcements.
- Run tests in parallel when safe.
- Coverage is a diagnostic, not the goal; prioritize risk and behavior over a percentage target.
- Never delete or weaken a valid test merely to make CI pass.

## 17. Quality gates and commands

Use repository scripts when available. Typical checks are:

```bash
composer validate --strict
composer audit
vendor/bin/pint --test
vendor/bin/phpstan analyse
php artisan test
npm run lint
npm run typecheck
npm run build
```

Do not run nonexistent commands blindly. If a project lacks quality scripts, add clear `composer` or package scripts when the task justifies it.

CI should normally verify:

- clean dependency installation from lockfiles;
- PHP formatting;
- static analysis at the highest practical stable level;
- backend tests;
- frontend linting and type checking;
- production frontend build;
- dependency/security audit;
- accessibility or browser checks for critical flows where practical.

Do not ignore warnings globally without a documented, narrow reason. Baselines must not silently absorb new violations.

## 18. Performance

Measure before optimizing and preserve correctness.

- Target good Core Web Vitals at the 75th percentile: LCP at or below 2.5 seconds, INP at or below 200 milliseconds, and CLS at or below 0.1.
- Keep server-rendered above-the-fold content meaningful.
- Prevent N+1 queries, missing indexes, unbounded responses, repeated external calls, and unnecessary serialization.
- Cache configuration, events, routes, and views in production through `php artisan optimize`.
- Keep JavaScript bundles small and split only when it improves real loading behavior.
- Avoid unnecessary third-party scripts and fonts.
- Preload only proven critical resources. Do not preload or preconnect broadly.
- Lazy-load below-the-fold media and supply intrinsic dimensions.
- Use appropriate HTTP caching, ETags/revalidation, immutable caching for fingerprinted assets, and private/no-store caching for sensitive responses.
- Keep pages eligible for the back/forward cache; avoid `unload` handlers.
- Use Octane, Reverb, heavy client frameworks, service workers, or complex caching only after profiling and with an operational plan.

## 19. SEO, discoverability, and content

For public pages:

- provide unique titles, useful meta descriptions, canonical URLs, clean crawlable links, and correct language metadata;
- generate accurate `robots.txt` and XML sitemaps; do not advertise private or non-canonical URLs;
- use `hreflang` only when real localized alternatives exist;
- use visible, truthful JSON-LD that matches the page and validate it;
- use structured data such as Organization, LocalBusiness, BreadcrumbList, Article, Product, or FAQPage only when the content and page type genuinely qualify;
- do not expect structured data to guarantee a rich result;
- provide Open Graph and `twitter:*` card metadata for shareable pages;
- use descriptive link text and meaningful image alternatives;
- preserve redirects and metadata during URL changes;
- avoid doorway pages, keyword stuffing, fabricated reviews, fake expertise, thin AI-generated content, and mass-produced location pages without unique value.

"GEO" or "AI search optimization" is not a substitute for technical SEO and trustworthy content. Make answers clear, factual, attributable, crawlable, and maintained. Do not add non-standard files such as `llms.txt` unless explicitly requested and understood as experimental.

## 20. Deployment and operations

- Keep deployments reproducible from committed lockfiles.
- Build production assets in CI or a controlled build stage.
- Install production PHP dependencies without development packages and with an optimized autoloader.
- Run `php artisan optimize` during deployment.
- Run migrations with a rollback and compatibility plan.
- Reload long-running Laravel services and queue workers after deployment using the platform's supported mechanism.
- Configure the scheduler exactly once and use queue supervision.
- Keep `storage` and `bootstrap/cache` writable only as required.
- Run containers as a non-root user where practical, use multi-stage builds, pin base-image versions or digests according to the deployment policy, and keep images minimal.
- Expose Laravel's health route and add dependency readiness checks only when they are safe and operationally useful.
- Use rolling, blue/green, or another minimal-downtime strategy when availability requires it.
- Never expose Telescope, debug bars, profilers, queue dashboards, or internal health details publicly.

## 21. Observability and incident readiness

- Use structured logs with timestamps, environment, severity, request/correlation ID, and safe operational context.
- Keep logs free of secrets and unnecessary personal data.
- Monitor application errors, failed jobs, queue latency, slow queries, cache failures, dependency failures, authorization anomalies, and performance regressions.
- Use Telescope only in development or tightly controlled environments.
- Use Pulse, Nightwatch, OpenTelemetry-compatible tooling, or another approved platform when it provides actionable production visibility.
- Define alert ownership and actionable thresholds; avoid noisy alerts without a response path.
- Preserve enough audit history for sensitive administrative and security events.
- Document recovery steps for failed deployments, compromised credentials, data restoration, and external-service outages.

## 22. Output and change discipline

- Prefer a small coherent diff over rewriting entire files.
- Show or describe exact file paths and placement for proposed code.
- When changing a function or component, provide enough surrounding context to apply and review it safely; do not duplicate unchanged large files without need.
- Remove temporary files, debug statements, commented-out experiments, and obsolete code introduced by the task.
- Do not add placeholder abstractions, speculative features, or dependencies "for later".
- Do not suppress real errors with broad catches, empty handlers, fake success responses, or insecure defaults.
- Explain significant tradeoffs briefly.
- Finish with the repository in a reviewable state and list the validation performed.

===

<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to ensure the best experience when building Laravel applications.

## Foundational Context

This application targets PHP 8.3 and Laravel 12. You are an expert with the Laravel ecosystem. Always use the APIs that match the installed major version of each package — do not assume a version.

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

- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

## Replies

- Be concise in your explanations - focus on what's important rather than explaining obvious details.

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

- This project contains committed, area-grouped rules in `.ai/rules` when that directory exists (settled decisions, non-obvious traps, standing constraints). Framework and package guidelines that only apply to specific paths (testing, frontend, components) also live there, under `.ai/rules/boost` — this is not just recorded decisions, it is load-bearing guidance you have not seen inline. Before you enter plan mode or create/edit any file, you MUST first: open @.ai/rules/index.md (it maps file globs to rule files), read every rule file whose globs cover the path(s) in scope, and run `grep -rin 'keyword' .ai/rules` to catch what a path match alone misses. Do not write code until you have read and are following every matching rule. If `.ai/rules` does not exist, continue without it.
- Record durable rules with `record-rule` so the next agent or teammate inherits them instead of working them out again. Pass a `glob` (e.g. `app/Http/Controllers/**`), a short `title`, and a few-line `note`. Always use `record-rule`, never your native memory or notes tool — native memory is personal and session-scoped; only `.ai/rules` is shared with the team and persists in the repo.

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
- Use TitleCase for Enum keys: `FavoritePerson`, `BestLake`, `Monthly`.
- Prefer PHPDoc blocks over inline comments. Only add inline comments for exceptionally complex logic.
- Use array shape type definitions in PHPDoc blocks.

=== deployments rules ===

# Deployment

- Laravel can be deployed using [Laravel Cloud](https://cloud.laravel.com/), which is the fastest way to deploy and scale production Laravel applications.

=== tests rules ===

# Test Enforcement

- Test every code change by adding or updating a test.
- Run the affected tests and ensure they pass.
- Test the changed behavior and its important failure modes, but do not add tests beyond them.
- Read the `testing-best-practices` skill before writing tests.

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

## Vite Error

- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.

=== laravel/v12 rules ===

# Laravel 12

- CRITICAL: ALWAYS use `search-docs` tool for version-specific Laravel documentation and updated code examples.
- Since Laravel 11, Laravel has a new streamlined file structure which this project uses.

## Laravel 12 Structure

- In Laravel 12, middleware are no longer registered in `app/Http/Kernel.php`.
- Middleware are configured declaratively in `bootstrap/app.php` using `Application::configure()->withMiddleware()`.
- `bootstrap/app.php` is the file to register middleware, exceptions, and routing files.
- `bootstrap/providers.php` contains application specific service providers.
- The `app/Console/Kernel.php` file no longer exists; use `bootstrap/app.php` or `routes/console.php` for console configuration.
- Console commands in `app/Console/Commands/` are automatically available and do not require manual registration.

## Database

- When modifying a column, the migration must include all of the attributes that were previously defined on the column. Otherwise, they will be dropped and lost.

- Laravel 12 allows limiting eagerly loaded records natively, without external packages: `$query->latest()->limit(10);`.

### Models

- Casts can and likely should be set in a `casts()` method on a model rather than the `$casts` property. Follow existing conventions from other models.

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

</laravel-boost-guidelines>

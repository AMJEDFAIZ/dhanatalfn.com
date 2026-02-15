## Scope & Assumptions
- Target: live, content-based Laravel site (no API, no advanced roles), Tailwind for frontend, Bootstrap for admin.
- Output: multi-section report exactly in your requested format (1–10) with scores, issue tables, exploit notes, and a final master action plan.
- Assumptions until evidence is provided: standard Laravel auth, typical Nginx/Apache deployment, MySQL/MariaDB unless stated otherwise.

## Evidence Collection (What I Need From You)
- Full folder tree (top-level + app/, routes/, resources/, config/, database/, public/, bootstrap/).
- Key files (paste contents or upload):
  - composer.json, package.json, vite.config.js (or mix config), tailwind.config.js, postcss.config.js
  - .env.example (NOT .env), config/app.php, config/cache.php, config/session.php, config/logging.php, config/filesystems.php, config/database.php
  - routes/web.php (+ any route files included)
  - app/Http/Kernel.php, app/Http/Middleware/*, app/Providers/*
  - Auth-related: controllers, guards/providers config, any custom middleware/policies/gates
  - Core domain controllers/models (top 10 most important)
  - resources/views layouts + key pages + admin dashboard templates
  - database/migrations/* (all), plus a schema dump or DB diagram (tables, columns, indexes, FKs)
  - public/robots.txt, public/sitemap.xml (or routes that generate them)
  - Any upload handling code (controllers/services), storage config, symlink status (storage:link)
  - Deployment artifacts: Dockerfile/docker-compose (if any), Nginx/Apache vhost, CI/CD pipeline YAML, queue/scheduler setup
- SEO signals (if available): example URLs for 5–10 top pages, current meta output, canonical behavior, sitemap coverage, GSC/GA insights (optional).

## Audit Methodology (How I Will Analyze)
- Architecture & structure: map modules, responsibilities, service container usage, providers, middleware flow, and coupling points.
- Code quality: detect fat controllers, duplicated logic, view-layer violations, inconsistent naming, missing validation boundaries.
- Security (OWASP): threat-model entry points (forms, uploads, admin), then verify input validation, output escaping, authZ boundaries, CSRF, rate limiting, session/cookie security, error leakage, storage exposure.
- Performance: locate N+1 patterns, missing eager loading, query hotspots, caching layers, config/route/view cache suitability, asset pipeline, image strategy.
- Database: validate migrations, constraints, indexes, nullable discipline, cascade rules, soft deletes, and query patterns vs schema.
- Frontend/UI/UX: Tailwind usage quality, component consistency, responsiveness, accessibility basics, form UX and error states.
- SEO: titles/meta, heading structure, canonicals, sitemap/robots, schema/OG, internal linking, URL/slugs, image alts, CWV impact.
- Production readiness: debug/logging, headers, HTTPS, backups, queues/scheduler, env separation, monitoring.

## Deliverables (What You’ll Receive)
- A brutally honest enterprise audit report with:
  - Numeric scores (overall, security, performance, SEO, code quality, architecture)
  - Top 10 critical issues
  - Per-section issue tables with severity, technical impact, and exact fix strategy
  - Code-level refactor examples tailored to your codebase patterns
  - Final prioritized master table sorted by your priority rules

## Execution Steps After You Provide Evidence
1. Build a complete inventory of routes, controllers, models, views, middleware, providers, migrations, configs.
2. Trace request lifecycles for public pages and admin pages; identify trust boundaries.
3. Run targeted static review patterns (validation, escaping, uploads, authZ, query patterns, caching).
4. Synthesize findings into the exact report structure you specified.

## Data Handling Rules
- I will not request or store secrets; only .env.example is needed.
- Any sensitive tokens/keys should be redacted before sharing.

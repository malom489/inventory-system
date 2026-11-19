# ADR-001: Multi-tenancy strategy

**Status:** Accepted
**Date:** 2026-11-18

## Context
The system holds several businesses' stock, money, and customer data in one
platform. Two failures are unacceptable (§1 of the engineering principles):
one tenant ever seeing another tenant's data, and stock or financial figures
that cannot be reconciled.

## Decision
- **Shared database, shared schema** with mandatory `tenant_id` on every
  tenant-owned table (§2.1).
- Isolation enforced at three layers:
  1. **Code** — `TenantScopedRepository` throws `LogicException` on any SQL
     lacking the `{TENANT}` marker.
  2. **Database** — composite unique keys and (Phase 2+) composite foreign
     keys make cross-tenant references physically impossible.
  3. **Tests** — `tests/Isolation/TenantIsolationTest` runs in CI on every
     push and asserts foreign-tenant data is invisible.
- Public identifiers use ULIDs (`public_id CHAR(26)`), never sequential integer
  IDs in URLs or API responses (§2.3).
- `TenantContext` is immutable per request; constructed only by the future
  `TenantResolutionMiddleware` (Phase 1 Day 2).

## Deferred to later phases (with reasons)
- `tenant_users.user_id` FK to `users` — `users` table created in Phase 2.
- `tenant_users.(tenant_id, role_id)` composite FK to `roles` — `roles` table
  created in Phase 2.
- `TenantResolutionMiddleware` (subdomain → `TenantContext`) — Phase 1 Day 2.
- Per-tenant rate limiting and quota enforcement (§2.4) — Milestone 9.

## Alternatives considered
- **Database per tenant** (MySQL schema per tenant): stronger isolation, but
  migrations run N times, many connections, cross-tenant reporting harder.
  Kept open as a hybrid escape hatch: `TenantContext` resolves the connection,
  so a large or regulated tenant can later be moved without rewriting
  business code (§2.1).
- **Table-prefix per tenant**: rejected. Schema changes require touching every
  tenant's prefix; naming discipline fails at scale.

## Consequences
- Every new tenant-owned table MUST have `tenant_id BIGINT UNSIGNED NOT NULL`
  as the first column of every composite index and unique key (§2.3).
- Cross-tenant operations (platform admin, billing) live in a separate
  `PlatformRepository` (§6) with explicit permission checks and full audit
  logging.
- The isolation test is a permanent CI gate — if it fails, nothing merges.
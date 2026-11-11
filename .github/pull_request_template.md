## What & why
<!-- One paragraph. Link milestone. -->

## Definition of Done (§17)
- [ ] tenant_id on every new table; composite uniques + FKs
- [ ] Queries only via tenant-scoped repositories
- [ ] Isolation test added (two tenants, foreign ID → 404)
- [ ] Reversible migration; indexes lead with tenant_id; EXPLAIN reviewed
- [ ] DECIMAL for money/qty; UTC timestamps; no hard deletes of posted data
- [ ] Every stock change writes immutable movement in same transaction
- [ ] Negative-stock policy enforced atomically; doc numbers transactional
- [ ] Optimistic locking (version); idempotency keys on creating POSTs
- [ ] Input allow-listed; PDO prepared statements; business logic in services
- [ ] Structured logs with tenant + request ID; audit entries written
- [ ] AuthN, tenant membership, permissions; CSRF; output escaped; rate limits
- [ ] API versioned, consistent errors, pagination caps, 404 for foreign IDs
- [ ] Frontend semantic/accessible; no innerHTML with user data; decimals as strings
- [ ] Tests pass; linters/static analysis clean

## Evidence
<!-- CI run link, test names, EXPLAIN output, screenshots -->

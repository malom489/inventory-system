# ADR-000: Toolchain and process baseline

**Status:** Accepted
**Date:** 2026-11-11

## Decision
- PHP 8.3, strict types everywhere
- PhpStorm as IDE (free student license)
- PSR-4 autoload (`App\` → `src/`)
- PHPStan level 8, PHP-CS-Fixer PSR-12, PHPUnit 10
- GitHub Actions gates every PR: cs:check → stan → test → audit
- Front controller in `public/`; config from env; secrets never committed
- DoD checklist (§17) is the PR template

## Consequences
- CI is the merge gate; nothing merges red
- The app DB user will later lack DDL and hold INSERT/SELECT-only on the ledger (§4.5)

# Inventory System

Multi-tenant inventory ledger system.

## Quick start
1. `cp .env.example .env` and set DB credentials
2. `composer install`
3. `composer ci` — must be green
4. `php -S localhost:8000 -t public` → `curl localhost:8000/health`

## Process
- ADRs in `docs/adr/`
- CI is the merge gate
- PRs follow the §17 DoD template

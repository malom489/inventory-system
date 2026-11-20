<?php

declare(strict_types=1);

namespace App\Core;

use PDO;

/**
 * Base class for queries that are NOT scoped to a tenant.
 *
 * Only for platform-level tables: tenants, users (identity), and future
 * platform admin/billing tables. Every row touched by a subclass must be
 * intentional, permission-checked at the service layer, and audit-logged
 * per §6 of the engineering principles.
 *
 * Regular business repositories (products, orders, etc.) MUST extend
 * TenantScopedRepository instead.
 */
abstract class PlatformRepository
{
    public function __construct(
        protected readonly PDO $pdo,
    ) {
    }

    /**
     * @param array<string, mixed> $params
     * @return array<int, array<string, mixed>>
     */
    protected function select(string $sql, array $params = []): array
    {
        $st = $this->pdo->prepare($sql);
        $st->execute($params);

        return $st->fetchAll();
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>|null
     */
    protected function one(string $sql, array $params = []): ?array
    {
        $rows = $this->select($sql . ' LIMIT 1', $params);

        return $rows[0] ?? null;
    }
}

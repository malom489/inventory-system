<?php

declare(strict_types=1);

namespace App\Core;

use LogicException;
use PDO;

abstract class TenantScopedRepository
{
    public function __construct(
        protected readonly PDO $pdo,
        protected readonly TenantContext $ctx,
    ) {
    }

    /**
     * @param array<string, mixed> $params
     * @return array<int, array<string, mixed>>
     */
    protected function select(string $sql, array $params = []): array
    {
        if (!str_contains($sql, '{TENANT}')) {
            throw new LogicException('Unscoped query: {TENANT} marker required');
        }

        $sql = str_replace('{TENANT}', 'tenant_id = :tenant', $sql);

        $st = $this->pdo->prepare($sql);
        $st->execute([':tenant' => $this->ctx->tenantId] + $params);

        return $st->fetchAll();
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function insert(string $table, array $data): int
    {
        $data['tenant_id'] = $this->ctx->tenantId;

        $cols = implode(',', array_keys($data));
        $ph   = implode(',', array_map(fn (string $c): string => ':' . $c, array_keys($data)));

        $this->pdo->prepare("INSERT INTO {$table} ({$cols}) VALUES ({$ph})")->execute($data);

        return (int) $this->pdo->lastInsertId();
    }
}

<?php

declare(strict_types=1);

namespace Tests\Isolation;

use App\Core\PdoFactory;
use App\Core\TenantContext;
use App\Core\TenantScopedRepository;
use LogicException;
use PDO;
use PHPUnit\Framework\TestCase;

final class TenantIsolationTest extends TestCase
{
    private PDO $pdo;
    private int $tenantA;
    private int $tenantB;

    protected function setUp(): void
    {
        $this->pdo = PdoFactory::make();

        $this->pdo->exec("DELETE FROM tenant_users WHERE tenant_id IN (SELECT id FROM tenants WHERE slug LIKE 'isolation-%')");
        $this->pdo->exec("DELETE FROM tenants WHERE slug LIKE 'isolation-%'");

        $this->tenantA = $this->insertTenant('isolation-a', 'Isolation A');
        $this->tenantB = $this->insertTenant('isolation-b', 'Isolation B');
    }

    public function testUnscopedQueryThrows(): void
    {
        $repo = $this->makeRepository($this->tenantA);

        $this->expectException(LogicException::class);
        $this->expectExceptionMessageMatches('/\{TENANT\}/');

        $repo->runUnscopedQuery();
    }

    public function testScopedQueryReturnsOnlyOwnRows(): void
    {
        $repoA = $this->makeRepository($this->tenantA);
        $repoB = $this->makeRepository($this->tenantB);

        $repoA->seedTenantUser(1001);
        $repoB->seedTenantUser(1001);

        self::assertSame(1, $repoA->countTenantUsers());
        self::assertSame(1, $repoB->countTenantUsers());
    }

    public function testInsertCannotSpoofTenant(): void
    {
        $repoA = $this->makeRepository($this->tenantA);

        $repoA->forceSeedForOtherTenant($this->tenantB, 5001);

        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM tenant_users WHERE tenant_id = :t AND user_id = 5001');
        $stmt->execute([':t' => $this->tenantB]);
        self::assertSame(0, (int) $stmt->fetchColumn(), 'Row must NOT be visible under tenant B');

        $stmt->execute([':t' => $this->tenantA]);
        self::assertSame(1, (int) $stmt->fetchColumn(), 'Row must be under tenant A');
    }

    private function insertTenant(string $slug, string $name): int
    {
        $st = $this->pdo->prepare(
            'INSERT INTO tenants (public_id, name, slug, status, plan, base_currency, timezone)
             VALUES (:public_id, :name, :slug, :status, :plan, :cur, :tz)'
        );
        $st->execute([
            ':public_id' => strtoupper(bin2hex(random_bytes(13))),
            ':name'      => $name,
            ':slug'      => $slug,
            ':status'    => 'active',
            ':plan'      => 'basic',
            ':cur'       => 'KES',
            ':tz'        => 'Africa/Nairobi',
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    private function makeRepository(int $tenantId): TenantTestRepository
    {
        return new class ($this->pdo, new TenantContext($tenantId, 1)) extends TenantScopedRepository implements TenantTestRepository {
            public function runUnscopedQuery(): array
            {
                return $this->select('SELECT COUNT(*) AS c FROM tenant_users');
            }

            public function countTenantUsers(): int
            {
                $rows = $this->select('SELECT COUNT(*) AS c FROM tenant_users WHERE {TENANT}');
                return (int) $rows[0]['c'];
            }

            public function seedTenantUser(int $userId): void
            {
                $this->insert('tenant_users', [
                    'user_id' => $userId,
                    'role_id' => 1,
                    'status'  => 'active',
                ]);
            }

            public function forceSeedForOtherTenant(int $otherTenantId, int $userId): void
            {
                $this->insert('tenant_users', [
                    'tenant_id' => $otherTenantId,
                    'user_id'   => $userId,
                    'role_id'   => 1,
                    'status'    => 'active',
                ]);
            }
        };
    }
}

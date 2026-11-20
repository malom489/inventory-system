<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Core\PdoFactory;
use App\Modules\Identity\TenantRepository;
use PDO;
use PHPUnit\Framework\TestCase;

final class TenantRepositoryTest extends TestCase
{
    private PDO $pdo;
    private TenantRepository $repo;

    protected function setUp(): void
    {
        $this->pdo = PdoFactory::make();
        $this->repo = new TenantRepository($this->pdo);

        $this->pdo->exec("DELETE FROM tenant_users WHERE tenant_id IN (SELECT id FROM tenants WHERE slug LIKE 'repo-test-%')");
        $this->pdo->exec("DELETE FROM tenants WHERE slug LIKE 'repo-test-%'");
    }

    public function testFindBySlugReturnsTenant(): void
    {
        $this->seedTenant('repo-test-acme', 'Acme Repo');

        $row = $this->repo->findBySlug('repo-test-acme');

        self::assertNotNull($row);
        self::assertSame('repo-test-acme', $row['slug']);
        self::assertSame('Acme Repo', $row['name']);
        self::assertSame('active', $row['status']);
    }

    public function testFindBySlugReturnsNullWhenMissing(): void
    {
        self::assertNull($this->repo->findBySlug('does-not-exist'));
    }

    public function testFindByPublicIdReturnsTenant(): void
    {
        $publicId = $this->seedTenant('repo-test-globex', 'Globex Repo');

        $row = $this->repo->findByPublicId($publicId);

        self::assertNotNull($row);
        self::assertSame($publicId, $row['public_id']);
        self::assertSame('repo-test-globex', $row['slug']);
    }

    public function testFindByIdReturnsTenant(): void
    {
        $this->seedTenant('repo-test-initech', 'Initech Repo');

        $bySlug = $this->repo->findBySlug('repo-test-initech');
        self::assertNotNull($bySlug);

        $byId = $this->repo->findById((int) $bySlug['id']);

        self::assertNotNull($byId);
        self::assertSame('repo-test-initech', $byId['slug']);
    }

    public function testFindByIdReturnsNullWhenMissing(): void
    {
        self::assertNull($this->repo->findById(999999));
    }

    private function seedTenant(string $slug, string $name): string
    {
        $publicId = strtoupper(bin2hex(random_bytes(13)));

        $st = $this->pdo->prepare(
            'INSERT INTO tenants (public_id, name, slug, status, plan, base_currency, timezone)
             VALUES (:public_id, :name, :slug, :status, :plan, :cur, :tz)'
        );
        $st->execute([
            ':public_id' => $publicId,
            ':name'      => $name,
            ':slug'      => $slug,
            ':status'    => 'active',
            ':plan'      => 'basic',
            ':cur'       => 'KES',
            ':tz'        => 'Africa/Nairobi',
        ]);

        return $publicId;
    }
}

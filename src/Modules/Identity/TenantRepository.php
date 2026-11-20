<?php

declare(strict_types=1);

namespace App\Modules\Identity;

use App\Core\PlatformRepository;

final class TenantRepository extends PlatformRepository
{
    /**
     * @return array<string, mixed>|null
     */
    public function findBySlug(string $slug): ?array
    {
        return $this->one(
            'SELECT id, public_id, name, slug, status, plan, base_currency, timezone
             FROM tenants
             WHERE slug = :slug',
            [':slug' => $slug],
        );
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findByPublicId(string $publicId): ?array
    {
        return $this->one(
            'SELECT id, public_id, name, slug, status, plan, base_currency, timezone
             FROM tenants
             WHERE public_id = :public_id',
            [':public_id' => $publicId],
        );
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findById(int $id): ?array
    {
        return $this->one(
            'SELECT id, public_id, name, slug, status, plan, base_currency, timezone
             FROM tenants
             WHERE id = :id',
            [':id' => $id],
        );
    }
}

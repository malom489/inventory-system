<?php

declare(strict_types=1);

namespace Tests\Isolation;

/**
 * Test-only interface declaring the helper methods our anonymous subclass exposes.
 * Not part of production code — exists solely so PHPStan can type-check the tests.
 */
interface TenantTestRepository
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function runUnscopedQuery(): array;

    public function countTenantUsers(): int;

    public function seedTenantUser(int $userId): void;

    public function forceSeedForOtherTenant(int $otherTenantId, int $userId): void;
}

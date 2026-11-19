<?php

declare(strict_types=1);

namespace App\Core;

final class TenantContext
{
    public function __construct(
        public readonly int $tenantId,
        public readonly int $userId,
    ) {
    }
}

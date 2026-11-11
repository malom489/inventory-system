<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

final class SmokeTest extends TestCase
{
    public function testEnvironmentIsWired(): void
    {
        self::assertTrue(extension_loaded('bcmath'));
        self::assertTrue(extension_loaded('pdo_mysql'));
        self::assertSame('8.3', PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION);
    }
}

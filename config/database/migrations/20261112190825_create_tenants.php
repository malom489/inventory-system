<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTenants extends AbstractMigration
{
    public function up(): void
    {
        $this->execute(<<<SQL
            CREATE TABLE tenants (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                public_id CHAR(26) NOT NULL,
                name VARCHAR(150) NOT NULL,
                slug VARCHAR(63) NOT NULL,
                status ENUM('trial','active','suspended','cancelled') NOT NULL DEFAULT 'trial',
                plan VARCHAR(30) NOT NULL,
                base_currency CHAR(3) NOT NULL,
                timezone VARCHAR(64) NOT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                UNIQUE KEY uq_tenants_public_id (public_id),
                UNIQUE KEY uq_tenants_slug (slug)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        SQL);
    }

    public function down(): void
    {
        $this->execute('DROP TABLE tenants');
    }
}
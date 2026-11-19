<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTenantUsers extends AbstractMigration
{
    public function up(): void
    {
        $this->execute(<<<SQL
           CREATE TABLE tenant_users (
                tenant_id BIGINT UNSIGNED NOT NULL,
                user_id BIGINT UNSIGNED NOT NULL,
                role_id BIGINT UNSIGNED NOT NULL,
                status ENUM('invited','active','disabled') NOT NULL DEFAULT 'invited',
                PRIMARY KEY (tenant_id, user_id),
                CONSTRAINT fk_tenant_users_tenant
                    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE RESTRICT
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 
        SQL);
    }

    public function down(): void
    {
        $this->execute('DROP TABLE tenant_users');
    }
}
<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateRoles extends AbstractMigration
{
    public function up(): void
    {
        $this->execute(<<<SQL
            CREATE TABLE roles (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                tenant_id BIGINT UNSIGNED NOT NULL,
                name VARCHAR(40) NOT NULL,
                permissions JSON NOT NULL,
                UNIQUE KEY uq_roles_tenant_name (tenant_id, name),
                UNIQUE KEY uq_roles_tenant_id (tenant_id, id),
                CONSTRAINT fk_roles_tenant
                    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE RESTRICT
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        SQL);
    }

    public function down(): void
    {
        $this->execute('DROP TABLE roles');
    }
}
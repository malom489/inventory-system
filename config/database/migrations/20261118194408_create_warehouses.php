<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateWarehouses extends AbstractMigration
{
    public function up(): void
    {
        $this->execute(<<<SQL
            CREATE TABLE warehouses (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                tenant_id BIGINT UNSIGNED NOT NULL,
                name VARCHAR(100) NOT NULL,
                is_active TINYINT(1) NOT NULL DEFAULT 1,
                UNIQUE KEY uq_warehouses_tenant_name (tenant_id, name),
                UNIQUE KEY uq_warehouses_tenant_id (tenant_id, id),
                CONSTRAINT fk_warehouses_tenant
                    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE RESTRICT
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        SQL);
    }

    public function down(): void
    {
        $this->execute('DROP TABLE warehouses');
    }
}
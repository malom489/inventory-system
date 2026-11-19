<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateCustomers extends AbstractMigration
{
    public function up(): void
    {
        $this->execute(<<<SQL
            CREATE TABLE customers (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                tenant_id BIGINT UNSIGNED NOT NULL,
                name VARCHAR(150) NOT NULL,
                is_active TINYINT(1) NOT NULL DEFAULT 1,
                UNIQUE KEY uq_customers_tenant_id (tenant_id, id),
                CONSTRAINT fk_customers_tenant
                    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE RESTRICT
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        SQL);
    }

    public function down(): void
    {
        $this->execute('DROP TABLE customers');
    }
}
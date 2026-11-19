<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateProducts extends AbstractMigration
{
    public function up(): void
    {
        $this->execute(<<<SQL
            CREATE TABLE products (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                tenant_id BIGINT UNSIGNED NOT NULL,
                public_id CHAR(26) NOT NULL,
                sku VARCHAR(64) NOT NULL,
                barcode VARCHAR(64) NULL,
                name VARCHAR(200) NOT NULL,
                unit VARCHAR(16) NOT NULL,
                sale_price DECIMAL(18,4) NOT NULL DEFAULT 0,
                tax_rate DECIMAL(5,2) NOT NULL DEFAULT 0,
                reorder_level DECIMAL(18,4) NOT NULL DEFAULT 0,
                is_active TINYINT(1) NOT NULL DEFAULT 1,
                version INT UNSIGNED NOT NULL DEFAULT 1,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                UNIQUE KEY uq_products_tenant_sku (tenant_id, sku),
                UNIQUE KEY uq_products_tenant_barcode (tenant_id, barcode),
                UNIQUE KEY uq_products_tenant_public_id (tenant_id, public_id),
                UNIQUE KEY uq_products_tenant_id (tenant_id, id),
                INDEX idx_products_tenant_name (tenant_id, name),
                CONSTRAINT fk_products_tenant
                    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE RESTRICT
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        SQL);
    }

    public function down(): void
    {
        $this->execute('DROP TABLE products');
    }
}
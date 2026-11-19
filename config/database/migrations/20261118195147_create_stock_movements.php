<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateStockMovements extends AbstractMigration
{
    public function up(): void
    {
        $this->execute(<<<SQL
            CREATE TABLE stock_movements (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                tenant_id BIGINT UNSIGNED NOT NULL,
                warehouse_id BIGINT UNSIGNED NOT NULL,
                product_id BIGINT UNSIGNED NOT NULL,
                type ENUM(
                    'opening','purchase_receipt','sale_issue','sale_return',
                    'purchase_return','adjustment','transfer_out','transfer_in'
                ) NOT NULL,
                qty_change DECIMAL(18,4) NOT NULL,
                unit_cost DECIMAL(18,4) NOT NULL DEFAULT 0,
                ref_type VARCHAR(30) NOT NULL,
                ref_id BIGINT UNSIGNED NOT NULL,
                reason VARCHAR(255) NULL,
                created_by BIGINT UNSIGNED NOT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_stock_movements_tenant_product_created (tenant_id, product_id, created_at),
                INDEX idx_stock_movements_tenant_ref (tenant_id, ref_type, ref_id),
                CONSTRAINT fk_stock_movements_warehouse
                    FOREIGN KEY (tenant_id, warehouse_id) REFERENCES warehouses(tenant_id, id) ON DELETE RESTRICT,
                CONSTRAINT fk_stock_movements_product
                    FOREIGN KEY (tenant_id, product_id) REFERENCES products(tenant_id, id) ON DELETE RESTRICT
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        SQL);
    }

    public function down(): void
    {
        $this->execute('DROP TABLE stock_movements');
    }
}
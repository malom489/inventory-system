<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateStockLevels extends AbstractMigration
{
    public function up(): void
    {
        $this->execute(<<<SQL
            CREATE TABLE stock_levels (
                tenant_id BIGINT UNSIGNED NOT NULL,
                warehouse_id BIGINT UNSIGNED NOT NULL,
                product_id BIGINT UNSIGNED NOT NULL,
                qty_on_hand DECIMAL(18,4) NOT NULL DEFAULT 0,
                qty_reserved DECIMAL(18,4) NOT NULL DEFAULT 0,
                avg_cost DECIMAL(18,4) NOT NULL DEFAULT 0,
                PRIMARY KEY (tenant_id, warehouse_id, product_id),
                CONSTRAINT fk_stock_levels_warehouse
                    FOREIGN KEY (tenant_id, warehouse_id) REFERENCES warehouses(tenant_id, id) ON DELETE RESTRICT,
                CONSTRAINT fk_stock_levels_product
                    FOREIGN KEY (tenant_id, product_id) REFERENCES products(tenant_id, id) ON DELETE RESTRICT,
                CONSTRAINT chk_stock_levels_reserved CHECK (qty_reserved >= 0)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        SQL);
    }

    public function down(): void
    {
        $this->execute('DROP TABLE stock_levels');
    }
}
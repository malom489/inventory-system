<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateSalesOrderLines extends AbstractMigration
{
    public function up(): void
    {
        $this->execute(<<<SQL
            CREATE TABLE sales_order_lines (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                tenant_id BIGINT UNSIGNED NOT NULL,
                order_id BIGINT UNSIGNED NOT NULL,
                product_id BIGINT UNSIGNED NOT NULL,
                sku VARCHAR(64) NOT NULL,
                name VARCHAR(200) NOT NULL,
                qty DECIMAL(18,4) NOT NULL,
                unit_price DECIMAL(18,4) NOT NULL,
                tax_rate DECIMAL(5,2) NOT NULL,
                line_total DECIMAL(18,2) NOT NULL,
                CONSTRAINT chk_sales_order_lines_qty CHECK (qty > 0),
                CONSTRAINT fk_sales_order_lines_order
                    FOREIGN KEY (tenant_id, order_id) REFERENCES sales_orders(tenant_id, id) ON DELETE CASCADE,
                CONSTRAINT fk_sales_order_lines_product
                    FOREIGN KEY (tenant_id, product_id) REFERENCES products(tenant_id, id) ON DELETE RESTRICT
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        SQL);
    }

    public function down(): void
    {
        $this->execute('DROP TABLE sales_order_lines');
    }
}
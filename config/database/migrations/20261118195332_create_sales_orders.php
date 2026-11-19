<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateSalesOrders extends AbstractMigration
{
    public function up(): void
    {
        $this->execute(<<<SQL
            CREATE TABLE sales_orders (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                tenant_id BIGINT UNSIGNED NOT NULL,
                public_id CHAR(26) NOT NULL,
                doc_no VARCHAR(30) NOT NULL,
                customer_id BIGINT UNSIGNED NOT NULL,
                warehouse_id BIGINT UNSIGNED NOT NULL,
                status ENUM('draft','confirmed','fulfilled','invoiced','cancelled') NOT NULL DEFAULT 'draft',
                subtotal DECIMAL(18,2) NOT NULL,
                tax_total DECIMAL(18,2) NOT NULL,
                total DECIMAL(18,2) NOT NULL,
                currency CHAR(3) NOT NULL,
                idempotency_key VARCHAR(64) NULL,
                version INT UNSIGNED NOT NULL DEFAULT 1,
                created_by BIGINT UNSIGNED NOT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY uq_sales_orders_tenant_doc_no (tenant_id, doc_no),
                UNIQUE KEY uq_sales_orders_tenant_idempotency (tenant_id, idempotency_key),
                UNIQUE KEY uq_sales_orders_tenant_public_id (tenant_id, public_id),
                UNIQUE KEY uq_sales_orders_tenant_id (tenant_id, id),
                INDEX idx_sales_orders_tenant_status_created (tenant_id, status, created_at),
                CONSTRAINT fk_sales_orders_tenant
                    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE RESTRICT,
                CONSTRAINT fk_sales_orders_customer
                    FOREIGN KEY (tenant_id, customer_id) REFERENCES customers(tenant_id, id) ON DELETE RESTRICT,
                CONSTRAINT fk_sales_orders_warehouse
                    FOREIGN KEY (tenant_id, warehouse_id) REFERENCES warehouses(tenant_id, id) ON DELETE RESTRICT
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        SQL);
    }

    public function down(): void
    {
        $this->execute('DROP TABLE sales_orders');
    }
}
<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateAuditLog extends AbstractMigration
{
    public function up(): void
    {
        $this->execute(<<<SQL
            CREATE TABLE audit_log (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                tenant_id BIGINT UNSIGNED NOT NULL,
                user_id BIGINT UNSIGNED NULL,
                action VARCHAR(50) NOT NULL,
                entity VARCHAR(40) NOT NULL,
                entity_id BIGINT UNSIGNED NOT NULL,
                before_json JSON NULL,
                after_json JSON NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_audit_log_entity (tenant_id, entity, entity_id, created_at),
                CONSTRAINT fk_audit_log_tenant
                    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE RESTRICT
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        SQL);
    }

    public function down(): void
    {
        $this->execute('DROP TABLE audit_log');
    }
}
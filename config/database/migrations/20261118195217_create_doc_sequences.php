<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateDocSequences extends AbstractMigration
{
    public function up(): void
    {
        $this->execute(<<<SQL
            CREATE TABLE doc_sequences (
                tenant_id BIGINT UNSIGNED NOT NULL,
                doc_type VARCHAR(20) NOT NULL,
                prefix VARCHAR(10) NOT NULL,
                next_no BIGINT UNSIGNED NOT NULL DEFAULT 1,
                PRIMARY KEY (tenant_id, doc_type),
                CONSTRAINT fk_doc_sequences_tenant
                    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE RESTRICT
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        SQL);
    }

    public function down(): void
    {
        $this->execute('DROP TABLE doc_sequences');
    }
}
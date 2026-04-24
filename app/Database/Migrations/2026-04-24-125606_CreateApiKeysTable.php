<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateApiKeysTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_email' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'key_label' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'api_key' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'ip_whitelist' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'null'       => true,
            ],
            'last_used_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null, // akan diatur via query di bawah
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('api_keys');

        // Menyesuaikan default CURRENT_TIMESTAMP sesuai dump SQL Anda
        $this->db->query("ALTER TABLE `api_keys` MODIFY `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP");
    }

    public function down()
    {
        $this->forge->dropTable('api_keys');
    }
}
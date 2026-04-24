<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateApiLogsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => false, // Mengikuti dump SQL Anda (int NOT NULL)
                'auto_increment' => true,
            ],
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
                'null'       => true,
            ],
            'method' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'uri' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'payload' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status_code' => [
                'type'       => 'INT',
                'null'       => true,
            ],
            'user_agent' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'created_at' => [
                'type'    => 'TIMESTAMP',
                'null'    => true,
                'default' => null, // akan diatur via query di bawah
            ],
            'user_id' => [
                'type' => 'INT',
                'null' => true,
            ],
            'user_email' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        
        // Menambahkan Index untuk performa Query Chart
        $this->forge->addKey('created_at');
        $this->forge->addKey('status_code');
        $this->forge->addKey('user_email');

        $this->forge->createTable('api_logs');

        // Menyesuaikan default CURRENT_TIMESTAMP
        $this->db->query("ALTER TABLE `api_logs` MODIFY `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP");
    }

    public function down()
    {
        $this->forge->dropTable('api_logs');
    }
}
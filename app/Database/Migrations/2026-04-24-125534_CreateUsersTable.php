<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'role' => [
                'type'       => 'ENUM',
                'constraint' => ['superadmin', 'user'],
                'default'    => 'user',
                'null'       => true,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'reset_token' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'reset_expires' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('email');
        $this->forge->createTable('users');

        // Mengatur default timestamp untuk created_at & updated_at via query manual (CI4 Forge terbatas untuk CURRENT_TIMESTAMP)
        $this->db->query("ALTER TABLE `users` MODIFY `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP");
        $this->db->query("ALTER TABLE `users` MODIFY `updated_at` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP");
    }

    public function down()
    {
        // Matikan FK check agar tidak error saat rollback jika ada tabel lain yang merujuk ke sini
        $this->db->query("SET FOREIGN_KEY_CHECKS = 0");
        $this->forge->dropTable('users');
        $this->db->query("SET FOREIGN_KEY_CHECKS = 1");
    }
}
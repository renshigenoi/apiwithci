<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStoreUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => false,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'null' => false,
            ],
            'store_id' => [
                'type' => 'INT',
                'null' => false,
            ],
            'role' => [
                'type'       => 'ENUM',
                'constraint' => ['owner', 'manager', 'staff'],
                'default'    => 'staff',
            ],
            'is_default' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
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
        $this->forge->addKey('user_id');
        $this->forge->addKey('store_id');
        $this->forge->createTable('store_users');
        
        // Atur default timestamp via query
        $this->db->query("ALTER TABLE `store_users` MODIFY `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP");
    }

    public function down()
    {
        $this->forge->dropTable('store_users');
    }
}
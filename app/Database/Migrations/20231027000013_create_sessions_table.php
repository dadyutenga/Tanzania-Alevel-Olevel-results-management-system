<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSessionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'session' => [
                'type'       => 'VARCHAR',
                'constraint' => '60',
                'null'       => false,
            ],
            'is_active' => [
                'type'       => 'ENUM',
                'constraint' => ['yes', 'no'],
                'null'       => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false, // Model implies NOT NULL through callbacks/rules
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => false, // Model implies NOT NULL through callbacks/rules
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('sessions');
    }

    public function down()
    {
        $this->forge->dropTable('sessions');
    }
}

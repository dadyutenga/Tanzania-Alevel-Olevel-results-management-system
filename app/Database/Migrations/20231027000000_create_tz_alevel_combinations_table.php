<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTzAlevelCombinationsTable extends Migration
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
            'combination_code' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'null'       => false,
                'unique'     => true,
            ],
            'combination_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'is_active' => [
                'type'       => 'ENUM',
                'constraint' => ['yes', 'no'],
                'default'    => 'yes',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tz_alevel_combinations');
    }

    public function down()
    {
        $this->forge->dropTable('tz_alevel_combinations');
    }
}

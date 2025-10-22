<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlevelCombinations extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'CHAR',
                'constraint'     => 36,
            ],
            'combination_code' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'unique'     => true,
            ],
            'combination_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'is_active' => [
                'type'       => 'ENUM',
                'constraint' => ['yes', 'no'],
                'default'    => 'yes',
            ],
            'school_id' => [
                'type'       => 'CHAR',
                'constraint' => 36,
                'null'       => true,
            ],
            'created_by' => [
                'type'       => 'CHAR',
                'constraint' => 36,
                'null'       => true,
            ],
            'updated_by' => [
                'type'       => 'CHAR',
                'constraint' => 36,
                'null'       => true,
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
        $this->forge->addKey('school_id');
        $this->forge->addKey('created_by');
        $this->forge->addKey('updated_by');
        $this->forge->createTable('tz_alevel_combinations');
    }

    public function down()
    {
        $this->forge->dropTable('tz_alevel_combinations');
    }
}

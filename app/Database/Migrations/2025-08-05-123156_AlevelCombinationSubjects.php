<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlevelCombinationSubjects extends Migration
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
            'combination_id' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
            ],
            'subject_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'subject_type' => [
                'type'       => 'ENUM',
                'constraint' => ['major', 'additional'],
                'default'    => 'major',
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
        $this->forge->addForeignKey('combination_id', 'tz_alevel_combinations', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tz_alevel_combination_subjects');
    }

    public function down()
    {
        $this->forge->dropTable('tz_alevel_combination_subjects');
    }
}

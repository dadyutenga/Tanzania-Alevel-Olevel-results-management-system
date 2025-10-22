<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlevelCombinationSubjects extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'CHAR',
                'constraint'     => 36,
            ],
            'combination_id' => [
                'type'       => 'CHAR',
                'constraint' => 36,
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
        $this->forge->addKey('combination_id');
        $this->forge->addKey('school_id');
        $this->forge->addKey('created_by');
        $this->forge->addKey('updated_by');
        $this->forge->addForeignKey('combination_id', 'tz_alevel_combinations', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tz_alevel_combination_subjects');
    }

    public function down()
    {
        $this->forge->dropTable('tz_alevel_combination_subjects');
    }
}

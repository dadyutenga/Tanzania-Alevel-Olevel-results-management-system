<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTzAlevelCombinationSubjectsTable extends Migration
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
                'null'       => false,
            ],
            'subject_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'subject_type' => [
                'type'       => 'ENUM',
                'constraint' => ['major', 'additional'],
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
        // It's good practice to add a foreign key constraint if combination_id refers to another table.
        // However, based on the current task, I'm only creating the table structure.
        // If needed, this can be added later:
        // $this->forge->addForeignKey('combination_id', 'tz_alevel_combinations', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tz_alevel_combination_subjects');
    }

    public function down()
    {
        $this->forge->dropTable('tz_alevel_combination_subjects');
    }
}

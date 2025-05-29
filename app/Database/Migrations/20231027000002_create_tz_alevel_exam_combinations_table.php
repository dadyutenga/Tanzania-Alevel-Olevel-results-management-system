<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTzAlevelExamCombinationsTable extends Migration
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
            'exam_id' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
                'null'       => false,
            ],
            'combination_id' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
                'null'       => false,
            ],
            'class_id' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
                'null'       => false,
            ],
            'session_id' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
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
        // Foreign key constraints can be added here if needed, for example:
        // $this->forge->addForeignKey('exam_id', 'tz_exams', 'id', 'CASCADE', 'CASCADE');
        // $this->forge->addForeignKey('combination_id', 'tz_alevel_combinations', 'id', 'CASCADE', 'CASCADE');
        // $this->forge->addForeignKey('class_id', 'tz_classes', 'id', 'CASCADE', 'CASCADE');
        // $this->forge->addForeignKey('session_id', 'tz_sessions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tz_alevel_exam_combinations');
    }

    public function down()
    {
        $this->forge->dropTable('tz_alevel_exam_combinations');
    }
}

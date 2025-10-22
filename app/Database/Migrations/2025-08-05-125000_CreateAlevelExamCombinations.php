<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAlevelExamCombinations extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'CHAR',
                'constraint' => 36,
            ],
            'exam_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'null' => false,
            ],
            'combination_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'null' => false,
            ],
            'class_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'null' => false,
            ],
            'session_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'null' => false,
            ],
            'is_active' => [
                'type' => 'ENUM',
                'constraint' => ['yes', 'no'],
                'default' => 'yes',
                'null' => false,
            ],
            'school_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'null' => true,
            ],
            'created_by' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'null' => true,
            ],
            'updated_by' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'null' => true,
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

        // Adding Primary Key
        $this->forge->addKey('id', true);
        $this->forge->addKey('exam_id');
        $this->forge->addKey('combination_id');
        $this->forge->addKey('class_id');
        $this->forge->addKey('session_id');
        $this->forge->addKey('school_id');
        $this->forge->addKey('created_by');
        $this->forge->addKey('updated_by');
        
        // Adding Foreign Keys
        $this->forge->addForeignKey('exam_id', 'tz_exams', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('combination_id', 'tz_alevel_combinations', 'id', 'CASCADE', 'CASCADE'); // Fixed table name
        $this->forge->addForeignKey('class_id', 'classes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('session_id', 'sessions', 'id', 'CASCADE', 'CASCADE');
        
        // Adding Unique Key for exam combination
        $this->forge->addUniqueKey(['exam_id', 'combination_id', 'class_id', 'session_id']);

        // Create Table
        $this->forge->createTable('tz_alevel_exam_combinations');
    }

    public function down()
    {
        $this->forge->dropTable('tz_alevel_exam_combinations');
    }
}

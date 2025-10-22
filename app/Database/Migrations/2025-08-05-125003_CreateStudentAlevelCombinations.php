<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStudentAlevelCombinations extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'CHAR',
                'constraint' => 36,
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
            'section_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'null' => true,
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
        $this->forge->addKey('combination_id');
        $this->forge->addKey('class_id');
        $this->forge->addKey('section_id');
        $this->forge->addKey('session_id');
        $this->forge->addKey('school_id');
        $this->forge->addKey('created_by');
        $this->forge->addKey('updated_by');
        
        // Adding Foreign Keys
        $this->forge->addForeignKey('combination_id', 'tz_alevel_combinations', 'id', 'CASCADE', 'CASCADE'); // Fixed table name
        $this->forge->addForeignKey('class_id', 'classes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('section_id', 'sections', 'id', 'CASCADE', 'SET NULL'); // Changed to SET NULL
        $this->forge->addForeignKey('session_id', 'sessions', 'id', 'CASCADE', 'CASCADE');
        
        // Adding Unique Key for student combination
        $this->forge->addUniqueKey(['combination_id', 'class_id', 'section_id', 'session_id']);

        // Create Table
        $this->forge->createTable('tz_student_alevel_combinations');
    }

    public function down()
    {
        $this->forge->dropTable('tz_student_alevel_combinations');
    }
}

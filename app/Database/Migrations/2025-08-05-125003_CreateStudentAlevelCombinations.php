<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStudentAlevelCombinations extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'combination_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'class_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'section_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true, // Changed to true as it's optional
            ],
            'session_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'is_active' => [
                'type' => 'ENUM',
                'constraint' => ['yes', 'no'],
                'default' => 'yes',
                'null' => false,
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

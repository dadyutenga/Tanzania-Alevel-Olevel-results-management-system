<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStudentSessions extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'CHAR',
                'constraint' => 36,
            ],
            'session_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'null' => false,
            ],
            'student_id' => [
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
        $this->forge->addKey('session_id');
        $this->forge->addKey('student_id');
        $this->forge->addKey('class_id');
        $this->forge->addKey('section_id');
        $this->forge->addKey('school_id');
        $this->forge->addKey('created_by');
        $this->forge->addKey('updated_by');
        
        // Adding Foreign Keys
        $this->forge->addForeignKey('session_id', 'sessions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('student_id', 'students', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('class_id', 'classes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('section_id', 'sections', 'id', 'CASCADE', 'CASCADE');
        
        // Adding Unique Key for student-session combination
        $this->forge->addUniqueKey(['student_id', 'session_id']);

        // Create Table
        $this->forge->createTable('student_session');
    }

    public function down()
    {
        $this->forge->dropTable('student_session');
    }
}

<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTzAlevelSubjectMarksTable extends Migration
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
            'student_id' => [
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
            'combination_id' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
                'null'       => false,
            ],
            'subject_id' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
                'null'       => false,
            ],
            'marks_obtained' => [
                'type'       => 'INT',
                'constraint' => 3, // Assuming marks are between 0-100
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
        // Potential foreign keys: exam_id, student_id, class_id, session_id, combination_id, subject_id
        // $this->forge->addForeignKey('exam_id', 'tz_exams', 'id', 'CASCADE', 'CASCADE');
        // $this->forge->addForeignKey('student_id', 'students', 'id', 'CASCADE', 'CASCADE');
        // ... and so on for other foreign keys
        $this->forge->createTable('tz_alevel_subject_marks');
    }

    public function down()
    {
        $this->forge->dropTable('tz_alevel_subject_marks');
    }
}

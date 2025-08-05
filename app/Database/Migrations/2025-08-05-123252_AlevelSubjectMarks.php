<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlevelSubjectMarks extends Migration
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
            ],
            'student_id' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
            ],
            'class_id' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
            ],
            'session_id' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
            ],
            'combination_id' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
            ],
            'subject_id' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
            ],
            'marks_obtained' => [
                'type'       => 'INT',
                'constraint' => 5,
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
        $this->forge->addForeignKey('exam_id', 'tz_exams', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('student_id', 'students', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('class_id', 'classes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('session_id', 'sessions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('combination_id', 'tz_alevel_combinations', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('subject_id', 'tz_alevel_combination_subjects', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tz_alevel_subject_marks');
    }

    public function down()
    {
        $this->forge->dropTable('tz_alevel_subject_marks');
    }
}

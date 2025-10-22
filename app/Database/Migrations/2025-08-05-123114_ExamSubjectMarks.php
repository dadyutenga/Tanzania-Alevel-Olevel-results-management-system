<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ExamSubjectMarks extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'CHAR',
                'constraint'     => 36,
            ],
            'exam_id' => [
                'type'       => 'CHAR',
                'constraint' => 36,
            ],
            'student_id' => [
                'type'       => 'CHAR',
                'constraint' => 36,
            ],
            'class_id' => [
                'type'       => 'CHAR',
                'constraint' => 36,
            ],
            'session_id' => [
                'type'       => 'CHAR',
                'constraint' => 36,
            ],
            'exam_subject_id' => [
                'type'       => 'CHAR',
                'constraint' => 36,
            ],
            'marks_obtained' => [
                'type'       => 'INT',
                'constraint' => 5,
                'null'       => true,
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
        $this->forge->addKey('exam_id');
        $this->forge->addKey('student_id');
        $this->forge->addKey('class_id');
        $this->forge->addKey('session_id');
        $this->forge->addKey('exam_subject_id');
        $this->forge->addKey('school_id');
        $this->forge->addKey('created_by');
        $this->forge->addKey('updated_by');
        $this->forge->addForeignKey('exam_id', 'tz_exams', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('student_id', 'students', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('class_id', 'classes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('session_id', 'sessions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('exam_subject_id', 'tz_exam_subjects', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tz_exam_subject_marks');
    }

    public function down()
    {
        $this->forge->dropTable('tz_exam_subject_marks');
    }
}

<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateExamMarksTable extends Migration
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
            'student_session_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'exam_subject_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'marks_obtained' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
            ],
            'is_absent' => [
                'type' => 'ENUM',
                'constraint' => ['yes', 'no'],
                'default' => 'no',
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
        $this->forge->addForeignKey('student_session_id', 'student_sessions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('exam_subject_id', 'exam_subjects', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('exam_marks');
    }

    public function down()
    {
        $this->forge->dropTable('exam_marks');
    }
}

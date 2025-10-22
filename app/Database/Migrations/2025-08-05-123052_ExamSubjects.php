<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ExamSubjects extends Migration
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
            'subject_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'max_marks' => [
                'type'       => 'INT',
                'constraint' => 5,
            ],
            'passing_marks' => [
                'type'       => 'INT',
                'constraint' => 5,
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
        $this->forge->addKey('school_id');
        $this->forge->addKey('created_by');
        $this->forge->addKey('updated_by');
        $this->forge->addForeignKey('exam_id', 'tz_exams', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tz_exam_subjects');
    }

    public function down()
    {
        $this->forge->dropTable('tz_exam_subjects');
    }
}

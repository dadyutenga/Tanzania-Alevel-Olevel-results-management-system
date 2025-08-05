<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ExamSubjects extends Migration
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
        $this->forge->createTable('tz_exam_subjects');
    }

    public function down()
    {
        $this->forge->dropTable('tz_exam_subjects');
    }
}

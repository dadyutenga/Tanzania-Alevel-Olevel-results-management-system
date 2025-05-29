<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTzExamSubjectsTable extends Migration
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
            'subject_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'max_marks' => [
                'type'       => 'INT',
                'constraint' => 3, // Assuming max marks usually e.g. 100, 200
                'null'       => false,
            ],
            'passing_marks' => [
                'type'       => 'INT',
                'constraint' => 3, // Assuming passing marks usually e.g. 33, 40
                'null'       => false,
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
        // Potential foreign key: exam_id
        // $this->forge->addForeignKey('exam_id', 'tz_exams', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tz_exam_subjects');
    }

    public function down()
    {
        $this->forge->dropTable('tz_exam_subjects');
    }
}

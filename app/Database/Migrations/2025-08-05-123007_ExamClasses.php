<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ExamClasses extends Migration
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
        $this->forge->addForeignKey('class_id', 'classes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('session_id', 'sessions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tz_exam_classes');
    }

    public function down()
    {
        $this->forge->dropTable('tz_exam_classes');
    }
}

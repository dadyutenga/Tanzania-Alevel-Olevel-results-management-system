<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Exams extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'CHAR',
                'constraint'     => 36,
            ],
            'exam_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'exam_date' => [
                'type' => 'DATE',
            ],
            'session_id' => [
                'type'       => 'CHAR',
                'constraint' => 36,
            ],
            'is_active' => [
                'type'       => 'ENUM',
                'constraint' => ['yes', 'no'],
                'default'    => 'yes',
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
        $this->forge->addKey('session_id');
        $this->forge->addKey('school_id');
        $this->forge->addKey('created_by');
        $this->forge->addKey('updated_by');
        $this->forge->addForeignKey('session_id', 'sessions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tz_exams');
    }

    public function down()
    {
        $this->forge->dropTable('tz_exams');
    }
}

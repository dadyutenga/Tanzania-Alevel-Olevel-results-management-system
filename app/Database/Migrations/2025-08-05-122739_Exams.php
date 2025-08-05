<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Exams extends Migration
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
            'exam_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'exam_date' => [
                'type' => 'DATE',
            ],
            'session_id' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
            ],
            'is_active' => [
                'type'       => 'ENUM',
                'constraint' => ['yes', 'no'],
                'default'    => 'yes',
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
        $this->forge->addForeignKey('session_id', 'sessions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tz_exams');
    }

    public function down()
    {
        $this->forge->dropTable('tz_exams');
    }
}

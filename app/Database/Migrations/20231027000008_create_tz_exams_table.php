<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTzExamsTable extends Migration
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
                'null'       => false,
            ],
            'exam_date' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'session_id' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
                'null'       => false,
            ],
            'is_active' => [
                'type'       => 'ENUM',
                'constraint' => ['yes', 'no'],
                'null'       => false,
            ],
            // No created_at or updated_at fields as $useTimestamps is false in the model
        ]);
        $this->forge->addKey('id', true);
        // Potential foreign key: session_id
        // $this->forge->addForeignKey('session_id', 'sessions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tz_exams');
    }

    public function down()
    {
        $this->forge->dropTable('tz_exams');
    }
}

<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateResultGradesTable extends Migration
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
            'grade' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
            ],
            'min_percentage' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'max_percentage' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'remarks' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'is_active' => [
                'type' => 'ENUM',
                'constraint' => ['yes', 'no'],
                'default' => 'yes',
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
        $this->forge->createTable('result_grades');
    }

    public function down()
    {
        $this->forge->dropTable('result_grades');
    }
}

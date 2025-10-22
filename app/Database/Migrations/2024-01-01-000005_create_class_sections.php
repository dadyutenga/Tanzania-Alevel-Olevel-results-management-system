<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateClassSections extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'CHAR',
                'constraint' => 36,
            ],
            'class_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
            ],
            'section_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
            ],
            'is_active' => [
                'type' => 'ENUM',
                'constraint' => ['yes', 'no'],
                'default' => 'yes',
            ],
            'school_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'null' => true,
            ],
            'created_by' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'null' => true,
            ],
            'updated_by' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'null' => true,
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
        $this->forge->addKey('class_id');
        $this->forge->addKey('section_id');
        $this->forge->addKey('school_id');
        $this->forge->addKey('created_by');
        $this->forge->addKey('updated_by');
        $this->forge->addForeignKey('class_id', 'classes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('section_id', 'sections', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('class_sections');
    }

    public function down()
    {
        $this->forge->dropTable('class_sections');
    }
}

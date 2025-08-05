<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSettings extends Migration
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
            'school_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'total_classes' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ],
            'school_year' => [
                'type' => 'VARCHAR',
                'constraint' => 9,
                'null' => false,
            ],
            'school_address' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'school_logo' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'contact_email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'contact_phone' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
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
        $this->forge->createTable('tz_web_setting');

        // Add default settings
        $defaultSettings = [
            'school_name' => 'School Name',
            'total_classes' => 12,
            'school_year' => '2024-2025',
            'is_active' => 'yes',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        
        $this->db->table('tz_web_setting')->insert($defaultSettings);
    }

    public function down()
    {
        $this->forge->dropTable('tz_web_setting');
    }
}

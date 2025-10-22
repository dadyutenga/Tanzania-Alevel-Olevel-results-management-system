<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSettings extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'CHAR',
                'constraint' => 36,
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
        $this->forge->addKey('school_id');
        $this->forge->addKey('created_by');
        $this->forge->addKey('updated_by');
        $this->forge->createTable('tz_web_setting');

        // Add default settings
        $defaultSettings = [
            'id' => $this->generateUuid(),
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

    private function generateUuid(): string
    {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}

<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTzWebSettingTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 1, // Usually settings table has one row, ID 1
                'unsigned'       => true,
                'null'           => false,
                // No auto_increment
            ],
            'school_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'total_classes' => [
                'type'       => 'INT',
                'constraint' => 5, // Max 99999 classes
                'null'       => false,
            ],
            'school_year' => [
                'type'       => 'VARCHAR',
                'constraint' => '9', // Format YYYY-YYYY
                'null'       => false,
            ],
            'school_address' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'school_logo' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'contact_email' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'contact_phone' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'is_active' => [
                'type'       => 'ENUM',
                'constraint' => ['yes', 'no'],
                'null'       => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);
        $this->forge->addKey('id', true); // Primary key
        $this->forge->createTable('tz_web_setting');
    }

    public function down()
    {
        $this->forge->dropTable('tz_web_setting');
    }
}

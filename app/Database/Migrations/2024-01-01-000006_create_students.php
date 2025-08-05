<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStudents extends Migration
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
            'admission_no' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
                'unique' => true,
            ],
            'admission_date' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'firstname' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
            'middlename' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'lastname' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
            'image' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'dob' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'gender' => [
                'type' => 'ENUM',
                'constraint' => ['male', 'female', 'other'],
                'null' => false,
            ],
            'permanent_address' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'guardian_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
            'guardian_relation' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'guardian_phone' => [
                'type' => 'VARCHAR',
                'constraint' => 15,
                'null' => false,
            ],
            'guardian_address' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'guardian_email' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'height' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
            ],
            'weight' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
            ],
            'is_active' => [
                'type' => 'ENUM',
                'constraint' => ['yes', 'no'],
                'default' => 'yes',
                'null' => false,
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
        $this->forge->createTable('students');
    }

    public function down()
    {
        $this->forge->dropTable('students');
    }
}

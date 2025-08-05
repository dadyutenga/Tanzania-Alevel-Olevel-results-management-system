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
            'parent_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ],
            'admission_no' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'roll_no' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'admission_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'firstname' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'middlename' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'lastname' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'rte' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'image' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'mobileno' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'state' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'city' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'pincode' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'religion' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'dob' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'gender' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'current_address' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'permanent_address' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'father_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'father_phone' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'father_occupation' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'mother_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'mother_phone' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'mother_occupation' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'guardian_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'guardian_relation' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'guardian_phone' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'guardian_occupation' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'guardian_address' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'guardian_email' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'father_pic' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'mother_pic' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'guardian_pic' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
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
        $this->forge->createTable('students');
    }

    public function down()
    {
        $this->forge->dropTable('students');
    }
}

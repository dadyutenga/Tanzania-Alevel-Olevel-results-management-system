<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateStudentsTableAddMissingFields extends Migration
{
    public function up()
    {
        // Drop all unwanted columns to align with StudentModel
        $fields = [
            'admission_no',
            'admission_date',
            'permanent_address',
            'guardian_name',
            'guardian_relation',
            'guardian_address',
            'guardian_email',
            'height',
            'weight'
        ];

        foreach ($fields as $field) {
            if ($this->db->fieldExists($field, 'students')) {
                $this->forge->dropColumn('students', $field);
            }
        }
    }

    public function down()
    {
        // Add back the columns if needed to rollback
        $fields = [
            'admission_no' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'id'
            ],
            'admission_date' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'admission_no'
            ],
            'permanent_address' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'gender'
            ],
            'guardian_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'permanent_address'
            ],
            'guardian_relation' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'guardian_name'
            ],
            'guardian_address' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'guardian_phone'
            ],
            'guardian_email' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'guardian_address'
            ],
            'height' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => true,
                'after' => 'guardian_email'
            ],
            'weight' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => true,
                'after' => 'height'
            ]
        ];

        $this->forge->addColumn('students', $fields);
    }
}

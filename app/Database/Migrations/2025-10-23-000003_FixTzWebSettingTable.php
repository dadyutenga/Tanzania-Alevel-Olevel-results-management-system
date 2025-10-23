<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixTzWebSettingTable extends Migration
{
    public function up()
    {
        // Drop school_id column if exists
        if ($this->db->fieldExists('school_id', 'tz_web_setting')) {
            $this->forge->dropColumn('tz_web_setting', 'school_id');
        }

        // Modify school_logo to LONGTEXT for base64 storage
        $fields = [
            'school_logo' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
        ];
        
        $this->forge->modifyColumn('tz_web_setting', $fields);
    }

    public function down()
    {
        // Add back school_id column
        $this->forge->addColumn('tz_web_setting', [
            'school_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'null' => true,
                'after' => 'is_active',
            ],
        ]);

        // Revert school_logo back to VARCHAR
        $fields = [
            'school_logo' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
        ];
        
        $this->forge->modifyColumn('tz_web_setting', $fields);
    }
}

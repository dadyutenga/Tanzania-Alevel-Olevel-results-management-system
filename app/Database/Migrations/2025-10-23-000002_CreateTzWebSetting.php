<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTzWebSetting extends Migration
{
    public function up()
    {
        $this->forge->addField([
            "id" => [
                "type" => "CHAR",
                "constraint" => 36,
            ],
            "school_name" => [
                "type" => "VARCHAR",
                "constraint" => 255,
                "null" => false,
            ],
            "total_classes" => [
                "type" => "INT",
                "constraint" => 11,
                "null" => false,
            ],
            "school_year" => [
                "type" => "VARCHAR",
                "constraint" => 9,
                "null" => false,
            ],
            "school_address" => [
                "type" => "TEXT",
                "null" => true,
            ],
            "school_logo" => [
                "type" => "VARCHAR",
                "constraint" => 255,
                "null" => true,
            ],
            "contact_email" => [
                "type" => "VARCHAR",
                "constraint" => 255,
                "null" => true,
            ],
            "contact_phone" => [
                "type" => "VARCHAR",
                "constraint" => 20,
                "null" => true,
            ],
            "is_active" => [
                "type" => "ENUM",
                "constraint" => ["yes", "no"],
                "default" => "yes",
                "null" => false,
            ],
            "created_by" => [
                "type" => "CHAR",
                "constraint" => 36,
                "null" => true,
            ],
            "updated_by" => [
                "type" => "CHAR",
                "constraint" => 36,
                "null" => true,
            ],
            "created_at" => [
                "type" => "DATETIME",
                "null" => true,
            ],
            "updated_at" => [
                "type" => "DATETIME",
                "null" => true,
            ],
        ]);

        $this->forge->addKey("id", true);
        $this->forge->createTable("tz_web_setting", true);
    }

    public function down()
    {
        $this->forge->dropTable("tz_web_setting", true);
    }
}

<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserRoles extends Migration
{
    public function up()
    {
        $this->forge->addField([
            "id" => [
                "type" => "CHAR",
                "constraint" => 36,
            ],
            "name" => [
                "type" => "VARCHAR",
                "constraint" => 100,
                "null" => false,
            ],
            "description" => [
                "type" => "TEXT",
                "null" => true,
            ],
            "type" => [
                "type" => "VARCHAR",
                "constraint" => 50,
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
        $this->forge->addUniqueKey("name");

        $this->forge->createTable("user_roles", true);
    }

    public function down()
    {
        $this->forge->dropTable("user_roles", true);
    }
}

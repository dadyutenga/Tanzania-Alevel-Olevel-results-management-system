<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsers extends Migration
{
    public function up()
    {
        $this->forge->addField([
            "id" => [
                "type" => "CHAR",
                "constraint" => 36,
            ],
            "email" => [
                "type" => "VARCHAR",
                "constraint" => 191,
                "null" => false,
            ],
            "username" => [
                "type" => "VARCHAR",
                "constraint" => 100,
                "null" => false,
            ],
            "password" => [
                "type" => "VARCHAR",
                "constraint" => 255,
                "null" => false,
            ],
            "role" => [
                "type" => "VARCHAR",
                "constraint" => 50,
                "default" => "user",
            ],
            "active" => [
                "type" => "TINYINT",
                "constraint" => 1,
                "default" => 1,
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
        $this->forge->addUniqueKey("email");
        $this->forge->addUniqueKey("username");

        $this->forge->createTable("users", true);
    }

    public function down()
    {
        $this->forge->dropTable("users", true);
    }
}

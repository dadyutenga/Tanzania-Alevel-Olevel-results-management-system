<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserUserRoles extends Migration
{
    public function up()
    {
        $this->forge->addField([
            "id" => [
                "type" => "CHAR",
                "constraint" => 36,
            ],
            "user_id" => [
                "type" => "CHAR",
                "constraint" => 36,
                "null" => false,
            ],
            "user_role_id" => [
                "type" => "CHAR",
                "constraint" => 36,
                "null" => false,
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
        $this->forge->addForeignKey(
            "user_id",
            "users",
            "id",
            "CASCADE",
            "CASCADE",
        );
        $this->forge->addForeignKey(
            "user_role_id",
            "user_roles",
            "id",
            "CASCADE",
            "CASCADE",
        );

        $this->forge->createTable("user_user_roles", true);
    }

    public function down()
    {
        $this->forge->dropTable("user_user_roles", true);
    }
}

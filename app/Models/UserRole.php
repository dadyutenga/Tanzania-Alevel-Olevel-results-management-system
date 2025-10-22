<?php

namespace App\Models;

use CodeIgniter\Model;

class UserRole extends Model
{
    protected $table = "user_roles";
    protected $primaryKey = "id";
    protected $returnType = "array";
    protected $allowedFields = ["name", "description", "type"];
    protected $useTimestamps = true;
    protected $createdField = "created_at";
    protected $updatedField = "updated_at";
    protected $validationRules = [
        "name" => "required|is_unique[user_roles.name,id,{id}]",
    ];
    protected $skipValidation = false;

    /**
     * Get users associated with this role (many-to-many relationship).
     * Note: CodeIgniter doesn't have built-in many-to-many, so this is a custom method.
     * Assumes a pivot table 'user_user_roles' with user_id and user_role_id.
     */
    public function getUsers()
    {
        return $this->db
            ->table("user_user_roles")
            ->join("users", "users.id = user_user_roles.user_id")
            ->where("user_user_roles.user_role_id", $this->id)
            ->get()
            ->getResultArray();
    }

    /**
     * Attach a user to this role.
     */
    public function attachUser($userId)
    {
        return $this->db->table("user_user_roles")->insert([
            "user_id" => $userId,
            "user_role_id" => $this->id,
        ]);
    }

    /**
     * Detach a user from this role.
     */
    public function detachUser($userId)
    {
        return $this->db
            ->table("user_user_roles")
            ->where("user_id", $userId)
            ->where("user_role_id", $this->id)
            ->delete();
    }
}

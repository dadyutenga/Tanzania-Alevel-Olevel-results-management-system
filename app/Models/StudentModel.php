<?php

namespace App\Models;

class StudentModel extends BaseModel
{
    protected $table = "students";
    protected $protectFields = true;
    protected $allowedFields = [
        "id",
        "firstname",
        "middlename",
        "lastname",
        "image",
        "dob",
        "gender",
        "guardian_phone",
        "is_active",
        "created_by",
        "updated_by",
        "school_id",
        "created_at",
        "updated_at",
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = "datetime";
    protected $createdField = "created_at";
    protected $updatedField = "updated_at";

    // Validation
    protected $validationRules = [
        "firstname" => "required|min_length[2]|max_length[100]|alpha_space",
        "middlename" => "permit_empty|max_length[100]|alpha_space",
        "lastname" => "required|min_length[2]|max_length[100]|alpha_space",
        "image" => "permit_empty|max_size[1024]|is_image[image]",
        "dob" => "required|valid_date",
        "gender" => "required|in_list[male,female,other]",
        "guardian_phone" => "required|min_length[10]|max_length[15]|numeric",
        "is_active" => "required|in_list[yes,no]",
    ];

    protected $validationMessages = [
        "firstname" => [
            "required" => "First name is required",
            "min_length" => "First name must be at least 2 characters long",
        ],
        "lastname" => [
            "required" => "Last name is required",
            "min_length" => "Last name must be at least 2 characters long",
        ],
    ];

    protected $skipValidation = false;
}

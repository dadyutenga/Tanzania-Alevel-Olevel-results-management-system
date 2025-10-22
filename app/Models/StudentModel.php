<?php

namespace App\Models;

class StudentModel extends BaseModel
{
    protected $table            = 'students';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'admission_no',
        'admission_date',
        'firstname',
        'middlename',
        'lastname',
        'image',
        'dob',
        'gender',
        'permanent_address',
        'guardian_name',
        'guardian_relation',
        'guardian_phone',
        'guardian_address',
        'guardian_email',
        'is_active',
        'height',
        'weight',
        'created_by',
        'updated_by',
        'school_id',
        'created_at',
        'updated_at',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'admission_no'      => 'required|max_length[100]|is_unique[students.admission_no,id,{id}]',
        'admission_date'    => 'required|valid_date',
        'firstname'         => 'required|min_length[2]|max_length[100]|alpha_space',
        'middlename'        => 'permit_empty|max_length[100]|alpha_space',
        'lastname'          => 'required|min_length[2]|max_length[100]|alpha_space',
        'image'            => 'permit_empty|max_size[1024]|is_image[image]',
        'dob'              => 'required|valid_date',
        'gender'           => 'required|in_list[male,female,other]',
        'permanent_address'=> 'required|min_length[5]|max_length[255]',
        'guardian_name'    => 'required|min_length[3]|max_length[100]|alpha_space',
        'guardian_relation'=> 'required|max_length[50]',
        'guardian_phone'   => 'required|min_length[10]|max_length[15]|numeric',
        'guardian_address' => 'required|min_length[5]|max_length[255]',
        'guardian_email'   => 'permit_empty|valid_email|max_length[100]',
        'is_active'        => 'required|in_list[yes,no]',
        'height'           => 'permit_empty|numeric|greater_than[0]',
        'weight'           => 'permit_empty|numeric|greater_than[0]'
    ];

    protected $validationMessages = [
        'admission_no' => [
            'required' => 'Admission number is required',
            'is_unique' => 'This admission number already exists'
        ],
        'firstname' => [
            'required' => 'First name is required',
            'min_length' => 'First name must be at least 2 characters long'
        ],
        'lastname' => [
            'required' => 'Last name is required',
            'min_length' => 'Last name must be at least 2 characters long'
        ]
    ];

    protected $skipValidation = false;
}
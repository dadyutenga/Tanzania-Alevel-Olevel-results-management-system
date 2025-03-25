<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentModel extends Model
{
    protected $DBGroup          = 'second_db';
    protected $table            = 'students';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'parent_id', 'admission_no', 'roll_no', 'admission_date',
        'firstname', 'middlename', 'lastname', 'rte', 'image',
        'mobileno', 'email', 'state', 'city', 'pincode',
        'religion', 'cast', 'dob', 'gender', 'current_address',
        'permanent_address', 'category_id', 'school_house_id',
        'blood_group', 'hostel_room_id', 'adhar_no', 'samagra_id',
        'bank_account_no', 'bank_name', 'ifsc_code', 'guardian_is',
        'father_name', 'father_phone', 'father_occupation',
        'mother_name', 'mother_phone', 'mother_occupation',
        'guardian_name', 'guardian_relation', 'guardian_phone',
        'guardian_occupation', 'guardian_address', 'guardian_email',
        'father_pic', 'mother_pic', 'guardian_pic', 'is_active',
        'previous_school', 'height', 'weight', 'measurement_date',
        'dis_reason', 'note', 'dis_note', 'app_key', 'parent_app_key',
        'disable_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'parent_id'    => 'required|integer',
        'admission_no' => 'permit_empty|max_length[100]',
        'firstname'    => 'permit_empty|max_length[100]',
        'lastname'     => 'permit_empty|max_length[100]',
        'email'        => 'permit_empty|valid_email|max_length[100]',
        'mobileno'     => 'permit_empty|max_length[100]',
        'is_active'    => 'permit_empty|in_list[yes,no]',
    ];
} 
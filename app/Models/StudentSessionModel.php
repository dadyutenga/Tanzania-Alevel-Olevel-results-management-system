<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentSessionModel extends Model
{
    protected $DBGroup          = 'second_db';
    protected $table            = 'student_session';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'session_id', 'student_id', 'class_id', 'section_id',
        'hostel_room_id', 'vehroute_id', 'route_pickup_point_id',
        'transport_fees', 'fees_discount', 'is_leave', 'is_active',
        'is_alumni', 'default_login'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'session_id'  => 'permit_empty|integer',
        'student_id'  => 'permit_empty|integer',
        'class_id'    => 'permit_empty|integer',
        'section_id'  => 'permit_empty|integer',
        'is_active'   => 'permit_empty|in_list[yes,no]',
    ];
}

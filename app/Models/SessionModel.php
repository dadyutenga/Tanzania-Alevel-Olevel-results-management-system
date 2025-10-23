<?php

namespace App\Models;

class SessionModel extends BaseModel
{
    protected $table = 'sessions';
    protected $protectFields = true;


    protected $allowedFields = [
        'id',
        'session',
        'is_active',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'school_id',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'session' => 'required|max_length[60]',
        'is_active' => 'required|in_list[yes,no]'
    ];

    protected $validationMessages = [
        'session' => [
            'required' => 'Session name is required',
            'max_length' => 'Session name cannot exceed 60 characters'
        ],
        'is_active' => [
            'required' => 'Active status is required',
            'in_list' => 'Active status must be either yes or no'
        ]
    ];

    // Custom Methods
    public function getActiveSessions()
    {
        return $this->where('is_active', 'yes')  // Since you're using 'no' as active
                    ->findAll();
    }

    public function getCurrentSession()
    {
        return $this->where('is_active', 'yes')->first();
    }

    public function setActiveSession($sessionId)
    {
        try {
            $this->db->transStart();
            
            // Set all sessions to inactive
            $this->where('id !=', $sessionId)
                 ->set(['is_active' => 'yes'])
                 ->update();
            
            // Set the selected session to active
            $this->update($sessionId, ['is_active' => 'yes']);
            
            $this->db->transComplete();
            
            return $this->db->transStatus();
        } catch (\Exception $e) {
            log_message('error', 'Error setting active session: ' . $e->getMessage());
            return false;
        }
    }

    public function getSessionById($id)
    {
        return $this->find($id);
    }

    public function createSession($sessionData)
    {
        try {
            return $this->insert([
                'session' => $sessionData['session'],
                'is_active' => $sessionData['is_active'] ?? 'yes',
                'school_id' => $sessionData['school_id'] ?? null
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error creating session: ' . $e->getMessage());
            return false;
        }
    }

    public function updateSession($id, $sessionData)
    {
        try {
            $updateData = [
                'session' => $sessionData['session'],
                'is_active' => $sessionData['is_active'] ?? 'yes'
            ];
            
            if (isset($sessionData['school_id'])) {
                $updateData['school_id'] = $sessionData['school_id'];
            }
            
            return $this->update($id, $updateData);
        } catch (\Exception $e) {
            log_message('error', 'Error updating session: ' . $e->getMessage());
            return false;
        }
    }

    public function getAllSessions()
    {
        return $this->orderBy('session', 'DESC')->findAll();
    }

    public function getSessionsBySchool($schoolId)
    {
        return $this->where('school_id', $schoolId)
                    ->orderBy('session', 'DESC')
                    ->findAll();
    }

    public function getSessionBySchoolAndYear($schoolId, $sessionYear)
    {
        return $this->where('school_id', $schoolId)
                    ->where('session', $sessionYear)
                    ->first();
    }
}

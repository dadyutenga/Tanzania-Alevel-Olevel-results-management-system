<?php

namespace App\Models;

use CodeIgniter\Model;

class SessionModel extends Model
{
    protected $table = 'sessions';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    

    protected $allowedFields = [
        'session',
        'is_active',
        'created_at',
        'updated_at'
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

    // Callbacks
    protected function beforeInsert(array $data)
    {
        $data['data']['created_at'] = date('Y-m-d H:i:s');
        $data['data']['updated_at'] = date('Y-m-d H:i:s');
        return $data;
    }

    protected function beforeUpdate(array $data)
    {
        $data['data']['updated_at'] = date('Y-m-d H:i:s');
        return $data;
    }

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
                'is_active' => $sessionData['is_active'] ?? 'yes'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error creating session: ' . $e->getMessage());
            return false;
        }
    }

    public function updateSession($id, $sessionData)
    {
        try {
            return $this->update($id, [
                'session' => $sessionData['session'],
                'is_active' => $sessionData['is_active'] ?? 'yes'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error updating session: ' . $e->getMessage());
            return false;
        }
    }

    public function getAllSessions()
    {
        return $this->orderBy('session', 'DESC')->findAll();
    }
}

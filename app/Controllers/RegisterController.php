<?php

namespace App\Controllers;

use CodeIgniter\Shield\Controllers\RegisterController as ShieldRegister;

class RegisterController extends ShieldRegister
{
    public function registerAction()
    {
        // Call the parent registerAction to handle the registration
        $result = parent::registerAction();

        // After successful registration, redirect to login
        if (auth()->check()) {
            // Log the user out immediately after registration
            auth()->logout();
            
            // Redirect to login with a success message
            return redirect()->to('/login')
                ->with('message', 'Registration successful! Please login with your credentials.');
        }

        return $result;
    }
} 
<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    protected UserModel $users;

    public function __construct()
    {
        helper(["url", "form"]);
        $this->users = new UserModel();
    }

    public function loginForm()
    {
        if ($this->session->get("isLoggedIn")) {
            return redirect()->to("/dashboard");
        }

        return view("Auth/Login");
    }

    public function login()
    {
        $rules = [
            "email" => "required|valid_email",
            "password" => "required",
        ];

        if (!$this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with("errors", $this->validator->getErrors());
        }

        $email = $this->request->getPost("email");
        $password = $this->request->getPost("password");

        $user = $this->users->where("email", $email)->first();

        if (!$user || !password_verify($password, $user["password"])) {
            return redirect()
                ->back()
                ->withInput()
                ->with("error", "Invalid login credentials.");
        }

        if (empty($user["active"])) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    "error",
                    "Your account is inactive. Please contact the administrator.",
                );
        }

        $this->session->set([
            "user_id" => $user["id"],
            "user_uuid" => $user["id"],
            "email" => $user["email"],
            "username" => $user["username"],
            "role" => $user["role"] ?? "user",
            "isLoggedIn" => true,
        ]);

        return redirect()->to("/dashboard");
    }

    public function logout()
    {
        $this->session->destroy();

        return redirect()
            ->to("/login")
            ->with("message", "You have been logged out.");
    }

    public function registerForm()
    {
        if ($this->session->get("isLoggedIn")) {
            return redirect()->to("/dashboard");
        }

        return view("Auth/Register");
    }

    public function register()
    {
        $rules = [
            "email" => "required|valid_email|is_unique[users.email]",
            "username" => "required|min_length[3]|is_unique[users.username]",
            "password" => "required|min_length[8]",
            "password_confirm" => "required|matches[password]",
        ];

        if (!$this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with("errors", $this->validator->getErrors());
        }

        $role = $this->users->countAllResults() == 0 ? "admin" : "user";

        $this->users->insert([
            "email" => $this->request->getPost("email"),
            "username" => $this->request->getPost("username"),
            "password" => $this->request->getPost("password"),
            "role" => $role,
            "active" => 1,
        ]);

        return redirect()
            ->to("/login")
            ->with("message", "Registration successful. You can now log in.");
    }
}

<?php

namespace benignware\micro\app\controllers;

class AuthController
{
    public function register($req, $res)
    {
        if ($req->method === 'POST') {
            $email = $req->params['email'] ?? '';
            $password = $req->params['password'] ?? '';

            // Validate input
            if (empty($email) || empty($password)) {
                return $res->render('users/register', ['error' => 'Email and password are required.']);
            }

            // Check if user already exists
            $existingUser = $req->db->query("SELECT * FROM users WHERE email = ?", [$email], true);
            if ($existingUser) {
                return $res->render('users/register', ['error' => 'Email is already taken.']);
            }

            // Insert new user into the database
            $req->db->execute("INSERT INTO users (email, password) VALUES (?, ?)", [
                $email,
                password_hash($password, PASSWORD_DEFAULT) // Hash the password
            ]);

            // Redirect to the login page
            $res->redirect('/login');
        } else {
            // Render the registration form
            $res->render('users/register');
        }
    }

    public function login($req, $res)
    {
        if ($req->method === 'POST') {
            $email = $req->params['email'] ?? '';
            $password = $req->params['password'] ?? '';

            // Fetch user by email
            $user = $req->db->query("SELECT * FROM users WHERE email = ?", [$email], true);

            if ($user && password_verify($password, $user->password)) {
                // Set user in session
                $_SESSION['user'] = $user;
                $redirectPath = $req->params['redirect'] ?? '/'; // Get redirect path if exists
                $res->redirect($redirectPath); // Redirect to the original page or homepage
                return;
            }

            // If authentication fails, render login view with an error
            $res->render('users/login', ['error' => 'Invalid email or password.']);
        } else {
            $res->render('users/login');
        }
    }

    public function logout($req, $res)
    {
        // Clear the session user
        unset($_SESSION['user']);
        
        // Optionally destroy the session
        session_destroy(); // This is optional, you can just unset the user
        
        // Redirect to the login page or home page
        $res->redirect('/login');
    }
}

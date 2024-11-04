<?php

namespace benignware\micro\app\controllers;

use stdClass;

class UserController
{
    public function index($req, $res)
    {
        $users = $req->db->query("SELECT * FROM users");
        $res->render('users/index', ['users' => $users]);
    }

    public function show($req, $res)
    {
        $id = $req->params['id'];
        $user = $req->db->query("SELECT * FROM users WHERE id = ?", [$id], true);

        if (!$user) {
            return $res->status(404)->render('404');
        }

        $res->render('users/show', ['user' => $user]);
    }

    public function register($req, $res)
    {
        if ($req->method === 'POST') {
            $email = $req->params['email'] ?? '';
            $password = $req->params['password'] ?? '';

            $req->db->execute("INSERT INTO users (email, password) VALUES (?, ?)", [
                $email,
                password_hash($password, PASSWORD_DEFAULT)
            ]);

            $res->redirect('/login');
        } else {
            $res->render('users/register');
        }
    }

    public function login($req, $res)
    {
        if ($req->method === 'POST') {
            $email = $req->params['email'] ?? '';
            $password = $req->params['password'] ?? '';

            $user = $req->db->query("SELECT * FROM users WHERE email = ?", [$email], true);

            if ($user && password_verify($password, $user->password)) {
                $_SESSION['user'] = $user;
                $res->redirect('/');
                return;
            }

            $res->render('users/login', ['error' => 'Invalid email or password.']);
        } else {
            $res->render('users/login');
        }
    }

    public function edit($req, $res)
    {
        $id = $req->params['id'];
        $user = $req->db->query("SELECT * FROM users WHERE id = ?", [$id], true);

        if (!$user) {
            return $res->status(404)->render('404');
        }

        $res->render('users/edit', ['user' => $user]);
    }

    public function update($req, $res)
    {
        $id = $req->params['id'];
        $email = $req->params['email'] ?? '';
        $password = $req->params['password'] ?? '';

        $query = "UPDATE users SET email = ?";
        $params = [$email, $id];

        if (!empty($password)) {
            $query .= ", password = ?";
            $params = [$email, password_hash($password, PASSWORD_DEFAULT), $id];
        }

        $req->db->execute($query . " WHERE id = ?", $params);
        $res->redirect('/users/' . $id);
    }

    public function destroy($req, $res)
    {
        $id = $req->params['id'];
        $req->db->execute("DELETE FROM users WHERE id = ?", [$id]);
        $res->redirect('/users');
    }
}

<?php

namespace benignware\micro\app\controllers;

use stdClass;

class PostController
{
    public function index($req, $res)
    {
        $currentPage = (int) ($req->params['page'] ?? 1);
        $totalPosts = (int) $req->db->query("SELECT COUNT(*) as count FROM posts")[0]->count;

        // Use items per page from the middleware
        $itemsPerPage = $req->params['itemsPerPage'] ?? 10; // Default to 10 if not set
        $offset = ($currentPage - 1) * $itemsPerPage; // Correct calculation for offset
        $posts = $req->db->query("SELECT * FROM posts LIMIT $offset, $itemsPerPage");

        // Prepare pagination information
        $res->render('posts/index', [
            'posts' => $posts,
            'totalPosts' => $totalPosts,
            'currentPage' => $currentPage,
            'itemsPerPage' => $itemsPerPage // Pass itemsPerPage to the view if needed
        ]);
    }


    public function show($req, $res)
    {
        $id = $req->params['id'];
        $post = $req->db->query("SELECT * FROM posts WHERE id = ?", [$id], true);

        if (!$post) {
            return $res->status(404)->render('404');
        }

        $res->render('posts/show', ['post' => $post]);
    }

    public function create($req, $res)
    {
        $res->render('posts/create');
    }

    public function store($req, $res)
    {
        $data = $req->params;
        $req->db->execute("INSERT INTO posts (title, content) VALUES (?, ?)", [
            $data['title'],
            $data['content']
        ]);

        $res->redirect('/posts');
    }

    public function edit($req, $res)
    {
        $id = $req->params['id'];
        $post = $req->db->query("SELECT * FROM posts WHERE id = ?", [$id], true);

        if (!$post) {
            return $res->status(404)->render('404');
        }

        $res->render('posts/edit', ['post' => $post]);
    }

    public function update($req, $res)
    {
        $id = $req->params['id'];
        $data = $req->params;

        $req->db->execute("UPDATE posts SET title = ?, content = ? WHERE id = ?", [
            $data['title'],
            $data['content'],
            $id
        ]);

        $res->redirect('/posts/' . $id);
    }

    public function destroy($req, $res)
    {
        $id = $req->params['id'];
        $req->db->execute("DELETE FROM posts WHERE id = ?", [$id]);

        $res->redirect('/posts');
    }
}

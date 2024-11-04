<?php

namespace benignware\micro\controllers;

class EntityController
{
    protected const TABLE_NAME = '';

    public function index($req, $res)
    {
        $records = $req->db->query("SELECT * FROM " . static::TABLE_NAME);
        $res->render(static::TABLE_NAME . '/index', ['records' => $records]);
    }

    public function show($req, $res)
    {
        $id = $req->params['id'];
        $record = $req->db->query("SELECT * FROM " . static::TABLE_NAME . " WHERE id = ?", [$id], true);
        $res->render(static::TABLE_NAME . '/show', ['record' => $record]);
    }

    public function create($req, $res)
    {
        if ($req->isPost()) {
            $data = $req->params; // Assume params contain the data to insert
            $columns = implode(',', array_keys($data));
            $placeholders = implode(',', array_fill(0, count($data), '?'));
            $sql = "INSERT INTO " . static::TABLE_NAME . " ($columns) VALUES ($placeholders)";
            $req->db->execute($sql, array_values($data));
            $res->redirect('/' . static::TABLE_NAME);
        } else {
            $res->render(static::TABLE_NAME . '/create');
        }
    }

    public function update($req, $res)
    {
        $id = $req->params['id'];
        if ($req->isPost()) {
            $data = $req->params;
            $set = implode(',', array_map(fn($column) => "$column = ?", array_keys($data)));
            $sql = "UPDATE " . static::TABLE_NAME . " SET $set WHERE id = ?";
            $req->db->execute($sql, array_merge(array_values($data), [$id]));
            $res->redirect('/' . static::TABLE_NAME);
        } else {
            $record = $req->db->query("SELECT * FROM " . static::TABLE_NAME . " WHERE id = ?", [$id], true);
            $res->render(static::TABLE_NAME . '/update', ['record' => $record]);
        }
    }

    public function delete($req, $res)
    {
        $id = $req->params['id'];
        $req->db->execute("DELETE FROM " . static::TABLE_NAME . " WHERE id = ?", [$id]);
        $res->redirect('/' . static::TABLE_NAME);
    }
}

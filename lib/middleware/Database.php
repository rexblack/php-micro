<?php

namespace benignware\micro\middleware;

use PDO;
use stdClass;

class Database
{
    protected $pdo;

    public function __construct($config)
    {
        $dsn = "mysql:host={$config['db_host']};dbname={$config['db_name']};charset=utf8";
        $this->pdo = new PDO($dsn, $config['db_user'], $config['db_password']);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

        // Handle schema configuration
        if (isset($config['schema'])) {
            $this->executeSchema($config['schema']);
        }
    }

    public static function middleware($config)
    {
        // Return middleware function to be registered in Micro
        return function ($req, $res, $next) use ($config) {
            // Attach a Database instance to each request
            $req->db = new self($config);
            $next();
        };
    }

    protected function executeSchema($schema)
    {
        if (is_file($schema)) {
            // If schema is a filename, read the contents
            $schema = file_get_contents($schema);
        }

        // Split the schema into individual queries based on semicolons
        $queries = array_filter(array_map('trim', explode(';', $schema)));

        foreach ($queries as $query) {
            if (!empty($query)) {
                $this->execute($query);
            }
        }
    }

    public function query($sql, $params = [], $single = false, $model = null)
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        // Fetch a single result or all results as stdClass
        if ($single) {
            $result = $stmt->fetch();
            return $model ? new $model((array) $result) : $result;
        } else {
            $results = $stmt->fetchAll();
            // If a model is provided, instantiate it for each result
            if ($model) {
                return array_map(function($data) use ($model) {
                    return new $model((array) $data); // Convert to array for model constructor
                }, $results);
            }
            // Default to returning stdClass objects
            return $results;
        }
    }

    public function execute($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }
}

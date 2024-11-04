<?php

namespace benignware\micro;

class Router {
    private $routes = [];

    public function addRoute($method, $path, $action) {
        if (!$this->isMethodSupported($method)) {
            throw new \BadMethodCallException("Method '$method' not supported.");
        }

        $this->routes[] = new Route($method, $path, $action);
    }

    public function match($request) {
        foreach ($this->routes as $route) {
            if ($route->matches($request)) {
                return $route;
            }
        }

        return null; 
    }

    public function isMethodSupported($method) {
        return in_array(strtoupper($method), ['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS']);
    }
}

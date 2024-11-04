<?php

namespace benignware\micro;

class Route {
    private $method;
    private $path;
    private $actions;

    public function __construct($method, $path, ...$actions) {
        $this->method = strtoupper($method);
        $this->path = $path;
        $this->actions = $actions;
    }

    public function matches($request) {
        // Convert path parameters (e.g., :id) to regex named capture groups
        $pattern = preg_replace('/:([\w]+)/', '(?P<$1>[^/]+)', $this->path);
        $pattern = '/^' . str_replace('/', '\/', $pattern) . '$/i';
    
        // Check if the request path matches the route pattern
        if (preg_match($pattern, $request->path, $matches) && 
            $this->method === strtoupper($request->method)) {
            
            // Filter matches to include only named parameters
            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

            $request->params = array_merge($request->params, $params);
            return true;
        }
    
        return false;
    }
    

    public function getActions() {
        return array_map([$this, 'resolveAction'], $this->actions); // Resolve all actions
    }

    private function resolveAction($action) {
        if (is_callable($action)) {
            return $action;
        }

        // If action is in array format, expect [ControllerClass::class, 'method']
        if (is_array($action) && count($action) === 2) {
            [$controllerClass, $method] = $action;

            // Verify that the controller class and method are valid
            if (class_exists($controllerClass) && method_exists($controllerClass, $method)) {
                return [new $controllerClass(), $method];
            }
        }

        throw new \Exception("Action not callable: $action");
    }

    public function getMethod() {
        return $this->method;
    }

    public function getPath() {
        return $this->path;
    }
}

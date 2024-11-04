<?php

namespace benignware\micro;

class Micro {
    private static $instance = null;
    private $router;
    private $middleware = [];
    private $config = [];
    private $notFoundHandler;

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        $this->router = new Router();
    }

    public function use($middleware) {
        $this->middleware[] = $middleware;
    }

    public function set($key, $value) {
        $this->config[$key] = $value;
    }

    public function __call($method, $args) {
        // Handle configuration values
        if ($method === 'get' && count($args) === 1) {
            return $this->config[$args[0]] ?? null;
        }

        // Handle routes
        if ($this->router->isMethodSupported(strtoupper($method)) && count($args) > 0) {
            $path = $args[0];
            $actions = array_slice($args, 1);
            $this->router->addRoute(strtoupper($method), $path, ...$actions);
            return; // Exit after adding the route
        }

        throw new \BadMethodCallException("Method '$method' not supported.");
    }

    public function handleRequest($request) {
        $response = new Response($this, [
            'request' => $request
        ]);

        // Execute middleware
        foreach ($this->middleware as $middleware) {
            $middleware($request, $response, function() {}); // Pass an empty next function
        }
        
        // Match the request with a route
        $match = $this->router->match($request);

        // Handle matched route
        if ($match) {
            $actions = $match->getActions();

            foreach ($actions as $action) {
                $action($request, $response); // Execute the action
            }
        } else {
            // If no route matched, call the 404 handler or default response
            if ($this->notFoundHandler) {
                call_user_func($this->notFoundHandler, $request, $response);
            } else {
                $response->status(404);
                $response->render('404'); // Render the default 404 view
            }
        }

        return $response;
    }

    public function bootstrap() {
        $request = new Request($this); // Pass Micro instance to Request
        $response = $this->handleRequest($request);

        // Send the response to the client
        $response->send();
    }

    public function setNotFoundHandler($handler) {
        $this->notFoundHandler = $handler;
    }
}

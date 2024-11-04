<?php

namespace benignware\micro\middleware;

class Authentication
{
    protected $redirectPath;
    protected $allowedPaths;
    protected $restrictedPaths;
    protected $sessionKey;
    protected $userProperty;
    protected $redirectParam;

    public function __construct($options = [])
    {
        $this->redirectPath = $options['redirectPath'] ?? '/login';
        $this->allowedPaths = $options['allowedPaths'] ?? [];
        $this->restrictedPaths = $options['restrictedPaths'] ?? [];
        $this->sessionKey = $options['sessionKey'] ?? 'user';
        $this->userProperty = $options['userProperty'] ?? 'user';
        $this->redirectParam = $options['redirectParam'] ?? 'redirect';
    }

    public static function middleware($options = [])
    {
        // Create an instance of the Authentication middleware
        $instance = new self($options);

        // Return the middleware function to be registered in Micro
        return function ($req, $res, $next) use ($instance) {
            // Start the session if it's not already started
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Augment the response object with currentUser property
            $instance->augmentResponse($res);

            // Check if the request path matches any allowed paths
            if ($instance->matchesGlob($req->path, $instance->allowedPaths)) {
                return $next();
            }

            // Check if the request path matches any restricted paths
            if ($instance->matchesGlob($req->path, $instance->restrictedPaths)) {
                // Check if the user is authenticated
                if (isset($_SESSION[$instance->sessionKey])) {
                    return $next();
                }

                // Redirect to the login page if not authenticated
                $redirectUrl = $instance->redirectPath . '?' . $instance->redirectParam . '=' . urlencode($req->path);
                return $res->redirect($redirectUrl);
            }

            // If the path is neither allowed nor restricted, proceed as normal
            return $next();
        };
    }

    protected function augmentResponse($res)
    {
        // Directly assign the current user to the response view if authenticated
        if (isset($_SESSION[$this->sessionKey])) {
            $res->view->{$this->userProperty} = (object) $_SESSION[$this->sessionKey];
        } else {
            $res->view->{$this->userProperty} = null; // Set to null if not authenticated
        }

        // Register the can helper
        $res->view->use('can', function ($action = null) use ($res) {
            $user = $res->view->{$this->userProperty};

            if ($action === null) {
                // Resolve to true if a user is logged in
                return $user !== null;
            }

            if (!$user) {
                return false;
            }
            // Implement your specific logic for checking permissions/actions
            return true; // Replace with actual permission logic if needed
        });

        $res->view->use('currentUser', function () use ($res) {
            return $res->view->{$this->userProperty};
        });
    }

    protected function matchesGlob($path, $patterns)
    {
        foreach ($patterns as $pattern) {
            // Convert glob pattern to regex
            $regex = preg_quote($pattern, '#');
            $regex = str_replace(['\*', '\?'], ['.*', '.'], $regex);
            if (preg_match('#^' . $regex . '$#', $path)) {
                return true;
            }
        }
        return false;
    }
}

<?php

namespace benignware\micro\middleware;

class Authorization
{
    public static function middleware(callable $checkPermission)
    {
        return function ($request, $response, $next) use ($checkPermission) {
            // Use the callback to determine if the user has access to the requested action
            if (!$checkPermission($request->user, $request->params, $request->method)) {
                $response->status(403);
                $response->render('403'); // Render 403 view or return a JSON response
                return;
            }

            // Call the next middleware or request handler
            $next();
        };
    }
}

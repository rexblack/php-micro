<?php

namespace benignware\micro\middleware;

class Json
{
    public static function middleware()
    {
        return function ($req, $res, $next) {
            // Proceed to the next middleware/controller
            $next();

            // Check if the URL ends with .json
            if (preg_match('/\.json$/', $req->url)) {
                // Remove the .json extension from the URL
                $req->url = preg_replace('/\.json$/', '', $req->url);
                
                // Get the response data that would normally be rendered
                $data = $res->getData(); // You might need to implement this method in Response

                // Return JSON response
                return $res->json($data);
            }
        };
    }
}

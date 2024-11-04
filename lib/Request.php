<?php

namespace benignware\micro;

class Request {
    public $path;
    public $method;
    public $query;
    public $params;
    public $headers;
    protected $app;

    public function __construct($app) {
        $this->app = $app;
        $this->path = rtrim(strtok($_SERVER['REQUEST_URI'], '?'), '/') ?: '/';
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->query = $_SERVER['QUERY_STRING'];
        $this->params = array_merge($_GET, $_POST);
        $this->headers = getallheaders();
        $this->baseUrl = sprintf(
            "%s://%s%s",
            isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
            $_SERVER['HTTP_HOST'],
            rtrim(pathinfo($_SERVER['PHP_SELF'])['dirname'], '/')
        );
    }
}

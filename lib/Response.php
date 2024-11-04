<?php

namespace benignware\micro;

class Response {
    public $status = 200;
    public $body = '';
    public $view;

    public function __construct($app, $options = []) {
        $this->view = new View([
            'views' => $app->get('views'),
            'layout' => $app->get('layout'),
            'baseUrl' => $options['request']->baseUrl
        ]);
    }

    public function render($view, $data = [], $options = []) {
      $this->body = $this->view->render($view, $data, $options);
    }

    public function redirect($url, $status = 302) {
        $this->status($status);  // Set the status to the redirect status (default 302)
        header("Location: $url");
        exit(); // End the script to ensure redirection
    }

    // Method to set the HTTP status code
    public function status($status) {
        $this->status = $status;
        return $this; // Return $this for method chaining
    }

    public function send() {
        http_response_code($this->status);
        echo $this->body;
    }

    public function isSent() {
        return headers_sent();
    }

    public function json($data) {
        $this->status(200); // Set the status to 200 OK
        header('Content-Type: application/json');
        $this->body = json_encode($data);
    }
}

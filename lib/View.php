<?php

namespace benignware\micro;

class View {
    private $viewsDir;
    private $layout;
    private $baseUrl;
    private $helpers = []; // Array to store view helpers

    public function __construct($options = []) {
        $this->options = array_merge([
            'views' => 'views',
            'layout' => null,
            'baseUrl' => ''
        ], $options);
    }

    // Method to add a helper
    public function use($name, $callable) {
        if (!is_callable($callable)) {
            throw new \InvalidArgumentException("The provided helper is not callable.");
        }
        $this->helpers[$name] = $callable;
    }

    // Magic method to call helpers
    public function __call($method, $args) {
        if (array_key_exists($method, $this->helpers)) {
            return call_user_func($this->helpers[$method], ...$args);
        }
        throw new \BadMethodCallException("Method '$method' not found.");
    }

    public function render($view, $data = [], $options = []) {
        $options = array_merge($this->options, $options);
        // Check if the view file exists
        $template = $options['views'] . '/' . $view . '.php';
        
        if (!file_exists($template)) {
            throw new \RuntimeException("Template file '$template' not found.");
        }

        // Extract the data for use in the view
        foreach($data as $key => $value) {
            $$key = $data[$key];
        }

        // Capture the output of the view
        ob_start();
        include $template;
        $content = ob_get_clean();

        // Check if a layout is specified
        if ($options['layout']) {
            $content = $this->render($options['layout'], array_merge(
              $data, [
                'title' => $data['title'] ?? '',
                'content' => $content
              ]
            ), array_merge(
              $options, [
                'layout' => null
              ]
            ));
        }

        return $content;
    }

    public function url($path, $params = []) {
        // Build the URL
        $url = $this->options['baseUrl'] . '/' . ltrim($path, '/');

        // Append query parameters if any
        if (!empty($params)) {
            $query = http_build_query($params);
            $url .= '?' . $query;
        }

        return $url;
    }
}

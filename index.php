<?php

error_reporting(E_ALL);


trait DB {
  public function __get($property) {
    return 'A';
  }
}

return (new class {
  public $config = [];
  public $db = null;
  public $params = [];

  public $base_dir = __DIR__;
  public $base_url = null;

  public function __construct() {
    $this->base_url = sprintf(
      "%s://%s%s",
      isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
      $_SERVER['HTTP_HOST'],
      rtrim(pathinfo($_SERVER['PHP_SELF'])['dirname'], '/')
    );
  }

  private function render($template, $data = []) {
    extract($data);
    ob_start();
    $result = require($template);
    $content = ob_get_contents();
    ob_end_clean();
    return $content ?: $result;
  }

  private function connect($options = []) {
    extract($options);
    return new PDO(sprintf('mysql:host=%s;dbname=%s', $host ?: 'localhost', $name), $user, $password);
  }

  private function match($routes = []) {
    $request_uri = rtrim(strtok($_SERVER['REQUEST_URI'], '?'), '/');

    foreach ($routes as $url => $route) {
      $url = rtrim($url, '/');
      $names = [];
      $values = [];

      $pattern = '~\:([a-zA-Z0-9_-]+)~';
      preg_match_all($pattern, $url, $names, PREG_PATTERN_ORDER);
      $match = preg_match(
        '~^' . preg_replace($pattern, '([a-zA-Z0-9-_]+)', $url) . '$~',
        $request_uri,
        $values
      );

      if ($match) {
        array_shift($values);
        $params = array_combine($names[1], $values);
        $params = array_merge($_GET, $params);

        return [ $route, $params ];
      }
    }

    return null;
  }

  public function bootstrap() {
    $this->config = require_once 'config.php';
    $this->db = $this->connect($this->config['db']);
    $match = $this->match($this->config['routes']);

    if ($match) {
      list($route, $params) = $match;
      $this->params = $params;

      list($controller_name, $action_name) = $route;

      echo $controller_name;

      if ($controller_name) {
        // print_r(get_object_vars($this));
        // $result = $this->render($controller_name, get_object_vars($this));
        $controller_class = require($controller_name);

        $controller = new $controller_class();

        $reflectionClass = new ReflectionClass($controller_class);

        $reflectionProperty = $reflectionClass->getProperty('db');

        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($controller, $this->db);

        //$classname = get_class($controller_class);

        $controller->{$action_name}();

        exit;

        echo "<br/>";
        echo "GET NAME";
        echo $classname;
        echo "<br/>";
        echo "EXIT";
        exit;

        $master = $this;

        $controller = new $controller_class();

        if (is_object($controller)) {
          echo "IS OBJECT: " . $action_name;
          $controller->{$action_name}();
        } else {
          echo $output;
        }

        exit;
      }
    }
  }
})->bootstrap();
?>

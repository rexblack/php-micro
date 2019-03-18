<?php

error_reporting(E_ALL);

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
      echo $this->render($route, get_object_vars($this));
    } else {
      echo "FILE NOT FOUND";
    }
  }
})->bootstrap();
?>

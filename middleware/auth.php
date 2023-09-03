<?php

namespace benignware\micro\middleware {
  function auth($config = []) {
    $config = array_merge([
      'session_key' => 'user',
      'restricted' => [
        '/**/edit',
        '/**/new',
      ],
      'login_url' => '/login',
      'redirect_param' => 'redirect'
    ], $config);

    return function($request) use ($config) {
      $this->current_user = isset($_SESSION[$config['session_key']])
        ? $_SESSION[$config['session_key']]
        : null;

      if (!$this->current_user) {
        $matches = array_values(
          array_filter(
            $config['restricted'], function($path) use ($request) {
          return fnmatch($path, $request->path);
          })
        );
        $match = count($matches) > 0 ? $matches[0] : null;
    
        if ($match) {
          $redirect_param = $config['redirect_param'];
          
          $redirect_url = $this->url($config['login_url'], [
            $redirect_param => $request->path
          ]);

          $this->redirect($redirect_url);
        }
      }
    };
  }
}

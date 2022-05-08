<?php

namespace benignware\micro\middleware {
  function mysqli($config = []) {
    $config = array_merge([
      'db_host' => 'localhost',
      'db_user' => 'root',
      'db_password' => '',
      'db_name' => '',
      'schema' => ''
    ], $config);

    return function () use ($config) {
      $this->db = mysqli_connect(
        $config['db_host'],
        $config['db_user'],
        $config['db_password'],
        $config['db_name']
      );

      $sql = file_exists($config['schema'])
        ? file_get_contents($config['schema'])
        : $config['schema'];
  
      # Execute multi query
      if ($this->db->multi_query($sql)) {
        do {
          if ($result = $this->db->store_result()) {
            $result->free();
          }
        } while ($this->db->next_result());
      }
    };
  }
}

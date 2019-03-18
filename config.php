<?php return [
  'db' => [
    'host' => getenv('DB_HOST'),
    'name' => getenv('DB_NAME'),
    'user' => getenv('DB_USER'),
    'password' => getenv('DB_PASSWORD')
  ],
  'routes' => [
    '/' => 'views/index/index.php',
    '/artists' => 'views/artists/index.php',
    '/artists/:id' => 'views/artists/show.php'
  ]
];

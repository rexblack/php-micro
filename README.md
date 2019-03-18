# php-micro

Minimalistic MVC-Framework


## Getting started

### Configuration

Copy `index.php` into your project directory and place a file named `config.php` next to it.

Specify your database connection and routes as follows:

```php
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
```

If your host doesn't allow for adding environment variables, you could of course hardcode your db credentials. Note that you should not maintain production keys in version control.

### Example page

Query database and render results to the output stream

```php
<?php
  $stmt = $db->query('SELECT * FROM artists');
  $rows = $stmt->fetchAll();
?>
<h1>Artists</h1>
<?php foreach($rows as $index => $row): ?>
  <h5><a href="<?= $base_url ?>/artists/<?= $row['id']; ?>"><?= $row['name']; ?></a></h5>
<?php endforeach; ?>
```

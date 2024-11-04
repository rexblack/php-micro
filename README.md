# php-micro

Minimalist web framework inspired by express.js

PHP Micro is suited in situations where you don't need a full-blown web framework with a huge dependency tree but rather want something tiny that just works and is manageable, e.g. implementing small web apps and REST APIs, prototyping or educational purposes.

Its middleware interface lets you extend its capabilities. Bundled with micro is a basic mysqli and authentication layer to get you started right away.

## Install

#### Composer

```json
{
  "name": "benignware/micro-app",
  "version": "1.0.0",
  "description": "My Micro App",
  "repositories": [{
    "type": "vcs",
    "url": "https://github.com/benignware/php-micro"
  }],
  "require": {
    "php": ">=7.4",
    "benignware/micro": "^0.0.2"
  }
}
```

### Basic example

Create an `index.php` with the following content:

```php
require_once __DIR__ . '/vendor/autoload.php';

use function benignware\micro\app;

$app = app();

$app->get('/', './views/index.php');
$app->bootstrap();

```

Create a file `views/index.php` which will serve as your homepage view:

```html
<h1>Hello World</h1>
```

### Actions

A typical action consists of the following:

* a request method represented by an instance function, e.g. `$app->get()` or `$app->post`
* a route that may contain parameters as placeholders, e.g. `/users/:id`
* a callable returning a response. This could either be raw content, a path to a view file, an error code or an actual response object containing additional information such as http headers etc. Usually you're going to render a view by calling `render` method and return its result.

Example:

```php

$app->get('posts/:id', function($request) {
  $id = $request->params['id'];
  $post = get_post_by_id($id); // Logic to retrieve a post

  if ($post) {
    return $this->render('./views/posts/show.php', [
      'post' => $post
    ]);
  }
});
```

### Views

A view is just a plain php template file that is referenced from a call to `render` method. Any data provided to render will be present as variables in the referenced php file as well as the app instance itself via `$this` object.

Example:

```php
<h1><?= $post->title ?></h1>

<a href="<?= $this->url('/') ?>">Home</a>
```

### Layout

You may specify a layout view that wraps your actual content...

Create a file `views/layouts/app.php` with content:

```html
<html>
  <head>
    <title>My Micro App</title>
  </head>
  <body>
    <h1>Welcome to Micro</h1>
  </body>
<header>
<main>
  <?= $content ?>
</main>
<footer>
  © Benignware 2023
</footer>
```

Reference your layout view globally as a config option:

```php
$app->set('layout', './views/layouts/app');
```

Or, if you want it to be particular to a certain action, provide it as an argument to the `render`-method.

```php
$app->get('posts/:id', function($request) {
  $id = $request->params['id'];
  $post = get_post_by_id($id); // Logic to retrieve a post

  if ($post) {
    return $this->render('./views/posts/show.php', [
      'post' => $post,
      'layout' => './views/layouts/app'
    ]);
  }
});
```

## Development

### Dev server

Install [Docker](https://docs.docker.com/get-docker/) if you haven't already.

Run example app

```shell
docker compose up -d
```

Application should now be running at http://localhost:3000


## Development

Run the tests

```shell
./vendor/bin/phpunit tests/
```
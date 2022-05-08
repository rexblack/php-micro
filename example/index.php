<?php

require_once __DIR__ . '/vendor/autoload.php';

use function benignware\micro\app;
use function benignware\micro\middleware\mysqli;
use function benignware\micro\middleware\auth;

session_start();

$app = app();

$app->use(mysqli([
  'db_host' => 'db',
  'db_user' => 'user',
  'db_password' => 'secret',
  'db_name' => 'database',
  'schema' => 'schema.sql'
]));

$app->use(auth());

$app->set('layout', './views/layouts/app.php');

$app->get('/', './views/index.php');

$app->get('/about', './views/about.php');

$app->get('/login', './views/account/sign-in.php');

$app->get('/logout', function($params) {
  unset($_SESSION['user']);

  return $this->redirect('/');
});

$app->post('/login', function($request) {
  [
    'email' => $email,
    'password' => $password,
    'redirect' => $redirect
  ] = $request->params;

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo 'Please enter a valid email';
    $error = true;
    exit;
  }

  $result = $this->db->query("SELECT * FROM users WHERE email = '$email'");
  $user = $result->fetch_assoc();

  if ($user) {
    $hashed_password = $user['password'];

    if (password_verify($password, $hashed_password)) {
      $_SESSION['user'] = $user;
      return $this->redirect($redirect ?: '/');
    }
  }

  $this->redirect('/login');
});

$app->get('/posts', function() {
  return $this->render('./views/posts/index.php', [
    'posts' => $this->db->query("SELECT * FROM posts")
  ]);
});

$app->get('/posts/new', './views/posts/new.php');

$app->get('/posts/:id/edit', function($request) {
  $stmt = $this->db->prepare("SELECT * FROM `posts` WHERE `id` = ?");
  $stmt->bind_param('i', $request->params['id']);
  $stmt->execute();

  $post = $stmt->get_result()->fetch_assoc();

  return $this->render('./views/posts/edit.php', [
    'post' => $post
  ]);
});

$app->post('/posts', function($request) {
  ['title' => $title, 'content' => $content] = $request->params;

  $stmt = $this->db->prepare("INSERT INTO `posts` (`title`, `content`) VALUES (?, ?);");
  $stmt->bind_param('ss', $title, $content);
  $stmt->execute();
  $stmt->close();

  $this->redirect("/posts/{$this->db->insert_id}/show");
});

$app->post('/posts/:id', function($request) {
  ['id' => $id, 'title' => $title, 'content' => $content] = $request->params;

  $stmt = $this->db->prepare("UPDATE `posts` SET `title` = ?, `content` = ? WHERE `id` = ?");
  $stmt->bind_param('ssi', $title, $content, $id);
  $stmt->execute();
  $stmt->close();

  $this->redirect("/posts/{$id}/show");
});

$app->get('/posts/:id/show', function($request) {
  $stmt = $this->db->prepare("SELECT * FROM `posts` WHERE `id` = ?");
  $stmt->bind_param('i', $request->params['id']);
  $stmt->execute();

  $post = $stmt->get_result()->fetch_assoc();

  return $this->render('./views/posts/show.php', [
    'post' => $post
  ]);
});

$app->bootstrap();

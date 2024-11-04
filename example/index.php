<?php

require 'vendor/autoload.php';

use benignware\micro\Micro;
use benignware\micro\middleware\Database;
use benignware\micro\middleware\Authentication;
use benignware\micro\middleware\Pagination;
use benignware\micro\middleware\Json;
use benignware\micro\app\controllers\UserController;
use benignware\micro\app\controllers\PostController;
use benignware\micro\app\controllers\AuthController;

// Initialize the app instance
$app = Micro::getInstance();

// Set the views and layout directory
$app->set('views', 'views');
$app->set('layout', 'layouts/main');

$app->use(Json::middleware());


// Register database middleware
$app->use(Database::middleware([
    'db_host' => 'db',
    'db_user' => 'user',
    'db_password' => 'secret',
    'db_name' => 'database',
]));

// Register authentication middleware
$app->use(Authentication::middleware([
    'allowedPaths' => ['/login', '/register'],
    'restrictedPaths' => ['*/edit'], // Update paths as needed
    'redirectPath' => '/login',
    'redirectParam' => 'redirect', // Customizable redirect parameter
]));

// Register pagination middleware
$app->use(Pagination::middleware([
    'itemsPerPage' => 2 // Set the desired items per page
]));

$app->get('/', function ($req, $res) {
    $res->render('index');
});

// Define routes for authentication
$app->get('/register', [AuthController::class, 'register']);
$app->post('/register', [AuthController::class, 'register']);
$app->get('/login', [AuthController::class, 'login']);
$app->post('/login', [AuthController::class, 'login']);
$app->get('/logout', [AuthController::class, 'logout']);


// Define routes for user actions
$app->get('/users', [UserController::class, 'index']);
$app->get('/users/:id', [UserController::class, 'show']);
$app->get('/users/:id/edit', [UserController::class, 'edit']);
$app->post('/users/:id', [UserController::class, 'update']);
$app->delete('/users/:id', [UserController::class, 'destroy']);


// Define routes for post actions
$app->get('/posts', [PostController::class, 'index']);
$app->get('/posts/create', [PostController::class, 'create']);
$app->post('/posts', [PostController::class, 'store']);
$app->get('/posts/:id', [PostController::class, 'show']);
$app->get('/posts/:id/edit', [PostController::class, 'edit']);
$app->post('/posts/:id', [PostController::class, 'update']);
$app->delete('/posts/:id', [PostController::class, 'destroy']);

// Bootstrap the app to start handling requests
$app->bootstrap();

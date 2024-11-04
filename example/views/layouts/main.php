<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'My Micro App'; ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body class="d-flex flex-column h-100">
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <a class="navbar-brand" href="/">Home</a>
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="/posts">Posts</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/users">Users</a>
                        </li>
                    </ul>
                </div>
                <div class="ml-auto">
                    <?php if ($this->can()): ?>
                        <span class="navbar-text">Welcome, <?php echo htmlspecialchars($this->user->email); ?></span>
                        <a class="btn btn-outline-danger ml-2" href="<? $this->url('logout') ?>">Logout</a>
                    <?php else: ?>
                        <a class="btn btn-outline-primary" href="<? $this->url('login') ?>">Login</a>
                        <a class="btn btn-outline-success ml-2" href="/register">Register</a>
                    <?php endif; ?>
                </div>
                <a class="navbar-toggler" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="navbar-toggler-icon"></span>
                </a>
            </div>
        </nav>
    </header>

    <main class="container mt-4 flex-grow-1">
        <?php echo $content; // Render the view content ?>
    </main>

    <footer class="footer mt-4">
        <div class="container">
            <p class="text-muted">© <?php echo date('Y'); ?> My Micro App. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

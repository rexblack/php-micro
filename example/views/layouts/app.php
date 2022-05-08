<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Rafael Nowrotek">
    <title>Benignware Micro Demo App</title>

    <!-- Bootstrap core CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.css" rel="stylesheet" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="<?= $this->url('/assets/main.css'); ?>" rel="stylesheet">
  </head>
  <body>
    <div class="navbar navbar-expand-md navbar-light fixed-top bg-white border-bottom shadow-sm">
      <div class="container">
        <a class="navbar-brand" href="<?= $this->url('/'); ?>">micro.benignware.com</a>
        <?php if ($this->current_user): ?>
          <a class="ml-auto btn btn-outline-primary" href="<?= $this->url('/logout'); ?>">Logout</a>
        <?php else: ?>
          <a class="ml-auto btn btn-outline-primary" href="<?= $this->url('/login'); ?>">Login</a>
        <?php endif; ?>
      </div>
    </div>
    <main>
      <div class="container py-2 py-lg-4">
        <?= $content; ?>
      </div>
    </main>
    <footer class="mt-5 py-4 border-top">
      <div class="container">
        <div class="text-center">
          <small class="d-block text-muted">&copy; 2017-<?= date("Y"); ?> benignware.com</small>
        </div>
    </footer>
  </body>
</html>

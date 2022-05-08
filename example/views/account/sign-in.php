<form 
  class="form-signin d-flex flex-column align-items-center justify-content-center h-100"
  method="POST"
  action="<?= $this->url('/login'); ?>"
>
  <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
  <label for="email" class="sr-only">Email address</label>
  <input name="email" type="email" value="<?= $this->request->params['email'] ?>" id="email" class="form-control" placeholder="Email address" required autofocus>
  <label for="password" class="sr-only">Password</label>
  <input name="password" type="password" id="password" value="<?= $this->request->params['password'] ?>" class="form-control" placeholder="Password" required>

  <input name="redirect" type="hidden" value="<?= $this->request->params['redirect'] ?>">
  <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
</form>

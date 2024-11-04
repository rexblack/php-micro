<h1>Edit User</h1>

<form action="/users/<?php echo htmlspecialchars($user->id); ?>" method="POST">
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($user->email); ?>" required>
    </div>
    <div class="form-group">
        <label for="password">New Password (leave blank to keep unchanged)</label>
        <input type="password" name="password" id="password" class="form-control">
    </div>
    <button type="submit" class="btn btn-primary">Update User</button>
</form>

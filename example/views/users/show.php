<h1>User Details</h1>

<p><strong>ID:</strong> <?php echo htmlspecialchars($user->id); ?></p>
<p><strong>Email:</strong> <?php echo htmlspecialchars($user->email); ?></p>

<a href="/users" class="btn btn-secondary">Back to User List</a>
<a href="/users/<?php echo htmlspecialchars($user->id); ?>/edit" class="btn btn-warning">Edit User</a>

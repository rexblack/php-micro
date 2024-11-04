<!-- user/profile.php -->
<h1>User Profile</h1>
<p>ID: <?= htmlspecialchars($user->id) ?></p>
<p>Email: <?= htmlspecialchars($user->email) ?></p>
<a href="/user/<?= $user->id ?>/edit">Edit User</a>

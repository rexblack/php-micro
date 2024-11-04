<h1>User List</h1>

<a href="/register" class="btn btn-primary mb-3">Register New User</a>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo htmlspecialchars($user->id); ?></td>
            <td><?php echo htmlspecialchars($user->email); ?></td>
            <td>
                <a href="/users/<?php echo htmlspecialchars($user->id); ?>" class="btn btn-info">View</a>
                <a href="/users/<?php echo htmlspecialchars($user->id); ?>/edit" class="btn btn-warning">Edit</a>
                <form action="/users/<?php echo htmlspecialchars($user->id); ?>" method="POST" style="display:inline;">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?');">Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h1 class="mt-4"><?php echo htmlspecialchars($post->title); ?></h1>

<p class="lead"><?php echo nl2br(htmlspecialchars($post->content)); ?></p>

<div class="mt-4">
    <a href="/posts" class="btn btn-secondary">Back to Posts</a>
    <a href="/posts/<?php echo $post->id; ?>/edit" class="btn btn-warning">Edit</a>
    <form action="/posts/<?php echo $post->id; ?>" method="POST" style="display:inline;">
        <input type="hidden" name="_method" value="DELETE">
        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?');">Delete</button>
    </form>
</div>

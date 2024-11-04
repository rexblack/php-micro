<form action="/posts<?= isset($post) ? '/' . $post->id : '' ?>" method="POST">
    <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($post->title ?? '') ?>" required>
    </div>
    <div class="mb-3">
        <label for="content" class="form-label">Content</label>
        <textarea class="form-control" id="content" name="content" rows="5" required><?= htmlspecialchars($post->content ?? '') ?></textarea>
    </div>
    <button type="submit" class="btn btn-success"><?= isset($post) ? 'Update' : 'Create' ?></button>
    <a href="/posts" class="btn btn-secondary ms-2">Cancel</a> <!-- Cancel button to return to the posts list -->
</form>
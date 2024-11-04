<div class="container mt-5">
    <h1 class="mb-4">Posts</h1>

    <!-- Create Post link -->
    <div class="mb-4">
        <a href="<?= $this->url('/posts/create') ?>" class="btn btn-secondary">
            <i class="fas fa-plus"></i> Create Post
        </a>
    </div>

    <div class="row">
        <?php foreach ($posts as $post): ?>
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($post->title) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($post->content) ?></p>
                        <div class="d-flex justify-content-between mt-4">
                            <a href="<?= $this->url('/posts/' . $post->id) ?>" class="btn btn-secondary btn-sm">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a href="<?= $this->url('/posts/' . $post->id . '/edit') ?>" class="btn btn-secondary btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="<?= $this->url('/posts/' . $post->id) ?>" method="POST" class="d-inline">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-secondary btn-sm" onclick="return confirm('Are you sure you want to delete this post?');">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="card-footer text-muted">
                        <small>Posted on <?= date('F j, Y', strtotime($post->created_at ?? 'now')) ?></small>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Call the paginate helper directly in the view with custom options -->
    <?= $this->paginate($totalPosts, $currentPage, [
        'paginationClass' => 'pagination', // Custom class for the pagination container
        'pageItemClass' => 'page-item',     // Custom class for each page item
        'pageLinkClass' => 'page-link',     // Custom class for each page link
    ]) ?>
</div>

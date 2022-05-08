<h1>Posts</h1>

<table class="table">
  <thead>
    <tr>
      <th>Id</th>
      <th>Title</th>
      <th>Content</th>
      <th></th>
    </tr>
  </thead>
  <?php foreach ($posts as $post): ?>
    <tr>
      <td><?= $post['id']; ?></td>
      <td><?= $post['title']; ?></td>
      <td width="100%"><?= $post['content']; ?></td>
      <td>
        <div class="btn-group" role="group" aria-label="Basic example">
          <a class="btn btn-sm btn-outline-secondary" href="<?= $this->url("/posts/{$post['id']}/show"); ?>"><i class="fas fa-eye"></i></a>
          <a class="btn btn-sm btn-outline-secondary" href="<?= $this->url("/posts/{$post['id']}/edit"); ?>"><i class="fas fa-edit"></i></a>
        </div>
      </td>
    </tr>
  <?php endforeach; ?>
</table>

<a class="btn btn-primary" href="<?= $this->url('/posts/new'); ?>"><i class="fas fa-plus-circle"></i>&nbsp;<span>Add post</span></a>

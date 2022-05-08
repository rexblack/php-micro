<h1><?= $post['title']; ?></h1>
<?= $post['content']; ?>

<p class="my-4">
  <a class="btn btn-primary" href="<?= $this->url("/posts/{$post['id']}/edit/"); ?>"><i class="fas fa-edit"></i>&nbsp;<span>Edit post</span></a>
</p>

<form
  class="form-post"
  action="<?= $this->url("/posts" . (isset($post['id']) ? "/{$post['id']}" : '')); ?>"
  method="POST"
>
  <div class="form-group">
    <label>Title</label>
    <input class="form-control" name="title" value="<?= $post['title']; ?>"/>
  </div>
  <div class="form-group">
    <label>Content</label>
    <input class="form-control" name="content" value="<?= $post['content']; ?>"/>
  </div>
  <div class="mt-2 mt-lg-4">
    <button class="btn btn-primary" type="submit">Save Post</button>
  </div>
</form>

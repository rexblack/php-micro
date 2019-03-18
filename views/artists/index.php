<?php
  $stmt = $db->query('SELECT * FROM artists');
  $rows = $stmt->fetchAll();
?>
<h1>Artists</h1>
<?php foreach($rows as $index => $row): ?>
  <h5><a href="<?= $base_url ?>/artists/<?= $row['id']; ?>"><?= $row['name']; ?></a></h5>
<?php endforeach; ?>

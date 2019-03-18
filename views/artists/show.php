<?php
  $stmt = $db->prepare('SELECT * FROM artists WHERE id = ?');
  $stmt->execute([
    $params['id']
  ]);
  $row = $stmt->fetch();
?>
<h1><?= $row['name']; ?></h1>

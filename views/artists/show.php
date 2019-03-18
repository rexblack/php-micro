<?php
  $stmt = $db->prepare('SELECT * FROM artists WHERE id = ?');
  $stmt->execute([
    $params['id']
  ]);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<h1><?= $row['name']; ?></h1>

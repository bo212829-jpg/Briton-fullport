<?php
session_start();
if (empty($_SESSION['user_id']) || empty($_SESSION['is_admin'])) {
    header('Location: login.php');
    exit;
}
require_once __DIR__ . '/db.php';

$stmt = $pdo->query('SELECT id, username, email, is_admin, created_at, last_login FROM users ORDER BY created_at DESC');
$users = $stmt->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin - Users</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container py-4">
  <h3>Users</h3>
  <table class="table table-striped">
    <thead>
      <tr><th>ID</th><th>Username</th><th>Email</th><th>Admin</th><th>Registered</th><th>Last login</th></tr>
    </thead>
    <tbody>
      <?php foreach ($users as $u): ?>
        <tr>
          <td><?=htmlspecialchars($u['id'])?></td>
          <td><?=htmlspecialchars($u['username'])?></td>
          <td><?=htmlspecialchars($u['email'])?></td>
          <td><?= $u['is_admin'] ? 'Yes' : 'No' ?></td>
          <td><?=htmlspecialchars($u['created_at'])?></td>
          <td><?=htmlspecialchars($u['last_login'] ?? 'Never')?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <a href="dashboard.php" class="btn btn-secondary">Back</a>
</div>
</body>
</html>

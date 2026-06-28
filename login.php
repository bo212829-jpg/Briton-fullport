<?php
session_start();
require_once __DIR__ . '/db.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $errors[] = 'Enter username and password.';
    } else {
        $stmt = $pdo->prepare('SELECT id, username, password, is_admin FROM users WHERE username = :u LIMIT 1');
        $stmt->execute(['u' => $username]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = (int)$user['is_admin'];

            // update last_login and store in login_logs
            $u = $pdo->prepare('UPDATE users SET last_login = NOW() WHERE id = :id');
            $u->execute(['id' => $user['id']]);
            $log = $pdo->prepare('INSERT INTO login_logs (user_id, ip_address) VALUES (:id, :ip)');
            $log->execute(['id' => $user['id'], 'ip' => $_SERVER['REMOTE_ADDR'] ?? null]);

            header('Location: dashboard.php');
            exit;
        } else {
            $errors[] = 'Invalid credentials.';
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/style.css">
  <style>body{background:linear-gradient(135deg,#a18cd1,#fbc2eb);}</style>
</head>
<body>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h3 class="card-title text-center mb-3">Welcome back</h3>
          <?php if (!empty($errors)): ?>
            <div class="alert alert-danger"><?=htmlspecialchars(implode(', ', $errors))?></div>
          <?php endif; ?>
          <form method="post" novalidate>
            <div class="mb-3">
              <label class="form-label">Username</label>
              <input name="username" class="form-control" required value="<?=htmlspecialchars($_POST['username'] ?? '')?>">
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <input name="password" type="password" class="form-control" required>
            </div>
            <div class="d-grid">
              <button class="btn btn-success btn-lg">Login</button>
            </div>
          </form>
          <hr>
          <p class="text-center">Don't have an account? <a href="register.php">Register</a></p>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>

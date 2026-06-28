<?php
session_start();
require_once __DIR__ . '/db.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if (strlen($username) < 3) $errors[] = 'Username must be at least 3 characters.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email address.';
    if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';
    if ($password !== $confirm) $errors[] = 'Passwords do not match.';

    if (empty($errors)) {
        // Check duplicates
        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = :u OR email = :e LIMIT 1');
        $stmt->execute(['u' => $username, 'e' => $email]);
        if ($stmt->fetch()) {
            $errors[] = 'Username or email already taken.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $ins = $pdo->prepare('INSERT INTO users (username,email,password) VALUES (:u,:e,:p)');
            $ins->execute(['u' => $username, 'e' => $email, 'p' => $hash]);
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['username'] = $username;
            $_SESSION['is_admin'] = 0;
            header('Location: dashboard.php');
            exit;
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/style.css">
  <style>body{background:linear-gradient(135deg,#f6d365,#fda085);}</style>
</head>
<body>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h3 class="card-title text-center mb-3">Create your account</h3>
          <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
              <ul class="mb-0">
                <?php foreach ($errors as $e): ?><li><?=htmlspecialchars($e)?></li><?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>
          <form method="post" novalidate>
            <div class="mb-3">
              <label class="form-label">Username</label>
              <input name="username" class="form-control" required value="<?=htmlspecialchars($_POST['username'] ?? '')?>">
            </div>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input name="email" type="email" class="form-control" required value="<?=htmlspecialchars($_POST['email'] ?? '')?>">
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <input name="password" type="password" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Confirm password</label>
              <input name="confirm" type="password" class="form-control" required>
            </div>
            <div class="d-grid">
              <button class="btn btn-primary btn-lg">Register</button>
            </div>
          </form>
          <hr>
          <p class="text-center">Already have an account? <a href="login.php">Log in</a></p>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>

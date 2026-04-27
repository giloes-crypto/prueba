<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/bootstrap.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = db()->prepare('SELECT id, password_hash FROM admins WHERE email = :email LIMIT 1');
    $stmt->execute(['email' => $email]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password_hash'])) {
        $_SESSION['admin_id'] = (int) $admin['id'];
        header('Location: dashboard.php');
        exit;
    }

    $error = 'Credenciales inválidas.';
}
?>
<!doctype html>
<html lang="es">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><link rel="stylesheet" href="../assets/css/styles.css"><title>Admin</title></head>
<body class="panel-body">
<form class="card" method="post">
    <h1>Login Administrador</h1>
    <?php if ($error): ?><p class="error"><?= htmlspecialchars($error); ?></p><?php endif; ?>
    <input name="email" type="email" placeholder="Correo" required>
    <input name="password" type="password" placeholder="Contraseña" required>
    <button type="submit">Entrar</button>
</form>
</body>
</html>

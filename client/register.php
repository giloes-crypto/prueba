<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/bootstrap.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';

    $code = (string) random_int(100000, 999999);
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = db()->prepare('INSERT INTO clients (email, phone, password_hash, verification_code) VALUES (:email, :phone, :password_hash, :verification_code)');
    $stmt->execute([
        'email' => $email,
        'phone' => $phone,
        'password_hash' => $hash,
        'verification_code' => $code,
    ]);

    $message = "Cuenta creada. Código de verificación (simulado envío por correo): {$code}";
}
?>
<!doctype html>
<html lang="es">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><link rel="stylesheet" href="../assets/css/styles.css"><title>Registro Cliente</title></head>
<body class="panel-body">
<form class="card" method="post">
    <h1>Registro cliente</h1>
    <?php if ($message): ?><p><?= htmlspecialchars($message); ?></p><?php endif; ?>
    <input type="email" name="email" placeholder="Correo" required>
    <input type="tel" name="phone" placeholder="Teléfono" required>
    <input type="password" name="password" placeholder="Contraseña" required>
    <button type="submit">Crear cuenta</button>
    <a href="verify.php">Verificar cuenta</a>
    <a href="login.php">Ya tengo cuenta</a>
</form>
</body>
</html>

<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/bootstrap.php';

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $code = trim($_POST['code'] ?? '');

    $stmt = db()->prepare('UPDATE clients SET is_verified = 1, verification_code = NULL WHERE email = :email AND verification_code = :code');
    $stmt->execute(['email' => $email, 'code' => $code]);

    $msg = $stmt->rowCount() > 0 ? 'Cuenta verificada correctamente.' : 'Código inválido.';
}
?>
<!doctype html>
<html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><link rel="stylesheet" href="../assets/css/styles.css"><title>Verificación</title></head>
<body class="panel-body">
<form class="card" method="post">
    <h1>Verificar cuenta</h1>
    <?php if ($msg): ?><p><?= htmlspecialchars($msg); ?></p><?php endif; ?>
    <input name="email" type="email" placeholder="Correo" required>
    <input name="code" type="text" placeholder="Código" required>
    <button type="submit">Verificar</button>
</form>
</body></html>

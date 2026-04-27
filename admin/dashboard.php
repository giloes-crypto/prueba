<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/bootstrap.php';

if (empty($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'save_service') {
        $name = trim($_POST['name'] ?? '');
        $price = (float) ($_POST['price'] ?? 0);
        $id = (int) ($_POST['id'] ?? 0);

        if ($id > 0) {
            $stmt = db()->prepare('UPDATE services SET name = :name, price = :price WHERE id = :id');
            $stmt->execute(compact('name', 'price', 'id'));
        } else {
            $stmt = db()->prepare('INSERT INTO services (name, price) VALUES (:name, :price)');
            $stmt->execute(compact('name', 'price'));
        }
    }

    if ($action === 'delete_service') {
        $id = (int) ($_POST['id'] ?? 0);
        $stmt = db()->prepare('DELETE FROM services WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    if ($action === 'save_notice') {
        $text = trim($_POST['notice'] ?? '');
        $stmt = db()->prepare('UPDATE site_settings SET setting_value = :text WHERE setting_key = "home_notice"');
        $stmt->execute(['text' => $text]);
    }

    header('Location: dashboard.php');
    exit;
}

$services = db()->query('SELECT id, name, price FROM services ORDER BY id DESC')->fetchAll();
$notice = db()->query('SELECT setting_value FROM site_settings WHERE setting_key = "home_notice"')->fetchColumn() ?: '';
?>
<!doctype html>
<html lang="es">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><link rel="stylesheet" href="../assets/css/styles.css"><title>Dashboard Admin</title></head>
<body class="panel-body">
<div class="container">
<h1>Panel de administración</h1>
<section class="card">
    <h2>Notificación principal</h2>
    <form method="post">
        <input type="hidden" name="action" value="save_notice">
        <textarea name="notice" required><?= htmlspecialchars((string)$notice); ?></textarea>
        <button type="submit">Guardar notificación</button>
    </form>
</section>
<section class="card">
    <h2>Servicios</h2>
    <form method="post" class="inline-form">
        <input type="hidden" name="action" value="save_service">
        <input name="name" placeholder="Servicio" required>
        <input name="price" placeholder="Precio" type="number" step="0.01" required>
        <button type="submit">Agregar servicio</button>
    </form>
    <?php foreach ($services as $service): ?>
        <form method="post" class="inline-form">
            <input type="hidden" name="action" value="save_service">
            <input type="hidden" name="id" value="<?= (int) $service['id']; ?>">
            <input name="name" value="<?= htmlspecialchars($service['name']); ?>">
            <input name="price" type="number" step="0.01" value="<?= htmlspecialchars((string) $service['price']); ?>">
            <button type="submit">Actualizar</button>
        </form>
        <form method="post">
            <input type="hidden" name="action" value="delete_service">
            <input type="hidden" name="id" value="<?= (int) $service['id']; ?>">
            <button class="danger" type="submit">Eliminar</button>
        </form>
        <hr>
    <?php endforeach; ?>
</section>
</div>
</body>
</html>

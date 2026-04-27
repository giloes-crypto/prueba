<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/bootstrap.php';

if (empty($_SESSION['client_id'])) {
    header('Location: login.php');
    exit;
}

$clientId = (int) $_SESSION['client_id'];
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $serviceId = (int) ($_POST['service_id'] ?? 0);
    $appointmentAt = $_POST['appointment_at'] ?? '';
    $payment = (float) ($_POST['payment_amount'] ?? 0);

    if ($payment !== 25.0) {
        $msg = 'Debes completar el pago exacto de 25 USD para reservar.';
    } else {
        $stmt = db()->prepare('INSERT INTO appointments (client_id, service_id, appointment_at, payment_amount, payment_status) VALUES (:client_id, :service_id, :appointment_at, :payment_amount, :payment_status)');
        $stmt->execute([
            'client_id' => $clientId,
            'service_id' => $serviceId,
            'appointment_at' => $appointmentAt,
            'payment_amount' => $payment,
            'payment_status' => 'paid',
        ]);
        $msg = 'Cita registrada correctamente.';
    }
}

$services = db()->query('SELECT id, name, price FROM services ORDER BY name')->fetchAll();
$appointments = db()->prepare('SELECT a.appointment_at, a.payment_amount, s.name FROM appointments a JOIN services s ON s.id = a.service_id WHERE a.client_id = :client_id ORDER BY a.appointment_at DESC');
$appointments->execute(['client_id' => $clientId]);
$rows = $appointments->fetchAll();
?>
<!doctype html>
<html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><link rel="stylesheet" href="../assets/css/styles.css"><title>Dashboard Cliente</title></head>
<body class="panel-body">
<div class="container">
    <section class="card">
        <h1>Agendar cita</h1>
        <?php if ($msg): ?><p><?= htmlspecialchars($msg); ?></p><?php endif; ?>
        <form method="post">
            <select name="service_id" required>
                <option value="">Selecciona servicio</option>
                <?php foreach ($services as $service): ?>
                    <option value="<?= (int)$service['id']; ?>"><?= htmlspecialchars($service['name']); ?> - $<?= number_format((float)$service['price'], 2); ?></option>
                <?php endforeach; ?>
            </select>
            <input type="datetime-local" name="appointment_at" required>
            <input type="number" step="0.01" min="25" name="payment_amount" value="25.00" required>
            <small>Pago obligatorio: $25.00 USD</small>
            <button type="submit">Reservar</button>
        </form>
    </section>

    <section class="card">
        <h2>Mis citas</h2>
        <ul>
            <?php foreach ($rows as $row): ?>
                <li><?= htmlspecialchars($row['appointment_at']); ?> - <?= htmlspecialchars($row['name']); ?> - $<?= number_format((float)$row['payment_amount'], 2); ?></li>
            <?php endforeach; ?>
        </ul>
    </section>
</div>
</body></html>

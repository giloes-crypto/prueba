<?php

declare(strict_types=1);

require_once __DIR__ . '/../backend/db.php';

header('Content-Type: application/json; charset=utf-8');

$date = $_GET['date'] ?? '';
$serviceId = (int) ($_GET['service_id'] ?? 0);

if (!$date || !$serviceId) {
    http_response_code(422);
    echo json_encode(['error' => 'date y service_id son obligatorios']);
    exit;
}

$pdo = db();

$svc = $pdo->prepare('SELECT duracion_minutos FROM servicios WHERE id = :id AND activo = 1');
$svc->execute(['id' => $serviceId]);
$service = $svc->fetch();

if (!$service) {
    http_response_code(404);
    echo json_encode(['error' => 'Servicio no encontrado']);
    exit;
}

$duration = (int) $service['duracion_minutos'];
$workStart = new DateTimeImmutable($date . ' 09:00:00');
$workEnd = new DateTimeImmutable($date . ' 18:00:00');
$stepMinutes = 30;

$stmt = $pdo->prepare("SELECT fecha_hora_inicio, fecha_hora_fin FROM citas WHERE estado_pago IN ('confirmado','pendiente') AND (expira_en IS NULL OR expira_en > NOW()) AND DATE(fecha_hora_inicio)=:date");
$stmt->execute(['date' => $date]);
$busy = $stmt->fetchAll();

$slots = [];
for ($slotStart = $workStart; $slotStart < $workEnd; $slotStart = $slotStart->modify("+{$stepMinutes} minutes")) {
    $slotEnd = $slotStart->modify("+{$duration} minutes");
    if ($slotEnd > $workEnd) {
        break;
    }

    $collision = false;
    foreach ($busy as $appointment) {
        $busyStart = new DateTimeImmutable($appointment['fecha_hora_inicio']);
        $busyEnd = new DateTimeImmutable($appointment['fecha_hora_fin']);
        if (($slotStart < $busyEnd) && ($slotEnd > $busyStart)) {
            $collision = true;
            break;
        }
    }

    if (!$collision && $slotStart > new DateTimeImmutable()) {
        $slots[] = $slotStart->format('H:i');
    }
}

echo json_encode(['date' => $date, 'slots' => $slots]);

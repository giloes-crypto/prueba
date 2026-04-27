<?php

declare(strict_types=1);

require_once __DIR__ . '/../backend/db.php';
require_once __DIR__ . '/../backend/config.php';

session_start();
header('Content-Type: application/json; charset=utf-8');

if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Debe iniciar sesión']);
    exit;
}

$payload = json_decode(file_get_contents('php://input'), true) ?? [];
$serviceId = (int) ($payload['service_id'] ?? 0);
$start = trim((string) ($payload['fecha_hora_inicio'] ?? ''));

if (!$serviceId || !$start) {
    http_response_code(422);
    echo json_encode(['error' => 'Parámetros incompletos']);
    exit;
}

$pdo = db();
$pdo->beginTransaction();

try {
    $svc = $pdo->prepare('SELECT duracion_minutos FROM servicios WHERE id = :id AND activo = 1 FOR UPDATE');
    $svc->execute(['id' => $serviceId]);
    $service = $svc->fetch();

    if (!$service) {
        throw new RuntimeException('Servicio no encontrado');
    }

    $startDt = new DateTimeImmutable($start);
    $endDt = $startDt->modify('+' . (int) $service['duracion_minutos'] . ' minutes');

    $check = $pdo->prepare("SELECT COUNT(*) FROM citas WHERE estado_pago IN ('confirmado','pendiente') AND (expira_en IS NULL OR expira_en > NOW()) AND (:new_start < fecha_hora_fin) AND (:new_end > fecha_hora_inicio) FOR UPDATE");
    $check->execute([
        'new_start' => $startDt->format('Y-m-d H:i:s'),
        'new_end' => $endDt->format('Y-m-d H:i:s'),
    ]);

    if ((int) $check->fetchColumn() > 0) {
        throw new RuntimeException('Horario ocupado');
    }

    $ins = $pdo->prepare("INSERT INTO citas (id_cliente, id_servicio, fecha_hora_inicio, fecha_hora_fin, estado_pago, monto_anticipo, expira_en) VALUES (:id_cliente, :id_servicio, :inicio, :fin, 'pendiente', :monto, DATE_ADD(NOW(), INTERVAL 10 MINUTE))");

    $ins->execute([
        'id_cliente' => (int) $_SESSION['user_id'],
        'id_servicio' => $serviceId,
        'inicio' => $startDt->format('Y-m-d H:i:s'),
        'fin' => $endDt->format('Y-m-d H:i:s'),
        'monto' => PAYPAL_ANTICIPO_USD,
    ]);

    $pdo->commit();
    echo json_encode(['ok' => true, 'cita_id' => (int) $pdo->lastInsertId(), 'estado' => 'pendiente']);
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(409);
    echo json_encode(['error' => $e->getMessage()]);
}

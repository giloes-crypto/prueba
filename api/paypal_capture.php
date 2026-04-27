<?php

declare(strict_types=1);

require_once __DIR__ . '/../backend/db.php';
require_once __DIR__ . '/../backend/config.php';

header('Content-Type: application/json; charset=utf-8');

$payload = json_decode(file_get_contents('php://input'), true) ?? [];
$citaId = (int) ($payload['cita_id'] ?? 0);
$orderId = trim((string) ($payload['order_id'] ?? ''));

if (!$citaId || !$orderId) {
    http_response_code(422);
    echo json_encode(['error' => 'cita_id y order_id son obligatorios']);
    exit;
}

// Placeholder del flujo server-side: en producción usar OAuth2 + capture PayPal con cURL.
$pdo = db();
$stmt = $pdo->prepare("UPDATE citas SET estado_pago='confirmado', referencia_pago=:ref, expira_en=NULL WHERE id=:id AND estado_pago='pendiente'");
$stmt->execute(['ref' => $orderId, 'id' => $citaId]);

echo json_encode(['ok' => true, 'capturado' => $stmt->rowCount() === 1]);

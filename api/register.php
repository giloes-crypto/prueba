<?php

declare(strict_types=1);

require_once __DIR__ . '/../backend/db.php';

header('Content-Type: application/json; charset=utf-8');

$payload = json_decode(file_get_contents('php://input'), true) ?? [];

$nombre = trim((string) ($payload['nombre'] ?? ''));
$email = strtolower(trim((string) ($payload['email'] ?? '')));
$password = (string) ($payload['password'] ?? '');
$telefono = trim((string) ($payload['telefono'] ?? ''));

if (!$nombre || !$email || strlen($password) < 8) {
    http_response_code(422);
    echo json_encode(['error' => 'Datos inválidos']);
    exit;
}

$hash = password_hash($password, PASSWORD_BCRYPT);

try {
    $stmt = db()->prepare('INSERT INTO usuarios (nombre, email, telefono, password_hash, rol) VALUES (:nombre, :email, :telefono, :hash, :rol)');
    $stmt->execute([
        'nombre' => $nombre,
        'email' => $email,
        'telefono' => $telefono,
        'hash' => $hash,
        'rol' => 'cliente',
    ]);

    echo json_encode(['ok' => true]);
} catch (PDOException $e) {
    http_response_code(409);
    echo json_encode(['error' => 'El correo ya está registrado']);
}

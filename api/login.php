<?php

declare(strict_types=1);

require_once __DIR__ . '/../backend/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');
$payload = json_decode(file_get_contents('php://input'), true) ?? [];
$email = strtolower(trim((string) ($payload['email'] ?? '')));
$password = (string) ($payload['password'] ?? '');

$stmt = db()->prepare('SELECT id, nombre, password_hash, rol FROM usuarios WHERE email = :email');
$stmt->execute(['email' => $email]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password_hash'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Credenciales inválidas']);
    exit;
}

$_SESSION['user_id'] = (int) $user['id'];
$_SESSION['user_name'] = $user['nombre'];
$_SESSION['role'] = $user['rol'];

echo json_encode(['ok' => true, 'role' => $user['rol']]);

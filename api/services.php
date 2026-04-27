<?php

declare(strict_types=1);

require_once __DIR__ . '/../backend/db.php';
require_once __DIR__ . '/../backend/i18n.php';

header('Content-Type: application/json; charset=utf-8');
$lang = detectLanguage();

$stmt = db()->prepare('SELECT id, nombre_es, nombre_en, descripcion_es, descripcion_en, precio, duracion_minutos, mostrar_precio, imagen FROM servicios WHERE activo = 1 ORDER BY id DESC');
$stmt->execute();
$rows = $stmt->fetchAll();

echo json_encode(array_map(static function (array $row) use ($lang): array {
    return [
        'id' => (int) $row['id'],
        'nombre' => $row['nombre_' . $lang],
        'descripcion' => $row['descripcion_' . $lang],
        'precio' => (float) $row['precio'],
        'duracion_minutos' => (int) $row['duracion_minutos'],
        'mostrar_precio' => (bool) $row['mostrar_precio'],
        'imagen' => $row['imagen'],
    ];
}, $rows), JSON_UNESCAPED_UNICODE);

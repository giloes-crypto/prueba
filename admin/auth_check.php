<?php

declare(strict_types=1);

session_start();

if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? null) !== 'admin') {
    http_response_code(403);
    exit('Acceso restringido');
}

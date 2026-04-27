<?php

declare(strict_types=1);

require_once __DIR__ . '/db.php';

function encolarCorreo(string $destinatario, string $asunto, string $cuerpoHtml, string $idioma = 'es'): void
{
    $stmt = db()->prepare('INSERT INTO cola_correos (destinatario, asunto, cuerpo_html, idioma) VALUES (:destinatario, :asunto, :cuerpo_html, :idioma)');
    $stmt->execute([
        'destinatario' => $destinatario,
        'asunto' => $asunto,
        'cuerpo_html' => $cuerpoHtml,
        'idioma' => in_array($idioma, ['es', 'en'], true) ? $idioma : 'es',
    ]);
}

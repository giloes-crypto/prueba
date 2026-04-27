<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/database.php';

function currentLocale(): string
{
    if (!empty($_SESSION['locale']) && in_array($_SESSION['locale'], ['es', 'en'], true)) {
        return $_SESSION['locale'];
    }

    return 'es';
}

function t(string $key): string
{
    $locale = currentLocale();
    $translations = [
        'es' => [
            'welcome' => 'Bienvenida al salón de belleza',
            'choose_language' => 'Selecciona tu idioma',
            'enter_spanish' => 'Entrar en Español',
            'enter_english' => 'Enter in English',
            'admin_login' => 'Acceso administrador',
            'client_login' => 'Acceso cliente',
            'book_appointment' => 'Agendar cita',
            'hero_text' => 'Servicios profesionales de belleza con reservas en línea.',
            'verify_account' => 'Verificar cuenta',
            'logout' => 'Cerrar sesión',
        ],
        'en' => [
            'welcome' => 'Welcome to the beauty salon',
            'choose_language' => 'Choose your language',
            'enter_spanish' => 'Entrar en Español',
            'enter_english' => 'Enter in English',
            'admin_login' => 'Admin login',
            'client_login' => 'Client login',
            'book_appointment' => 'Book appointment',
            'hero_text' => 'Professional beauty services with online booking.',
            'verify_account' => 'Verify account',
            'logout' => 'Logout',
        ],
    ];

    return $translations[$locale][$key] ?? $key;
}

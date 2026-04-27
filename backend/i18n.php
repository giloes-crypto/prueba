<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

function detectLanguage(): string
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!empty($_GET['lang']) && in_array($_GET['lang'], APP_ALLOWED_LANGS, true)) {
        $_SESSION['lang'] = $_GET['lang'];
        return $_SESSION['lang'];
    }

    if (!empty($_SESSION['lang']) && in_array($_SESSION['lang'], APP_ALLOWED_LANGS, true)) {
        return $_SESSION['lang'];
    }

    $acceptLanguage = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? APP_DEFAULT_LANG;
    $candidate = strtolower(substr($acceptLanguage, 0, 2));
    $_SESSION['lang'] = in_array($candidate, APP_ALLOWED_LANGS, true) ? $candidate : APP_DEFAULT_LANG;

    return $_SESSION['lang'];
}

function t(string $key, array $translations): string
{
    return $translations[$key] ?? $key;
}

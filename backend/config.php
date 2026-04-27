<?php

declare(strict_types=1);

date_default_timezone_set('America/Mexico_City');

const DB_HOST = '127.0.0.1';
const DB_NAME = 'salon_belleza';
const DB_USER = 'root';
const DB_PASS = '';

const APP_DEFAULT_LANG = 'es';
const APP_ALLOWED_LANGS = ['es', 'en'];

const SMTP_HOST = 'smtp.example.com';
const SMTP_PORT = 587;
const SMTP_USER = 'no-reply@example.com';
const SMTP_PASS = 'change_me';
const SMTP_FROM_NAME = 'Salón Belleza';

const PAYPAL_CLIENT_ID = 'sandbox_client_id';
const PAYPAL_CLIENT_SECRET = 'sandbox_client_secret';
const PAYPAL_API_BASE = 'https://api-m.sandbox.paypal.com';
const PAYPAL_ANTICIPO_USD = 25.00;

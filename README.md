# Salón de Belleza SPA (PHP + MySQL)

Implementación base alineada a los requerimientos:

- SPA ligera con navegación por secciones y menú sticky.
- i18n ES/EN con detección automática por `HTTP_ACCEPT_LANGUAGE` y persistencia en `$_SESSION['lang']`.
- Base de datos relacional con tablas de servicios, usuarios, citas, CMS y cola de correos.
- Lógica anti-colisión por trigger SQL y por validación transaccional en PHP.
- Registro/Login con hashing bcrypt.
- Flujo de reserva en estado `pendiente` con expiración para bloqueo temporal.
- Endpoint de captura PayPal server-side (placeholder listo para integrar API oficial).

## Ejecutar local

1. Importar `sql/schema.sql` en MySQL.
2. Configurar credenciales en `backend/config.php`.
3. Servir `public/` con PHP:
   ```bash
   php -S localhost:8000 -t public
   ```

## Endpoints API

- `GET /api/services.php`
- `GET /api/availability.php?date=YYYY-MM-DD&service_id=1`
- `POST /api/register.php`
- `POST /api/login.php`
- `POST /api/book.php`
- `POST /api/paypal_capture.php`

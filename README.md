# Sitio web para salón de belleza (PHP + MySQL)

Proyecto base responsive para salón de belleza con:

- Selector inicial de idioma (español / inglés).
- Acceso separado para administrador y cliente.
- Panel administrador para crear/actualizar/eliminar servicios y actualizar notificación principal.
- Flujo cliente con registro, verificación por código (simulación de correo), login y agenda de cita.
- Regla de pago obligatorio de **$25 USD** para confirmar cita.

## Requisitos

- PHP 8.1+
- MySQL 8+
- Servidor web local (Apache/Nginx) o servidor embebido de PHP.

## Configuración rápida

1. Crear base de datos y tablas:
   ```bash
   mysql -u root -p < sql/schema.sql
   ```
2. Exportar variables de entorno (si aplica):
   ```bash
   export DB_HOST=127.0.0.1
   export DB_NAME=beauty_salon
   export DB_USER=root
   export DB_PASS=
   ```
3. Iniciar servidor local:
   ```bash
   php -S localhost:8000 -t public
   ```
4. Abrir en navegador: `http://localhost:8000`

## Credenciales iniciales de administrador

- Correo: `admin@salon.com`
- Contraseña (hash precargado): `admin123`

> Nota: En producción, integrar servicio real de email para verificación y pasarela de pago real.

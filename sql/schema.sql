CREATE DATABASE IF NOT EXISTS salon_belleza CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE salon_belleza;

CREATE TABLE IF NOT EXISTS configuracion_sitio (
  id INT AUTO_INCREMENT PRIMARY KEY,
  logo VARCHAR(255) DEFAULT NULL,
  telefono_1 VARCHAR(30) DEFAULT NULL,
  telefono_2 VARCHAR(30) DEFAULT NULL,
  direccion_es VARCHAR(255) DEFAULT NULL,
  direccion_en VARCHAR(255) DEFAULT NULL,
  mapa_embed_url TEXT DEFAULT NULL,
  nosotros_es TEXT DEFAULT NULL,
  nosotros_en TEXT DEFAULT NULL,
  whatsapp VARCHAR(30) DEFAULT NULL,
  pago_obligatorio TINYINT(1) NOT NULL DEFAULT 1,
  monto_anticipo DECIMAL(10,2) NOT NULL DEFAULT 25.00,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(120) NOT NULL,
  email VARCHAR(160) NOT NULL UNIQUE,
  telefono VARCHAR(30) DEFAULT NULL,
  password_hash VARCHAR(255) NOT NULL,
  rol ENUM('admin','cliente') NOT NULL DEFAULT 'cliente',
  idioma_preferido ENUM('es','en') NOT NULL DEFAULT 'es',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS servicios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre_es VARCHAR(150) NOT NULL,
  nombre_en VARCHAR(150) NOT NULL,
  descripcion_es TEXT NOT NULL,
  descripcion_en TEXT NOT NULL,
  precio DECIMAL(10,2) NOT NULL,
  duracion_minutos SMALLINT UNSIGNED NOT NULL,
  mostrar_precio TINYINT(1) NOT NULL DEFAULT 1,
  activo TINYINT(1) NOT NULL DEFAULT 1,
  imagen VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS horarios_bloqueados (
  id INT AUTO_INCREMENT PRIMARY KEY,
  fecha DATE NOT NULL UNIQUE,
  motivo VARCHAR(160) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS citas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_cliente INT NOT NULL,
  id_servicio INT NOT NULL,
  fecha_hora_inicio DATETIME NOT NULL,
  fecha_hora_fin DATETIME NOT NULL,
  estado_pago ENUM('pendiente','confirmado','cancelado','expirado') NOT NULL DEFAULT 'pendiente',
  referencia_pago VARCHAR(120) DEFAULT NULL,
  moneda CHAR(3) NOT NULL DEFAULT 'USD',
  monto_anticipo DECIMAL(10,2) NOT NULL DEFAULT 25.00,
  expira_en DATETIME DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_citas_cliente FOREIGN KEY (id_cliente) REFERENCES usuarios(id),
  CONSTRAINT fk_citas_servicio FOREIGN KEY (id_servicio) REFERENCES servicios(id),
  INDEX idx_citas_rango (fecha_hora_inicio, fecha_hora_fin),
  INDEX idx_citas_estado (estado_pago)
);

CREATE TABLE IF NOT EXISTS cola_correos (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  destinatario VARCHAR(160) NOT NULL,
  asunto VARCHAR(255) NOT NULL,
  cuerpo_html MEDIUMTEXT NOT NULL,
  idioma ENUM('es','en') NOT NULL DEFAULT 'es',
  estado ENUM('pendiente','enviado','error') NOT NULL DEFAULT 'pendiente',
  intentos TINYINT UNSIGNED NOT NULL DEFAULT 0,
  ultimo_error TEXT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  sent_at TIMESTAMP NULL DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS logs_auditoria (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT NULL,
  accion VARCHAR(120) NOT NULL,
  detalle JSON NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_logs_user (id_usuario)
);

DELIMITER $$
CREATE TRIGGER tr_validar_colision_citas
BEFORE INSERT ON citas
FOR EACH ROW
BEGIN
  DECLARE colisiones INT DEFAULT 0;

  SELECT COUNT(*) INTO colisiones
  FROM citas c
  WHERE c.estado_pago IN ('confirmado','pendiente')
    AND (c.expira_en IS NULL OR c.expira_en > NOW())
    AND (NEW.fecha_hora_inicio < c.fecha_hora_fin)
    AND (NEW.fecha_hora_fin > c.fecha_hora_inicio);

  IF colisiones > 0 THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'Horario no disponible por colision de citas';
  END IF;
END$$
DELIMITER ;

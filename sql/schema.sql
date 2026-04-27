CREATE DATABASE IF NOT EXISTS beauty_salon CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE beauty_salon;

CREATE TABLE IF NOT EXISTS admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS clients (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(190) NOT NULL UNIQUE,
  phone VARCHAR(30) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  is_verified TINYINT(1) DEFAULT 0,
  verification_code VARCHAR(10) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS services (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS appointments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  client_id INT NOT NULL,
  service_id INT NOT NULL,
  appointment_at DATETIME NOT NULL,
  payment_amount DECIMAL(10,2) NOT NULL,
  payment_status ENUM('pending','paid') DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
  FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS site_settings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  setting_key VARCHAR(100) UNIQUE NOT NULL,
  setting_value TEXT NULL
);

INSERT INTO site_settings (setting_key, setting_value)
VALUES ('home_notice', 'Reserva con anticipación para asegurar tu horario.')
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);

INSERT INTO admins (email, password_hash)
VALUES ('admin@salon.com', '$2y$10$2F8GK6pE7XJ4Ja.2XBXVfOUVhDQNX3z4fD6Qod2f4nQ9VFwY8ahz2')
ON DUPLICATE KEY UPDATE email = VALUES(email);

INSERT INTO services (name, price)
VALUES
('Corte de cabello', 35.00),
('Tinte profesional', 60.00),
('Manicure spa', 30.00)
ON DUPLICATE KEY UPDATE price = VALUES(price);

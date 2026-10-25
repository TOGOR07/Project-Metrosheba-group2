-- MetroSheba - Online Metro Ticketing Platform
-- Database schema + seed data
--
-- Import:  mysql -u root -p < database/metrosheba.sql
-- or in phpMyAdmin: Import -> choose this file.

DROP DATABASE IF EXISTS metrosheba;
CREATE DATABASE metrosheba DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE metrosheba;

-- ---------------------------------------------------------------
-- users
-- ---------------------------------------------------------------
CREATE TABLE users (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    name          VARCHAR(100)  NOT NULL,
    email         VARCHAR(150)  NOT NULL,
    mobile_number VARCHAR(20)   NOT NULL,
    dob           DATETIME      DEFAULT NULL,
    nid           VARCHAR(30)   NOT NULL,
    gender        VARCHAR(10)   NOT NULL,
    password      VARCHAR(255)  NOT NULL,
    otp           VARCHAR(6)    DEFAULT NULL,
    profile_image VARCHAR(255)  DEFAULT NULL,
    created_at    TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_users_email (email),
    UNIQUE KEY uq_users_name  (name),
    KEY idx_users_mobile (mobile_number)
) ENGINE = InnoDB;

-- ---------------------------------------------------------------
-- metroshebatickets
-- `username` holds users.name so a ticket keeps the buyer's name
-- even if the account row is later removed by an admin.
-- ---------------------------------------------------------------
CREATE TABLE metroshebatickets (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    username      VARCHAR(100) NOT NULL,
    from_station  VARCHAR(60)  NOT NULL,
    to_station    VARCHAR(60)  NOT NULL,
    quantity      INT          NOT NULL DEFAULT 1,
    per_price     INT          NOT NULL DEFAULT 0,
    total_price   INT          NOT NULL DEFAULT 0,
    purchase_date DATETIME     NOT NULL,
    status        VARCHAR(20)  NOT NULL DEFAULT 'active',
    KEY idx_tickets_username (username),
    KEY idx_tickets_date (purchase_date)
) ENGINE = InnoDB;

-- ---------------------------------------------------------------
-- Seed: one demo passenger. Password is "passenger123".
-- The admin account is not a row here; it is checked in
-- controllers/loginvalidation.php (admin@gmail.com / admin).
-- ---------------------------------------------------------------
INSERT INTO users (name, email, mobile_number, dob, nid, gender, password, profile_image)
VALUES (
    'Demo Passenger',
    'passenger@gmail.com',
    '01712345678',
    '2000-01-01 00:00:00',
    '1234567890',
    'Male',
    '$2y$10$/Ymjyj1tbn0UVbtjVZm0WeDokppy2wHQNWExZjjQculH7HOt7I0GO',
    NULL
);

INSERT INTO metroshebatickets (username, from_station, to_station, quantity, per_price, total_price, purchase_date)
VALUES
    ('Demo Passenger', 'Uttara North', 'Agargaon', 2, 80, 160, NOW() - INTERVAL 3 DAY),
    ('Demo Passenger', 'Agargaon', 'Motijheel', 1, 60, 60, NOW() - INTERVAL 1 DAY);

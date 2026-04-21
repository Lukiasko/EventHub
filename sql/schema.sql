

CREATE DATABASE IF NOT EXISTS eventhub
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE eventhub;

SET NAMES utf8mb4;

-- Tabuľky sa mažú v poradí podľa závislostí, aby bolo možné schému
-- opakovane importovať počas lokálneho vývoja.
DROP TABLE IF EXISTS contact_messages;
DROP TABLE IF EXISTS events;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS admins;

-- Administrátori pre chránenú administračnú časť.
-- Heslá sú uložené ako hashe vytvorené funkciou password_hash().
CREATE TABLE admins (
    id INT UNSIGNED AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    UNIQUE KEY idx_admins_username (username)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- Kategórie podujatí. Jedna kategória môže obsahovať viac podujatí.
CREATE TABLE categories (
    id INT UNSIGNED AUTO_INCREMENT,
    name VARCHAR(120) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    UNIQUE KEY idx_categories_name (name)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- Podujatia zobrazované na verejnej časti webu.
-- Stĺpec category_id je voliteľný, pretože ON DELETE SET NULL zachová
-- podujatie aj po odstránení jeho kategórie.
CREATE TABLE events (
    id INT UNSIGNED AUTO_INCREMENT,
    category_id INT UNSIGNED NULL,
    title VARCHAR(180) NOT NULL,
    description TEXT NOT NULL,
    location VARCHAR(180) NOT NULL,
    event_date DATETIME NOT NULL,
    image VARCHAR(500) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    KEY idx_events_category_id (category_id),
    KEY idx_events_event_date (event_date),

    CONSTRAINT fk_events_category
        FOREIGN KEY (category_id)
        REFERENCES categories (id)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- Správy odoslané cez kontaktný formulár.
CREATE TABLE contact_messages (
    id INT UNSIGNED AUTO_INCREMENT,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(180) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    KEY idx_contact_messages_email (email),
    KEY idx_contact_messages_created_at (created_at)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

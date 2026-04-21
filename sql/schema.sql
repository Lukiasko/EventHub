

CREATE DATABASE IF NOT EXISTS eventhub
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE eventhub;

SET NAMES utf8mb4;

-- Tables are dropped in dependency order so the schema can be re-imported
-- easily during development in XAMPP/phpMyAdmin.
DROP TABLE IF EXISTS contact_messages;
DROP TABLE IF EXISTS events;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS admins;

-- Administrators for protected admin area.
-- Passwords must be stored as hashes created by PHP password_hash().
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

-- Event categories. One category can contain many events.
CREATE TABLE categories (
    id INT UNSIGNED AUTO_INCREMENT,
    name VARCHAR(120) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    UNIQUE KEY idx_categories_name (name)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- Events shown on the public website.
-- category_id is nullable because ON DELETE SET NULL keeps the event
-- even if its category is deleted.
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

-- Messages submitted from the contact form.
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

CREATE DATABASE IF NOT EXISTS eventhub
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE eventhub;

SET NAMES utf8mb4;

-- Remove tables in dependency order so the file can be imported repeatedly.
DROP TABLE IF EXISTS event_registrations;
DROP TABLE IF EXISTS contact_messages;
DROP TABLE IF EXISTS events;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS admins;

-- Administrators for the protected admin area.
-- Passwords are stored as hashes created with password_hash().
CREATE TABLE admins (
    id INT UNSIGNED AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    UNIQUE KEY idx_admins_username (username)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- Public user accounts.
-- Passwords are stored as hashes created with password_hash().
CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(180) NOT NULL,
    nickname VARCHAR(100) NULL,
    avatar VARCHAR(255) NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    UNIQUE KEY idx_users_username (username),
    UNIQUE KEY idx_users_email (email)
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

-- Public events displayed on the website.
-- category_id is nullable because ON DELETE SET NULL keeps the event if the category is removed.
CREATE TABLE events (
    id INT UNSIGNED AUTO_INCREMENT,
    category_id INT UNSIGNED NULL,
    title VARCHAR(180) NOT NULL,
    description TEXT NOT NULL,
    location VARCHAR(180) NOT NULL,
    event_date DATETIME NOT NULL,
    image VARCHAR(500) NULL,
    is_featured TINYINT(1) NOT NULL DEFAULT 0,
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

-- Registrations of users for events without payment.
CREATE TABLE event_registrations (
    id INT UNSIGNED AUTO_INCREMENT,
    user_id INT UNSIGNED NOT NULL,
    event_id INT UNSIGNED NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    UNIQUE KEY idx_event_registrations_user_event (user_id, event_id),
    KEY idx_event_registrations_user_id (user_id),
    KEY idx_event_registrations_event_id (event_id),

    CONSTRAINT fk_event_registrations_user
        FOREIGN KEY (user_id)
        REFERENCES users (id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT fk_event_registrations_event
        FOREIGN KEY (event_id)
        REFERENCES events (id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- Contact form messages.
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

-- Default admin account.
-- Username: admin
-- Password: admin123
INSERT INTO admins (username, password)
VALUES
    ('admin', '$2y$10$Hj0lZO7sxJs3GTEr7daUu.BP2Puuvzc.tK3.Zdr/TyGfrehTRYiiW');

-- Default user account.
-- Username: user
-- Email: user@example.com
-- Password: password
INSERT INTO users (username, email, nickname, avatar, password)
VALUES
    ('user', 'user@example.com', 'EventFan', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Base categories.
INSERT INTO categories (name)
VALUES
    ('Koncerty'),
    ('Šport'),
    ('Konferencie'),
    ('Festivaly'),
    ('Workshopy');

-- Public sample events.
INSERT INTO events (category_id, title, description, location, event_date, image)
VALUES
    (
        (SELECT id FROM categories WHERE name = 'Koncerty'),
        'Jarný mestský koncert',
        'Večer plný živej hudby, lokálnych interpretov a príjemnej atmosféry v centre mesta.',
        'Hlavné námestie, Bratislava',
        '2026-05-15 19:00:00',
        'https://images.unsplash.com/photo-1501386761578-eac5c94b800a?auto=format&fit=crop&w=1200&q=80'
    ),
    (
        (SELECT id FROM categories WHERE name = 'Šport'),
        'Nočný beh mestom',
        'Komunitné športové podujatie pre rekreačných aj pokročilých bežcov so štartom v centre mesta.',
        'Námestie SNP, Banská Bystrica',
        '2026-05-28 20:30:00',
        'https://images.unsplash.com/photo-1476480862126-209bfaa8edc8?auto=format&fit=crop&w=1200&q=80'
    ),
    (
        (SELECT id FROM categories WHERE name = 'Konferencie'),
        'Tech konferencia 2026',
        'Celodenné stretnutie vývojárov, dizajnérov a manažérov tímov s prednáškami o modernom webe.',
        'Kultúrne centrum, Žilina',
        '2026-09-24 09:00:00',
        'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=1200&q=80'
    ),
    (
        (SELECT id FROM categories WHERE name = 'Festivaly'),
        'Letný food festival',
        'Festival dobrého jedla, lokálnych predajcov a sprievodného programu pre rodiny aj priateľov.',
        'Mestský park, Nitra',
        '2026-07-18 11:00:00',
        'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&w=1200&q=80'
    ),
    (
        (SELECT id FROM categories WHERE name = 'Workshopy'),
        'PHP workshop pre začiatočníkov',
        'Praktický workshop zameraný na základy PHP, prácu s formulármi a bezpečné pripojenie k databáze cez PDO.',
        'Coworking Centrum, Košice',
        '2026-06-03 16:30:00',
        'https://images.unsplash.com/photo-1517048676732-d65bc937f952?auto=format&fit=crop&w=1200&q=80'
    );

-- Sample registrations for the default user.
INSERT INTO event_registrations (user_id, event_id)
VALUES
    (
        (SELECT id FROM users WHERE username = 'user'),
        (SELECT id FROM events WHERE title = 'Jarný mestský koncert')
    ),
    (
        (SELECT id FROM users WHERE username = 'user'),
        (SELECT id FROM events WHERE title = 'PHP workshop pre začiatočníkov')
    );

-- Example contact message.
INSERT INTO contact_messages (name, email, message)
VALUES
    ('Jana Nováková', 'jana@example.com', 'Dobrý deň, chcela by som sa informovať o možnosti pridania vlastného podujatia.');



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
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS admins;

-- Administrátori pre chránenú administračnú časť.
-- Heslá sú uložené ako hashe vytvorené funkciou password_hash().
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

-- Používateľské účty pre verejnú časť webu.
-- Heslá sú uložené ako hashe vytvorené funkciou password_hash().
CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(180) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    UNIQUE KEY idx_users_username (username),
    UNIQUE KEY idx_users_email (email)
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

-- Predvolený administrátor:
-- používateľské meno: admin
-- heslo: admin123
INSERT INTO admins (username, password)
VALUES
    ('admin', '$2y$10$Hj0lZO7sxJs3GTEr7daUu.BP2Puuvzc.tK3.Zdr/TyGfrehTRYiiW');

-- Predvolený používateľ:
-- používateľské meno: user
-- email: user@example.com
-- heslo: password
INSERT INTO users (username, email, password)
VALUES
    ('user', 'user@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Základné kategórie podujatí.
INSERT INTO categories (name)
VALUES
    ('Koncerty'),
    ('Šport'),
    ('Konferencie'),
    ('Festivaly'),
    ('Workshopy');

-- Podujatia pre verejnú časť webu.
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

-- Kontaktná správa zobrazená v administrácii.
INSERT INTO contact_messages (name, email, message)
VALUES
    ('Jana Nováková', 'jana@example.com', 'Dobrý deň, chcela by som sa informovať o možnosti pridania vlastného podujatia.');

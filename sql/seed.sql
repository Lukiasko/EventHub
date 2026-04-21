-- Dáta pre prvé spustenie aplikácie.
-- Tento súbor importujte až po vytvorení schémy.

USE eventhub;

SET NAMES utf8mb4;

-- Predvolený administrátor:
-- používateľské meno: admin
-- heslo: admin123
-- Hodnota nižšie je bcrypt hash kompatibilný s funkciou password_verify().
-- Pri zmene hesla vytvorte nový hash pomocou:
-- echo password_hash('admin123', PASSWORD_DEFAULT);
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

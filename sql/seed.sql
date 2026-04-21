USE eventhub;

-- Predvolený administrátor:
-- používateľské meno: admin
-- heslo: password
-- V databáze je uložený iba hash hesla vytvorený cez password_hash().

INSERT INTO admins (username, password)
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi')
ON DUPLICATE KEY UPDATE username = username;

INSERT INTO categories (name) VALUES
    ('Koncerty'),
    ('Workshopy'),
    ('Konferencie'),
    ('Komunitné stretnutia')
ON DUPLICATE KEY UPDATE name = VALUES(name);

INSERT INTO events (category_id, title, description, location, event_date, image)
SELECT c.id,
       'Jarný mestský koncert',
       'Večer plný živej hudby, lokálnych interpretov a príjemnej atmosféry v centre mesta.',
       'Hlavné námestie, Bratislava',
       '2026-05-15 19:00:00',
       'https://images.unsplash.com/photo-1501386761578-eac5c94b800a?auto=format&fit=crop&w=1200&q=80'
FROM categories c
WHERE c.name = 'Koncerty'
AND NOT EXISTS (SELECT 1 FROM events WHERE title = 'Jarný mestský koncert');

INSERT INTO events (category_id, title, description, location, event_date, image)
SELECT c.id,
       'PHP workshop pre začiatočníkov',
       'Praktický workshop zameraný na základy PHP, prácu s formulármi a bezpečné pripojenie k databáze cez PDO.',
       'Coworking Centrum, Košice',
       '2026-06-03 16:30:00',
       'https://images.unsplash.com/photo-1517048676732-d65bc937f952?auto=format&fit=crop&w=1200&q=80'
FROM categories c
WHERE c.name = 'Workshopy'
AND NOT EXISTS (SELECT 1 FROM events WHERE title = 'PHP workshop pre začiatočníkov');

INSERT INTO events (category_id, title, description, location, event_date, image)
SELECT c.id,
       'Tech konferencia 2026',
       'Celodenné stretnutie vývojárov, dizajnérov a projektových manažérov s prednáškami o modernom webe.',
       'Kultúrne centrum, Žilina',
       '2026-09-24 09:00:00',
       'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=1200&q=80'
FROM categories c
WHERE c.name = 'Konferencie'
AND NOT EXISTS (SELECT 1 FROM events WHERE title = 'Tech konferencia 2026');

INSERT INTO events (category_id, title, description, location, event_date, image)
SELECT c.id,
       'Večer lokálnej komunity',
       'Neformálne stretnutie ľudí z okolia, ktoré podporuje nové kontakty, nápady a spoluprácu.',
       'Mestská knižnica, Trnava',
       '2026-07-10 18:00:00',
       'https://images.unsplash.com/photo-1528605248644-14dd04022da1?auto=format&fit=crop&w=1200&q=80'
FROM categories c
WHERE c.name = 'Komunitné stretnutia'
AND NOT EXISTS (SELECT 1 FROM events WHERE title = 'Večer lokálnej komunity');

# EventHub

EventHub je školský semester projekt v čistom PHP. Aplikácia slúži ako moderný web pre zobrazovanie podujatí a obsahuje chránenú administráciu pre správu podujatí a kategórií.

## Požiadavky

- PHP 8.0 alebo novšie
- MySQL 8.0 alebo MariaDB 10.5 alebo novšie
- Web server s podporou PHP, napríklad Apache cez XAMPP alebo Laragon
- Zapnuté rozšírenie PDO MySQL

## Inštalácia

1. Skopírujte projekt do priečinka web servera, napríklad `htdocs/EventHub`.
2. Vytvorte databázu a tabuľky spustením súboru `sql/schema.sql`.
3. Vložte ukážkové dáta spustením súboru `sql/seed.sql`.
4. V súbore `config/config.php` nastavte `DB_HOST`, `DB_NAME`, `DB_USER` a `DB_PASS` podľa lokálneho prostredia.
5. Otvorte projekt v prehliadači cez `http://localhost/EventHub/index.php`.

## Databáza

Súbor `sql/schema.sql` vytvorí databázu `eventhub` a tabuľky:

- `admins` pre administrátorov
- `categories` pre kategórie podujatí
- `events` pre podujatia
- `contact_messages` pre správy z kontaktného formulára

Súbor `sql/seed.sql` vloží predvolené kategórie, ukážkové podujatia a jedného administrátora.

## Predvolené prihlásenie

- Používateľské meno: `admin`
- Heslo: `password`

Heslo je v databáze uložené iba ako hash kompatibilný s `password_verify()`. Po prezentácii alebo nasadení je vhodné heslo zmeniť.

## Hlavné trasy

Projekt používa jednoduchý front controller v `index.php` a whitelist podľa query parametra `page`.

- `index.php?page=home`
- `index.php?page=events`
- `index.php?page=event_detail&id=1`
- `index.php?page=contact`
- `index.php?page=login`
- `index.php?page=logout`
- `index.php?page=admin_dashboard`
- `index.php?page=admin_events`
- `index.php?page=admin_event_create`
- `index.php?page=admin_event_edit&id=1`
- `index.php?page=admin_event_delete&id=1`
- `index.php?page=admin_categories`
- `index.php?page=admin_category_create`
- `index.php?page=admin_category_edit&id=1`
- `index.php?page=admin_category_delete&id=1`

## Štruktúra projektu

- `index.php` je vstupný bod aplikácie a obsahuje jednoduché whitelist routovanie.
- `config/` obsahuje konfiguráciu a PDO pripojenie k databáze.
- `core/` obsahuje základné triedy pre controller, model, session, autentifikáciu a pomocné funkcie.
- `controllers/` obsahuje logiku verejných stránok aj administrácie.
- `models/` obsahuje databázové operácie nad tabuľkami.
- `views/` obsahuje verejné a administračné šablóny.
- `public/css/` obsahuje štýly pre verejný web a administráciu.
- `public/js/` obsahuje jednoduchý JavaScript pre mobilnú navigáciu a hlášky.
- `sql/` obsahuje SQL schému a seed dáta.

## Bezpečnosť

- Administrácia je chránená cez sessions.
- Prihlásenie používa `password_verify()`.
- Heslo v seede je uložené ako hash.
- Všetky databázové dotazy používajú PDO prepared statements.
- Výstup vo view súboroch je escapovaný cez `htmlspecialchars()`.
- Formuláre používajú jednoduchý CSRF token.
- Admin stránky sa nedajú načítať bez prihlásenia.

## CRUD funkcionalita

Administrácia obsahuje kompletné CRUD operácie pre:

- podujatia
- kategórie

Kontaktný formulár je doplnková funkcia. Správy sa ukladajú do databázy a zobrazujú sa na dashboarde.

## Návrh postupných Git commitov

1. `initial project structure`
2. `database schema and seed data added`
3. `public pages and event listing added`
4. `admin login with sessions added`
5. `categories CRUD added`
6. `events CRUD added`
7. `contact form and dashboard summary added`
8. `final styling and fixes`

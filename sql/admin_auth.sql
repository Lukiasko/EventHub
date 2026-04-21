CREATE TABLE IF NOT EXISTS admins (
    id INT UNSIGNED AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    UNIQUE KEY idx_admins_username (username)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

INSERT INTO admins (username, password)
VALUES
    ('admin', '$2y$10$Hj0lZO7sxJs3GTEr7daUu.BP2Puuvzc.tK3.Zdr/TyGfrehTRYiiW')
ON DUPLICATE KEY UPDATE username = username;

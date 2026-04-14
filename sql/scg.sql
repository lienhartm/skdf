--
-- CREATE DATABASE IF NOT EXISTS scg CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
--

--
-- USE scg;
--

--
-- Table Administrateur
--
CREATE TABLE IF NOT EXISTS admin (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    courriel VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL -- Hash du mot de passe
);

-- Insertion admin par défaut (user: admin / pass: admin)
-- Le hash ci-dessous correspond à "admin" hashé avec password_hash() par défaut (BCRYPT)
INSERT IGNORE INTO admin (username, password) VALUES ('admin', '$2y$10$8RBUa7UleZDeJJG.cDjEne2NkMsuAAfRCTCzCMZCXnb2ESohoARga');

--
--
--

--
-- Table des newsletter
--

CREATE TABLE IF NOT EXISTS newsletter (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    date_inscription DATETIME NOT NULL
);

--
--
-- insertion newsletter par défaut
INSERT INTO newsletter (nom, prenom, email, date_inscription) VALUES
('Dupont', 'Jean', 'jean.dupont@example.com', '2026-04-13 08:05:00'),
('Lefebvre', 'Marie', 'marie.lefebvre@test.fr', '2026-04-13 08:10:00'),
('Petit', 'Thomas', 't.petit@gmail.com', '2026-04-13 08:15:00'),
('Roux', 'Julie', 'julie.roux@outlook.com', '2026-04-13 08:20:00'),
('Durand', 'Nicolas', 'durand.nicolas@free.fr', '2026-04-13 08:25:00'),
('Morel', 'Sophie', 'sophie.morel@orange.fr', '2026-04-13 08:30:00'),
('Fournier', 'Lucas', 'lucas.fournier@sfr.fr', '2026-04-13 08:35:00'),
('Girard', 'Emma', 'emma.girard@yahoo.fr', '2026-04-13 08:40:00'),
('Andre', 'Clara', 'andre.clara@proton.me', '2026-04-13 08:45:00'),
('Mercier', 'Paul', 'paul.mercier@laposte.net', '2026-04-13 08:50:00');

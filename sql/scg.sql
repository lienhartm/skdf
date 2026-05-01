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
    username VARCHAR(50) DEFAULT NULL, -- 0: admin, 1: member, null: conscrit
    courriel int NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL -- Hash du mot de passe
);

-- Insertion admin par défaut (user: admin / pass: admin)
-- Le hash ci-dessous correspond à "admin" hashé avec password_hash() par défaut (BCRYPT)
-- username is status 'admin' : 0 | 'member' : 1 | 'null'
INSERT IGNORE INTO admin (username, courriel, password) VALUES 
(0, 1, '$2y$10$8RBUa7UleZDeJJG.cDjEne2NkMsuAAfRCTCzCMZCXnb2ESohoARga'),
(1, 2, '$2y$10$8RBUa7UleZDeJJG.cDjEne2NkMsuAAfRCTCzCMZCXnb2ESohoARga'),
(NULL, 3, '$2y$10$8RBUa7UleZDeJJG.cDjEne2NkMsuAAfRCTCzCMZCXnb2ESohoARga'),
(NULL, 4, '$2y$10$8RBUa7UleZDeJJG.cDjEne2NkMsuAAfRCTCzCMZCXnb2ESohoARga'),
(NULL, 5, '$2y$10$8RBUa7UleZDeJJG.cDjEne2NkMsuAAfRCTCzCMZCXnb2ESohoARga'),
(NULL, 6, '$2y$10$8RBUa7UleZDeJJG.cDjEne2NkMsuAAfRCTCzCMZCXnb2ESohoARga'),
(NULL, 7, '$2y$10$8RBUa7UleZDeJJG.cDjEne2NkMsuAAfRCTCzCMZCXnb2ESohoARga'),
(NULL, 8, '$2y$10$8RBUa7UleZDeJJG.cDjEne2NkMsuAAfRCTCzCMZCXnb2ESohoARga'),
(NULL, 9, '$2y$10$8RBUa7UleZDeJJG.cDjEne2NkMsuAAfRCTCzCMZCXnb2ESohoARga'),
(NULL, 10, '$2y$10$8RBUa7UleZDeJJG.cDjEne2NkMsuAAfRCTCzCMZCXnb2ESohoARga'),
(NULL, 11, '$2y$10$8RBUa7UleZDeJJG.cDjEne2NkMsuAAfRCTCzCMZCXnb2ESohoARga'),
(NULL, 12, '$2y$10$8RBUa7UleZDeJJG.cDjEne2NkMsuAAfRCTCzCMZCXnb2ESohoARga'),
(NULL, 13, '$2y$10$8RBUa7UleZDeJJG.cDjEne2NkMsuAAfRCTCzCMZCXnb2ESohoARga'),
(NULL, 14, '$2y$10$8RBUa7UleZDeJJG.cDjEne2NkMsuAAfRCTCzCMZCXnb2ESohoARga'),
(NULL, 15, '$2y$10$8RBUa7UleZDeJJG.cDjEne2NkMsuAAfRCTCzCMZCXnb2ESohoARga');

--
--
--

--
-- Table des newsletter
--

CREATE TABLE IF NOT EXISTS newsletter (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE
);


--
--
-- insertion newsletter par défaut
INSERT INTO newsletter (email) VALUES
('lienhartm8@gmail.com'),
('jean.dupont@example.com'),
('marie.lefebvre@test.fr'),
('t.petit@gmail.com'),
('julie.roux@outlook.com'),
('durand.nicolas@free.fr'),
('sophie.morel@orange.fr'),
('lucas.fournier@sfr.fr'),
('emma.girard@yahoo.fr'),
('andre.clara@proton.me'),
('paul.mercier@laposte.net'),
('marie.martin@testmail.com'),
('paul.durand@mail.fr'),
('lucie.bernard@demo.org'),
('antoine.petit@sample.net');

--
--
--

--
-- Table des conscrits
--

CREATE TABLE IF NOT EXISTS conscrit (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    gardien BOOLEAN NOT NULL DEFAULT FALSE,
    membre BOOLEAN NOT NULL DEFAULT FALSE,
    newsletter BOOLEAN NOT NULL DEFAULT TRUE,
    date_inscription DATETIME NOT NULL
);

--
--
-- insertion conscrits par défaut

INSERT INTO conscrit (nom, prenom, gardien, newsletter, date_inscription) VALUES
('Lienhart', 'Mathieu', TRUE, TRUE, '2023-01-10 09:00:00'),
('Dupont', 'Jean', FALSE, TRUE, '2023-01-15 10:30:00'),
('Lefebvre', 'Marie', TRUE, FALSE, '2023-02-20 14:45:00'),
('Petit', 'Thomas', FALSE, TRUE, '2023-03-05 09:15:00'),
('Roux', 'Julie', TRUE, TRUE, '2023-04-10 16:00:00'),
('Durand', 'Nicolas', FALSE, FALSE, '2023-05-25 11:20:00'),
('Morel', 'Sophie', TRUE, FALSE, '2023-06-30 13:50:00'),
('Fournier', 'Lucas', FALSE, TRUE, '2023-07-18 08:40:00'),
('Girard', 'Emma', TRUE, TRUE, '2023-08-22 17:30:00'),
('Clara', 'André', FALSE, FALSE, '2023-09-12 12:10:00'),
('Mercier', 'Paul', TRUE, FALSE, '2023-10-05 15:25:00');

--
--
--

--
-- Table de liaison entre conscrits et newsletter
--

CREATE TABLE IF NOT EXISTS conscrit_news (
    conscrit_id INT NOT NULL,
    newsletter_id INT NOT NULL,
    PRIMARY KEY (conscrit_id, newsletter_id),
    FOREIGN KEY (conscrit_id) REFERENCES conscrit(id) ON DELETE CASCADE,
    FOREIGN KEY (newsletter_id) REFERENCES newsletter(id) ON DELETE CASCADE
);

--
--
-- insertion conscrit_news par défaut
INSERT INTO conscrit_news (conscrit_id, newsletter_id) VALUES
(1, 1),
(2, 2),
(3, 4),
(4, 5),
(5, 8),
(6, 9),
(7, 10),
(8, 11),
(9, 13),
(10, 14),
(11, 15);
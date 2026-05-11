DROP DATABASE IF EXISTS regime;
CREATE DATABASE regime;
USE regime;

-- ============================================
-- TABLE USERS
-- ============================================
CREATE TABLE users (
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    nom                 VARCHAR(100) NOT NULL,
    email               VARCHAR(100) NOT NULL UNIQUE,
    username            VARCHAR(100) NOT NULL UNIQUE,
    password_hash       VARCHAR(255) NOT NULL,
    genre               ENUM('M', 'F') NOT NULL,
    taille              DECIMAL(3, 2) NOT NULL,
    poids               DECIMAL(5, 2) NOT NULL,
    imc                 DECIMAL(5, 2) GENERATED ALWAYS AS (poids / (taille * taille)) STORED,
    solde_portefeuille  DECIMAL(10, 2) DEFAULT 0.00,
    is_gold             BOOLEAN DEFAULT FALSE,
    role                ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    created_at          DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at          DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================
-- TABLE REGIMES
-- ============================================
CREATE TABLE regimes (
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    nom                 VARCHAR(255) NOT NULL UNIQUE,
    description         TEXT,
    type                ENUM('perte', 'prise', 'maintien') NOT NULL,
    duree_jours         INT NOT NULL,
    prix                DECIMAL(8, 2) NOT NULL,
    poids_variation_min DECIMAL(5, 2) NOT NULL,
    poids_variation_max DECIMAL(5, 2) NOT NULL,
    pourcentage_viande  INT NOT NULL DEFAULT 0,
    pourcentage_poisson INT NOT NULL DEFAULT 0,
    pourcentage_volaille INT NOT NULL DEFAULT 0,
    created_at          DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at          DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================
-- TABLE REGIME_TARIFS
-- ============================================
CREATE TABLE regime_tarifs (
    id                      INT AUTO_INCREMENT PRIMARY KEY,
    regime_id               INT NOT NULL,
    duree_jours             INT NOT NULL,
    prix                    DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    reduction_pourcentage   INT NOT NULL DEFAULT 0,
    created_at              DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at              DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (regime_id) REFERENCES regimes(id) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE KEY unique_regime_duree (regime_id, duree_jours)
);

-- ============================================
-- TABLE ACTIVITES_SPORTIVES
-- ============================================
CREATE TABLE activites_sportives (
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    nom                 VARCHAR(255) NOT NULL UNIQUE,
    description         TEXT,
    type                ENUM('cardio', 'musculation', 'yoga', 'autre') NOT NULL,
    intensite           ENUM('basse', 'moyenne', 'haute') NOT NULL,
    duree_jours         INT NOT NULL,
    calories_brulees    INT NOT NULL,
    prix                DECIMAL(8, 2) NOT NULL DEFAULT 0.00,
    created_at          DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at          DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================
-- TABLE CODES_PORTEFEUILLE
-- ============================================
CREATE TABLE codes_portefeuille (
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    code                VARCHAR(50) NOT NULL UNIQUE,
    montant             DECIMAL(8, 2) NOT NULL,
    utilisateur_id      INT NULL,
    date_utilisation    DATETIME NULL,
    valide              BOOLEAN DEFAULT FALSE,
    created_at          DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at          DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (utilisateur_id) REFERENCES users(id) ON DELETE SET NULL
);

-- ============================================
-- TABLE OBJECTIFS
-- ============================================
CREATE TABLE objectifs (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nom         VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- TABLE USER_OBJECTIFS
-- ============================================
CREATE TABLE user_objectifs (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT NOT NULL,
    objectif_id INT NOT NULL,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (objectif_id) REFERENCES objectifs(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_objectif (user_id, objectif_id)
);

-- ============================================
-- TABLE USER_REGIMES
-- ============================================
CREATE TABLE user_regimes (
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    user_id             INT NOT NULL,
    regime_id           INT NOT NULL,
    prix_paye           DECIMAL(8, 2) NOT NULL,
    date_selection      DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_fin_prevu      DATETIME,
    statut              ENUM('actif', 'termine', 'annule') DEFAULT 'actif',
    created_at          DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (regime_id) REFERENCES regimes(id) ON DELETE RESTRICT
);

-- ============================================
-- TABLE USER_ACTIVITES
-- ============================================
CREATE TABLE user_activites (
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    user_id             INT NOT NULL,
    activite_id         INT NOT NULL,
    prix_paye           DECIMAL(8, 2) NOT NULL DEFAULT 0.00,
    date_selection      DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_fin_prevu      DATETIME,
    statut              ENUM('actif', 'termine', 'annule') DEFAULT 'actif',
    created_at          DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (activite_id) REFERENCES activites_sportives(id) ON DELETE RESTRICT
);

-- ============================================
-- TABLE PARAMETRES
-- ============================================
CREATE TABLE parametres (
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    cle                 VARCHAR(100) NOT NULL UNIQUE,
    valeur              TEXT NOT NULL,
    description         VARCHAR(255),
    created_at          DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at          DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================
-- TABLE MIGRATIONS (CodeIgniter)
-- ============================================
CREATE TABLE migrations (
    id          BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    version     VARCHAR(255) NOT NULL,
    class       VARCHAR(255) NOT NULL,
    `group`     VARCHAR(255) NOT NULL,
    namespace   VARCHAR(255) NOT NULL,
    time        INT(11) NOT NULL,
    batch       INT(11) UNSIGNED NOT NULL
);

-- ============================================
-- INSERT: OBJECTIFS
-- ============================================
INSERT INTO objectifs (nom, description) VALUES
('Augmenter son poids', 'Prendre du poids de manière saine et progressive'),
('Réduire son poids', 'Perdre du poids progressivement et durablement'),
('Atteindre son IMC idéal', 'Atteindre un indice de masse corporelle optimal');

-- ============================================
-- INSERT: USERS (5 utilisateurs)
-- ============================================
INSERT INTO users (nom, email, username, password_hash, genre, taille, poids, solde_portefeuille, is_gold, role, created_at, updated_at) VALUES
-- Admin (password: admin123)
('Admin System', 'admin@RegimeApp.com', 'admin', '$2y$10$OByBK98ecciq3w2KiK2M4OsgFKOjPDrYd1ec9XLGUOq0u/cyv3CIG', 'M', 1.75, 80.00, 1000.00, FALSE, 'admin', NOW(), NOW()),

-- Users
('Alice Martin', 'alice@email.com', 'alice', '$2y$10$qg4R2wVsj1z90R32TtarM.S6GbYp23yCx5X1wDz3hcYx5ui5QOEby', 'F', 1.65, 72.00, 50.00, FALSE, 'user', NOW(), NOW()),
('Bob Dupont', 'bob@email.com', 'bob', '$2y$10$abnchtixaNVEM9iAVgcP6.t0NfouAHul6T.kSsFlFi6SagbWUGP1q', 'M', 1.80, 95.00, 75.00, TRUE, 'user', NOW(), NOW()),
('Carole Michel', 'carole@email.com', 'carole', '$2y$10$4zV6VLCd/gJRMvRoR02CJ.b1uWEgCR/DGsVUabBD0wgCQjZI8bNqq', 'F', 1.60, 68.00, 100.00, FALSE, 'user', NOW(), NOW()),
('David Leblanc', 'david@email.com', 'david', '$2y$10$QuGDyQP3dSJm.VzlYpuxyO4a9p3PnFKK9DqoTV9enauOyjWaYs2ly', 'M', 1.78, 110.00, 35.50, TRUE, 'user', NOW(), NOW());

-- ============================================
-- INSERT: REGIMES (5 régimes)
-- ============================================
INSERT INTO regimes (nom, description, type, duree_jours, prix, poids_variation_min, poids_variation_max, pourcentage_viande, pourcentage_poisson, pourcentage_volaille) VALUES
('Perte Douce', 'Régime léger et progressif, idéal pour débuter sans restriction forte. Parfait pour une perte de poids graduelle et durable.', 'perte', 30, 12.00, -2.00, -0.50, 25, 35, 40),
('Perte Intensive', 'Régime protéiné à forte restriction calorique pour résultats rapides. À suivre avec discipline et suivi régulier.', 'perte', 30, 18.00, -4.00, -2.50, 20, 40, 40),
('Maintien Sain', 'Régime équilibré pour stabiliser votre poids sans variation significative. Idéal après avoir atteint votre objectif.', 'maintien', 60, 20.00, -0.50, 0.50, 30, 30, 40),
('Prise Musclée', 'Régime hypercalorique riche en protéines pour la construction musculaire. Parfait en combinaison avec la musculation.', 'prise', 90, 35.00, 3.00, 6.00, 40, 20, 40),
('Rééquilibre', 'Régime équilibré sur long terme pour rééquilibrer progressivement votre poids. Résultats stables et durables.', 'perte', 60, 28.00, -3.00, -1.50, 25, 40, 35);

-- ============================================
-- INSERT: REGIME_TARIFS (prix variables par durée)
-- ============================================
INSERT INTO regime_tarifs (regime_id, duree_jours, prix, reduction_pourcentage) VALUES
(1, 7, 12.00, 0), (1, 14, 22.80, 5), (1, 30, 46.29, 10), (1, 90, 131.14, 15),
(2, 7, 18.00, 0), (2, 14, 34.20, 5), (2, 30, 69.43, 10), (2, 90, 196.71, 15),
(3, 7, 20.00, 0), (3, 14, 38.00, 5), (3, 30, 77.14, 10), (3, 90, 218.57, 15),
(4, 7, 35.00, 0), (4, 14, 66.50, 5), (4, 30, 135.00, 10), (4, 90, 382.50, 15),
(5, 7, 28.00, 0), (5, 14, 53.20, 5), (5, 30, 108.00, 10), (5, 90, 306.00, 15);

-- ============================================
-- INSERT: ACTIVITES_SPORTIVES (5 activités)
-- ============================================
INSERT INTO activites_sportives (nom, description, type, intensite, duree_jours, calories_brulees, prix) VALUES
('Course à Pied', 'Courir régulièrement pour brûler des calories rapidement. Excellente pour le cardio et l\'endurance.', 'cardio', 'haute', 30, 350, 15.00),
('Musculation', 'Entraînement progressif pour construire et renforcer la musculature. Idéal pour la tonification.', 'musculation', 'moyenne', 45, 280, 20.00),
('Yoga Détente', 'Séances douces de yoga pour améliorer la flexibilité et la sérénité. Excellent pour la récupération.', 'yoga', 'basse', 60, 150, 12.00),
('HIIT Training', 'Entraînement intensif par intervalle haute/basse intensité. Très efficace pour brûler des calories en peu de temps.', 'cardio', 'haute', 20, 400, 25.00),
('Natation', 'Nage complète et non-traumatisante pour les articulations. Excellent travail cardio et musculaire.', 'cardio', 'moyenne', 45, 320, 18.00);

-- ============================================
-- INSERT: CODES_PORTEFEUILLE (15 codes)
-- ============================================
INSERT INTO codes_portefeuille (code, montant, utilisateur_id, date_utilisation, valide) VALUES
('1252324523', 10.00, 2, NOW(), TRUE),
('1252324524', 15.00, 3, NOW(), TRUE),
('1252324525', 20.00, NULL, NULL, TRUE),
('1252324526', 10.00, NULL, NULL, TRUE),
('1252324527', 25.00, 4, NOW(), TRUE),
('1252324528', 15.00, NULL, NULL, TRUE),
('1252324529', 50.00, NULL, NULL, TRUE),
('1252324530', 50.00, NULL, NULL, TRUE),
('1252324531', 5.00, NULL, NULL, TRUE),
('1252324532', 5.00, NULL, NULL, TRUE),
('1252324533', 5.00, NULL, NULL, TRUE),
('1252324534', 10.00, NULL, NULL, TRUE),
('1252324535', 10.00, NULL, NULL, TRUE),
('1252324536', 30.00, NULL, NULL, TRUE),
('1252324537', 30.00, NULL, NULL, TRUE);

-- ============================================
-- INSERT: USER_OBJECTIFS
-- ============================================
INSERT INTO user_objectifs (user_id, objectif_id) VALUES
(2, 2), -- Alice: Réduire poids
(2, 3), -- Alice: Atteindre IMC idéal
(2, 1), -- Alice: Augmenter poids
(3, 1), -- Bob: Augmenter poids
(3, 3), -- Bob: Atteindre IMC idéal
(3, 2), -- Bob: Réduire poids
(4, 2), -- Carole: Réduire poids
(4, 3), -- Carole: Atteindre IMC idéal
(4, 1), -- Carole: Augmenter poids
(5, 1), -- David: Augmenter poids
(5, 3), -- David: Atteindre IMC idéal
(5, 2); -- David: Réduire poids

-- ============================================
-- INSERT: PARAMETRES (Configuration système)
-- ============================================
INSERT INTO parametres (cle, valeur, description) VALUES
('imc_maigre_max', '18.5', 'Limite maximale pour catégorie maigre'),
('imc_normal_max', '25', 'Limite maximale pour catégorie normal'),
('imc_surpoids_max', '30', 'Limite maximale pour catégorie surpoids'),
('prix_option_gold', '9.99', 'Prix unique pour l\'option Gold'),
('remise_gold_pourcentage', '15', 'Pourcentage de remise Gold sur les régimes'),
('duree_gold_jours', '0', '0 = Illimité, sinon nombre de jours'),
('calories_femme_defaut', '2000', 'Calories recommandées par défaut pour une femme'),
('calories_homme_defaut', '2500', 'Calories recommandées par défaut pour un homme'),
('email_support', 'support@regimeapp.com', 'Email de support de l\'application'),
('nom_app', 'RegimeApp', 'Nom de l\'application');

INSERT INTO migrations (version, class, `group`, namespace, time, batch) VALUES
('2026-05-11-000001', 'App\\Database\\Migrations\\CreateRegimeTarifs', 'default', 'App', UNIX_TIMESTAMP(), 1),
('2026-05-11-000002', 'App\\Database\\Migrations\\HashExistingPasswords', 'default', 'App', UNIX_TIMESTAMP(), 1);

-- ============================================
-- INDEX pour optimisation
-- ============================================
CREATE INDEX idx_user_email ON users(email);
CREATE INDEX idx_user_username ON users(username);
CREATE INDEX idx_regime_type ON regimes(type);
CREATE INDEX idx_code_code ON codes_portefeuille(code);
CREATE INDEX idx_user_regimes_user ON user_regimes(user_id);
CREATE INDEX idx_user_regimes_regime ON user_regimes(regime_id);
CREATE INDEX idx_user_objectifs_user ON user_objectifs(user_id);
CREATE INDEX idx_user_objectifs_objectif ON user_objectifs(objectif_id);

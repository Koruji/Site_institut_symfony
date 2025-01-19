-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : dim. 19 jan. 2025 à 22:21
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `site_institut`
--

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20250115133042', '2025-01-15 13:31:03', 377),
('DoctrineMigrations\\Version20250115133747', '2025-01-15 13:37:54', 33),
('DoctrineMigrations\\Version20250115134921', '2025-01-15 13:49:26', 17),
('DoctrineMigrations\\Version20250115163423', '2025-01-15 16:34:27', 195),
('DoctrineMigrations\\Version20250116090606', '2025-01-16 09:06:15', 219),
('DoctrineMigrations\\Version20250116132124', '2025-01-16 13:21:29', 40),
('DoctrineMigrations\\Version20250116212342', '2025-01-16 21:23:47', 87),
('DoctrineMigrations\\Version20250116212502', '2025-01-16 21:25:06', 82),
('DoctrineMigrations\\Version20250117143351', '2025-01-17 14:33:58', 130),
('DoctrineMigrations\\Version20250117144839', '2025-01-17 14:51:03', 48),
('DoctrineMigrations\\Version20250117150540', '2025-01-17 15:05:43', 44),
('DoctrineMigrations\\Version20250117151236', '2025-01-17 15:12:41', 33),
('DoctrineMigrations\\Version20250118103621', '2025-01-18 10:36:39', 177),
('DoctrineMigrations\\Version20250119214222', '2025-01-19 21:42:33', 194);

-- --------------------------------------------------------

--
-- Structure de la table `matiere`
--

DROP TABLE IF EXISTS `matiere`;
CREATE TABLE IF NOT EXISTS `matiere` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code_matiere` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `libelle` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_9014574AA4D60759` (`libelle`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `matiere`
--

INSERT INTO `matiere` (`id`, `code_matiere`, `libelle`, `description`) VALUES
(9, 'SALCZ', 'Mathématiques', 'Apprenez à maîtriser les nombres, les formules et les théorèmes.'),
(10, 'AZHRN', 'Histoire', 'Tout sur l\'histoire de France et du monde. Ainsi que de la géographie.'),
(11, 'DVSRO', 'Informatique', 'Apprenez à développer votre propre site et comprendre les algorithmes.'),
(12, 'JJBKZ', 'Physique-Chimie', 'Comprenez comment fonctionne notre monde.'),
(13, 'VYGRI', 'SVT', 'Apprenez comment la vie est apparue sur terre.');

-- --------------------------------------------------------

--
-- Structure de la table `matiere_stage`
--

DROP TABLE IF EXISTS `matiere_stage`;
CREATE TABLE IF NOT EXISTS `matiere_stage` (
  `matiere_id` int NOT NULL,
  `stage_id` int NOT NULL,
  PRIMARY KEY (`matiere_id`,`stage_id`),
  KEY `IDX_4EDC3D1CF46CD258` (`matiere_id`),
  KEY `IDX_4EDC3D1C2298D193` (`stage_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `matiere_stage`
--

INSERT INTO `matiere_stage` (`matiere_id`, `stage_id`) VALUES
(10, 16),
(11, 15),
(11, 17),
(12, 14),
(13, 14);

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `professeur`
--

DROP TABLE IF EXISTS `professeur`;
CREATE TABLE IF NOT EXISTS `professeur` (
  `id` int NOT NULL AUTO_INCREMENT,
  `matiere_id` int DEFAULT NULL,
  `matricule` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_17A5529912B2DC9C` (`matricule`),
  UNIQUE KEY `UNIQ_17A55299E7927C74` (`email`),
  KEY `IDX_17A55299F46CD258` (`matiere_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `professeur`
--

INSERT INTO `professeur` (`id`, `matiere_id`, `matricule`, `nom`, `prenom`, `email`) VALUES
(18, 9, '645', 'Dupont', 'Claire', 'claire.dupont@gmail.com'),
(19, 12, '932', 'Lefevre', 'Pierre', 'pierre.lefevre@gmail.com'),
(20, 11, '526', 'Martin', 'Sophie', 'sophie.martin@gmail.com'),
(21, 10, '152', 'Bernard', 'Nicolas', 'bernard.nicolas@gmail.com'),
(22, 13, '458', 'Dubois', 'Alain', 'dubois.alain@gmail.com');

-- --------------------------------------------------------

--
-- Structure de la table `stage`
--

DROP TABLE IF EXISTS `stage`;
CREATE TABLE IF NOT EXISTS `stage` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code_stage` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `libelle` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `stage`
--

INSERT INTO `stage` (`id`, `code_stage`, `libelle`, `description`, `date_debut`, `date_fin`) VALUES
(14, 'MNJSG', 'Cours combiné SVT - Physique - Chimie', 'Apprenez dans ce stage tous sur les lois qui régissent notre monde.', '2026-06-06 00:00:00', '2026-06-09 00:00:00'),
(15, 'YSYON', 'Base de JAVA', 'Cours en informatique qui vous apprendra comment maitriser JAVA.', '2025-06-04 00:00:00', '2025-06-07 00:00:00'),
(16, 'KBMBL', 'Histoire de France', 'Ce cours vous apprendra tout sur l\'histoire de France.', '2023-01-01 00:00:00', '2023-01-06 00:00:00'),
(17, 'DCFYP', 'Symfony', 'Au fil de ce cours, vous apprendrez les bases du framework Symfony.\nAu bout d\'1 an vous deviendrez un pro.', '2024-12-04 00:00:00', '2025-12-06 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `stage_professeur`
--

DROP TABLE IF EXISTS `stage_professeur`;
CREATE TABLE IF NOT EXISTS `stage_professeur` (
  `stage_id` int NOT NULL,
  `professeur_id` int NOT NULL,
  PRIMARY KEY (`stage_id`,`professeur_id`),
  KEY `IDX_5BFF493D2298D193` (`stage_id`),
  KEY `IDX_5BFF493DBAB22EE9` (`professeur_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `stage_professeur`
--

INSERT INTO `stage_professeur` (`stage_id`, `professeur_id`) VALUES
(14, 19),
(14, 22),
(15, 20),
(16, 21),
(17, 20);

-- --------------------------------------------------------

--
-- Structure de la table `stage_stagiaire`
--

DROP TABLE IF EXISTS `stage_stagiaire`;
CREATE TABLE IF NOT EXISTS `stage_stagiaire` (
  `stage_id` int NOT NULL,
  `stagiaire_id` int NOT NULL,
  PRIMARY KEY (`stage_id`,`stagiaire_id`),
  KEY `IDX_7C690D102298D193` (`stage_id`),
  KEY `IDX_7C690D10BBA93DD6` (`stagiaire_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `stage_stagiaire`
--

INSERT INTO `stage_stagiaire` (`stage_id`, `stagiaire_id`) VALUES
(14, 15),
(14, 16),
(14, 17),
(15, 15),
(15, 16),
(15, 17),
(15, 18),
(15, 19),
(16, 16),
(16, 17),
(17, 15),
(17, 18),
(17, 19);

-- --------------------------------------------------------

--
-- Structure de la table `stagiaire`
--

DROP TABLE IF EXISTS `stagiaire`;
CREATE TABLE IF NOT EXISTS `stagiaire` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse` longtext COLLATE utf8mb4_unicode_ci,
  `code_postal` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ville` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_inscription` datetime NOT NULL,
  `email` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `stagiaire`
--

INSERT INTO `stagiaire` (`id`, `nom`, `prenom`, `adresse`, `code_postal`, `ville`, `date_inscription`, `email`) VALUES
(15, 'Lefevre', 'Jeanne', NULL, NULL, NULL, '2025-01-19 21:49:32', 'jeanne.lefevre@gmail.com'),
(16, 'Petit', 'Lucien', NULL, NULL, NULL, '2025-01-19 21:49:57', 'petit.lucien@gmail.com'),
(17, 'Tanguy', 'Marie', NULL, NULL, NULL, '2025-01-19 21:50:59', 'tanguy.marie@gmail.com'),
(18, 'Roux', 'Thomas', NULL, NULL, NULL, '2025-01-19 21:51:25', 'roux.thomas@gmail.com'),
(19, 'Gauthier', 'Emilie', NULL, NULL, NULL, '2025-01-19 21:51:51', 'gauthier.emilie@gmail.com');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_professeur_id` int DEFAULT NULL,
  `id_stagiaire_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`),
  UNIQUE KEY `UNIQ_8D93D64949AFF8C` (`id_professeur_id`),
  UNIQUE KEY `UNIQ_8D93D649986848A4` (`id_stagiaire_id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `nom`, `prenom`, `id_professeur_id`, `id_stagiaire_id`) VALUES
(3, 'admin@gmail.com', '[\"ROLE_USER\", \"ROLE_ADMIN\"]', '$2y$13$ABHmSv0BMoRso3eHjkhZdu68p54QWfrq.tzoUJJerzZc4/MQrM2l6', 'ADMIN', 'admin', NULL, NULL),
(33, 'claire.dupont@gmail.com', '[\"ROLE_PROFESSEUR\"]', '$2y$13$Xer2KtRFb53Z1a55QP87/.g.wOZdFngTIdnqmPSejMKyaqWWVsKD6', 'Dupont', 'Claire', 18, NULL),
(34, 'pierre.lefevre@gmail.com', '[\"ROLE_PROFESSEUR\"]', '$2y$13$pQ91LIzvd4vXH2L43QHXE.CPFr.c2yWhvYArRU400KV.lp183hKxG', 'Lefevre', 'Pierre', 19, NULL),
(35, 'sophie.martin@gmail.com', '[\"ROLE_PROFESSEUR\"]', '$2y$13$He3pM0HV4QpfJ1hJuzUU6e5y0mqh7Z3JY/cVpXCiMt5oBej2lru62', 'Martin', 'Sophie', 20, NULL),
(36, 'bernard.nicolas@gmail.com', '[\"ROLE_PROFESSEUR\"]', '$2y$13$QMexwNJ10zmKEzqByIdoGOOZ/8f4MZoO7Ok7joOnxcoSN9kBljWIu', 'Bernard', 'Nicolas', 21, NULL),
(37, 'dubois.alain@gmail.com', '[\"ROLE_PROFESSEUR\"]', '$2y$13$a7fRh3SLSn71eaKVjl4ln.kQAGbzVX5PJUHYUV/HTuO1D3kqm5Bs2', 'Dubois', 'Alain', 22, NULL),
(38, 'jeanne.lefevre@gmail.com', '[\"ROLE_STAGIAIRE\"]', '$2y$13$Z9D6roXpn1wIVOjpRS2fxudZI5yXsSNgLQLs/Wrd.T1uIYQ2anXku', 'Lefevre', 'Jeanne', NULL, 15),
(39, 'petit.lucien@gmail.com', '[\"ROLE_STAGIAIRE\"]', '$2y$13$ioqGGiEyxUK7SgtOhJarzOusNN/ghya.2Be4pdZhLrAFKl2Y1tUpG', 'Petit', 'Lucien', NULL, 16),
(41, 'tanguy.marie@gmail.com', '[\"ROLE_STAGIAIRE\"]', '$2y$13$MPe2HOobL2waWyXM4Mx03OdGV0do5H/wLhX0AvzshRUTjlIG/E0l.', 'Tanguy', 'Marie', NULL, 17),
(42, 'roux.thomas@gmail.com', '[\"ROLE_STAGIAIRE\"]', '$2y$13$ZfMMarxAsy9irhRkuOah4uXNGDZ8fxFlxWxwDJDPsAGMoCe923T3W', 'Roux', 'Thomas', NULL, 18),
(43, 'gauthier.emilie@gmail.com', '[\"ROLE_STAGIAIRE\"]', '$2y$13$Qd98vXdGJAKXxCb0T71D8uymNz77APW5WDZUUCVmsdZHlAu14QzqO', 'Gauthier', 'Emilie', NULL, 19);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `matiere_stage`
--
ALTER TABLE `matiere_stage`
  ADD CONSTRAINT `FK_4EDC3D1C2298D193` FOREIGN KEY (`stage_id`) REFERENCES `stage` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_4EDC3D1CF46CD258` FOREIGN KEY (`matiere_id`) REFERENCES `matiere` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `professeur`
--
ALTER TABLE `professeur`
  ADD CONSTRAINT `FK_17A55299F46CD258` FOREIGN KEY (`matiere_id`) REFERENCES `matiere` (`id`);

--
-- Contraintes pour la table `stage_professeur`
--
ALTER TABLE `stage_professeur`
  ADD CONSTRAINT `FK_5BFF493D2298D193` FOREIGN KEY (`stage_id`) REFERENCES `stage` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_5BFF493DBAB22EE9` FOREIGN KEY (`professeur_id`) REFERENCES `professeur` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `stage_stagiaire`
--
ALTER TABLE `stage_stagiaire`
  ADD CONSTRAINT `FK_7C690D102298D193` FOREIGN KEY (`stage_id`) REFERENCES `stage` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_7C690D10BBA93DD6` FOREIGN KEY (`stagiaire_id`) REFERENCES `stagiaire` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_8D93D64949AFF8C` FOREIGN KEY (`id_professeur_id`) REFERENCES `professeur` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_8D93D649986848A4` FOREIGN KEY (`id_stagiaire_id`) REFERENCES `stagiaire` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

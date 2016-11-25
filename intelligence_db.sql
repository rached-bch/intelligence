-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Ven 25 Novembre 2016 à 07:31
-- Version du serveur :  5.6.21
-- Version de PHP :  5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `intelligence_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `advert`
--

CREATE TABLE IF NOT EXISTS `advert` (
`id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `content` varchar(255) NOT NULL,
  `image_id` int(11) DEFAULT NULL,
  `published` tinyint(1) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `nb_applications` int(11) NOT NULL,
  `slug` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `advert_category`
--

CREATE TABLE IF NOT EXISTS `advert_category` (
  `advert_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `advert_skill`
--

CREATE TABLE IF NOT EXISTS `advert_skill` (
`id` int(11) NOT NULL,
  `advert_id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL,
  `level` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `application`
--

CREATE TABLE IF NOT EXISTS `application` (
`id` int(11) NOT NULL,
  `advert_id` int(11) NOT NULL,
  `author` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
`id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(11, 'Développement web'),
(12, 'Développement mobile'),
(13, 'Graphisme'),
(14, 'Intégration'),
(15, 'Réseau');

-- --------------------------------------------------------

--
-- Structure de la table `image`
--

CREATE TABLE IF NOT EXISTS `image` (
`id` int(11) NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `alt` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `it_advert_category`
--

CREATE TABLE IF NOT EXISTS `it_advert_category` (
  `advert_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `oc_advert_category`
--

CREATE TABLE IF NOT EXISTS `oc_advert_category` (
  `advert_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `skill`
--

CREATE TABLE IF NOT EXISTS `skill` (
`id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `skill`
--

INSERT INTO `skill` (`id`, `name`) VALUES
(1, 'PHP'),
(2, 'Symfony'),
(3, 'C++'),
(4, 'Java'),
(5, 'Photoshop'),
(6, 'Blender'),
(7, 'Bloc-note');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
`id` int(11) NOT NULL,
  `username` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `username_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `locked` tinyint(1) NOT NULL,
  `expired` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `confirmation_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `credentials_expired` tinyint(1) NOT NULL,
  `credentials_expire_at` datetime DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `salt`, `roles`, `username_canonical`, `email`, `email_canonical`, `enabled`, `last_login`, `locked`, `expired`, `expires_at`, `confirmation_token`, `password_requested_at`, `credentials_expired`, `credentials_expire_at`, `first_name`, `last_name`) VALUES
(1, 'rached', 'pds95gUsTWKExipi15uiilrlLggkAqHo6IlwDkXsqRpTkt+Qb5QxuDOpc3bnCJJTexmbobMjg2rDKXC55YDZJw==', 'tgp3eqqzlvk0wckgocw4ggco88c000w', 'a:0:{}', 'rached', 'adresse1@domainevirtuel.com', 'adresse1@domainevirtuel.com', 1, '2016-09-17 13:35:50', 0, 0, NULL, NULL, NULL, 0, NULL, 'Rached', 'BEN CHAABENE');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `advert`
--
ALTER TABLE `advert`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `UNIQ_54F1F40B989D9B62` (`slug`), ADD UNIQUE KEY `UNIQ_54F1F40B2B36786B` (`title`), ADD UNIQUE KEY `UNIQ_54F1F40B3DA5256D` (`image_id`);

--
-- Index pour la table `advert_category`
--
ALTER TABLE `advert_category`
 ADD PRIMARY KEY (`advert_id`,`category_id`), ADD KEY `IDX_84EEA340D07ECCB6` (`advert_id`), ADD KEY `IDX_84EEA34012469DE2` (`category_id`);

--
-- Index pour la table `advert_skill`
--
ALTER TABLE `advert_skill`
 ADD PRIMARY KEY (`id`), ADD KEY `IDX_5619F91BD07ECCB6` (`advert_id`), ADD KEY `IDX_5619F91B5585C142` (`skill_id`);

--
-- Index pour la table `application`
--
ALTER TABLE `application`
 ADD PRIMARY KEY (`id`), ADD KEY `IDX_A45BDDC1D07ECCB6` (`advert_id`);

--
-- Index pour la table `category`
--
ALTER TABLE `category`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `image`
--
ALTER TABLE `image`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `it_advert_category`
--
ALTER TABLE `it_advert_category`
 ADD PRIMARY KEY (`advert_id`,`category_id`), ADD KEY `IDX_7AE1F9B5D07ECCB6` (`advert_id`), ADD KEY `IDX_7AE1F9B512469DE2` (`category_id`);

--
-- Index pour la table `oc_advert_category`
--
ALTER TABLE `oc_advert_category`
 ADD PRIMARY KEY (`advert_id`,`category_id`), ADD KEY `IDX_435EA006D07ECCB6` (`advert_id`), ADD KEY `IDX_435EA00612469DE2` (`category_id`);

--
-- Index pour la table `skill`
--
ALTER TABLE `skill`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `UNIQ_8D93D64992FC23A8` (`username_canonical`), ADD UNIQUE KEY `UNIQ_8D93D649A0D96FBF` (`email_canonical`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `advert`
--
ALTER TABLE `advert`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `advert_skill`
--
ALTER TABLE `advert_skill`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `application`
--
ALTER TABLE `application`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `category`
--
ALTER TABLE `category`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT pour la table `image`
--
ALTER TABLE `image`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `skill`
--
ALTER TABLE `skill`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `advert`
--
ALTER TABLE `advert`
ADD CONSTRAINT `FK_54F1F40B3DA5256D` FOREIGN KEY (`image_id`) REFERENCES `image` (`id`);

--
-- Contraintes pour la table `advert_category`
--
ALTER TABLE `advert_category`
ADD CONSTRAINT `FK_84EEA34012469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `FK_84EEA340D07ECCB6` FOREIGN KEY (`advert_id`) REFERENCES `advert` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `advert_skill`
--
ALTER TABLE `advert_skill`
ADD CONSTRAINT `FK_5619F91B5585C142` FOREIGN KEY (`skill_id`) REFERENCES `skill` (`id`),
ADD CONSTRAINT `FK_5619F91BD07ECCB6` FOREIGN KEY (`advert_id`) REFERENCES `advert` (`id`);

--
-- Contraintes pour la table `application`
--
ALTER TABLE `application`
ADD CONSTRAINT `FK_A45BDDC1D07ECCB6` FOREIGN KEY (`advert_id`) REFERENCES `advert` (`id`);

--
-- Contraintes pour la table `it_advert_category`
--
ALTER TABLE `it_advert_category`
ADD CONSTRAINT `FK_7AE1F9B512469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `FK_7AE1F9B5D07ECCB6` FOREIGN KEY (`advert_id`) REFERENCES `advert` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `oc_advert_category`
--
ALTER TABLE `oc_advert_category`
ADD CONSTRAINT `FK_435EA00612469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `FK_435EA006D07ECCB6` FOREIGN KEY (`advert_id`) REFERENCES `advert` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

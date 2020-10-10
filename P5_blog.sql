-- phpMyAdmin SQL Dump
-- version 4.9.3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le :  sam. 10 oct. 2020 à 14:59
-- Version du serveur :  5.7.26
-- Version de PHP :  7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `P5_blog`
--

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `id_author` int(11) NOT NULL,
  `pseudo_author` varchar(255) NOT NULL,
  `id_post` int(11) NOT NULL,
  `content` text NOT NULL,
  `login_insert` int(11) DEFAULT NULL,
  `date_insert` datetime DEFAULT CURRENT_TIMESTAMP,
  `login_mod` int(11) DEFAULT NULL,
  `date_mod` datetime DEFAULT CURRENT_TIMESTAMP,
  `displayed_status` enum('pending','granted','rejected') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `home`
--

CREATE TABLE `home` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `tagline` varchar(255) NOT NULL,
  `presentation` text NOT NULL,
  `photo` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `post`
--

CREATE TABLE `post` (
  `id` int(11) NOT NULL COMMENT 'ID du post',
  `title` varchar(255) NOT NULL COMMENT 'titre',
  `id_author` int(11) NOT NULL COMMENT 'ID de l''auteur du post',
  `pseudo_author` varchar(255) DEFAULT NULL COMMENT 'pseudo de l''auteur',
  `content` text NOT NULL COMMENT 'contenu',
  `content_short` varchar(100) DEFAULT NULL COMMENT 'chapô',
  `date_display` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'date d''affichage du post',
  `displayed_status` tinyint(1) NOT NULL DEFAULT '0',
  `login_insert` varchar(255) DEFAULT NULL COMMENT 'pseudo de l''user connecté lors de l''insertion',
  `date_insert` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'date d''insertion',
  `login_mod` varchar(255) DEFAULT NULL COMMENT 'pseudo de l''user connecté lors de la modification',
  `date_mod` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'date de modification'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `pseudo` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('member','admin','superadmin','') NOT NULL DEFAULT 'member',
  `lname` varchar(255) DEFAULT NULL,
  `fname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `login_insert` varchar(255) DEFAULT NULL,
  `date_insert` datetime DEFAULT CURRENT_TIMESTAMP,
  `login_mod` varchar(255) DEFAULT NULL,
  `date_mod` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `comment`
--
ALTER TABLE `comment`
  ADD UNIQUE KEY `id` (`id`);

--
-- Index pour la table `home`
--
ALTER TABLE `home`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD UNIQUE KEY `id_2` (`id`),
  ADD KEY `ID` (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `home`
--
ALTER TABLE `home`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `post`
--
ALTER TABLE `post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID du post';

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

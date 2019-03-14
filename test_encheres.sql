-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:3306
-- Généré le :  Jeu 14 Mars 2019 à 17:11
-- Version du serveur :  10.1.37-MariaDB-0+deb9u1
-- Version de PHP :  7.3.3-1+0~20190307202245.32+stretch~1.gbp32ebb2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `test_encheres`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `categories`
--

INSERT INTO `categories` (`id`, `nom`) VALUES
(1, 'Téléphones'),
(2, 'Cartes cadeau'),
(3, 'Consoles');

-- --------------------------------------------------------

--
-- Structure de la table `categories_details`
--

CREATE TABLE `categories_details` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `categorie` mediumint(8) UNSIGNED DEFAULT NULL,
  `nom` varchar(191) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `categories_details`
--

INSERT INTO `categories_details` (`id`, `categorie`, `nom`) VALUES
(1, 1, 'Taille d\'écran'),
(2, 1, 'Batterie'),
(3, 1, 'Réseaux'),
(4, 2, 'Site marchand'),
(5, 3, 'Taille stockage'),
(6, 3, 'Nombre manettes');

-- --------------------------------------------------------

--
-- Structure de la table `encheres`
--

CREATE TABLE `encheres` (
  `id` int(10) UNSIGNED NOT NULL,
  `produit` int(10) UNSIGNED NOT NULL,
  `cout_mise` smallint(5) UNSIGNED NOT NULL,
  `prix_public` smallint(5) UNSIGNED DEFAULT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime NOT NULL,
  `nb_mises` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `encheres`
--

INSERT INTO `encheres` (`id`, `produit`, `cout_mise`, `prix_public`, `date_debut`, `date_fin`, `nb_mises`) VALUES
(1, 2, 100, 20000, '2019-03-13 12:00:00', '2019-05-11 12:00:00', 0);

-- --------------------------------------------------------

--
-- Structure de la table `images`
--

CREATE TABLE `images` (
  `id` int(10) UNSIGNED NOT NULL,
  `produit` int(10) UNSIGNED NOT NULL,
  `hash` varchar(191) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `images`
--

INSERT INTO `images` (`id`, `produit`, `hash`) VALUES
(2, 1, '08a17dbe3fd8a8b79da0909f0db61e9329d5dc5c'),
(1, 1, '2018db00983885b4d5d32e6adfab048399deb893'),
(4, 2, '1b248ae6d72e987f77d450aa61b9fc6a3c04ff2f'),
(3, 2, 'f9ed66bdce0166a2dffbbe928545a70f8e67d716'),
(7, 3, '2bf7f6ca073dd88010caec5bb87c5c54c8f1f078'),
(5, 3, '4a5b61d773a60457799f08a0eaaeefff74f8d86f'),
(8, 4, '70a385b8b6a31b04c84aa251e4032563b04b16c2');

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `liste_encheres_courantes`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `liste_encheres_courantes` (
`pid` int(10) unsigned
,`nom` varchar(255)
,`id` int(10) unsigned
,`produit` int(10) unsigned
,`cout_mise` smallint(5) unsigned
,`prix_public` smallint(5) unsigned
,`date_debut` datetime
,`date_fin` datetime
,`nb_mises` int(10) unsigned
,`nom_categorie` varchar(255)
,`image` int(10) unsigned
);

-- --------------------------------------------------------

--
-- Structure de la table `membres`
--

CREATE TABLE `membres` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(191) NOT NULL,
  `passe` varchar(255) NOT NULL,
  `admin` tinyint(4) NOT NULL DEFAULT '0',
  `date_inscription` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `credit` int(11) NOT NULL DEFAULT '1000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `membres`
--

INSERT INTO `membres` (`id`, `email`, `passe`, `admin`, `date_inscription`, `credit`) VALUES
(1, 'admin@admin.fr', '$2y$10$oguCg9RCbHrpYt1kREqBU.q1byKAyJWiw4jjq.k2LwbuHC1fOuMQG', 1, '2019-03-14 10:04:03', 13400);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `mes_mises`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `mes_mises` (
);

-- --------------------------------------------------------

--
-- Structure de la table `mises`
--

CREATE TABLE `mises` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `membre` int(10) UNSIGNED NOT NULL,
  `enchere` int(10) UNSIGNED NOT NULL,
  `montant` smallint(5) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `mises`
--

INSERT INTO `mises` (`id`, `membre`, `enchere`, `montant`, `date`) VALUES
(1, 1, 1, 1, '2019-03-14 16:08:50'),
(2, 1, 1, 2, '2019-03-14 16:08:50'),
(3, 1, 1, 3, '2019-03-14 16:08:50'),
(4, 1, 1, 4, '2019-03-14 16:08:50'),
(5, 1, 1, 5, '2019-03-14 16:08:50'),
(6, 1, 1, 6, '2019-03-14 16:08:50'),
(7, 1, 1, 7, '2019-03-14 16:08:50'),
(8, 1, 1, 8, '2019-03-14 16:08:50'),
(9, 1, 1, 9, '2019-03-14 16:08:50');

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `mise_gagnante`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `mise_gagnante` (
);

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

CREATE TABLE `produits` (
  `id` int(10) UNSIGNED NOT NULL,
  `categorie` mediumint(8) UNSIGNED DEFAULT NULL,
  `nom` varchar(255) NOT NULL,
  `description` longtext,
  `image` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `produits`
--

INSERT INTO `produits` (`id`, `categorie`, `nom`, `description`, `image`) VALUES
(1, 1, 'Yotaphone 2', 'Le Yotaphone 2 est un smartphone doté de deux écrans, un AMOLED au recto, et un E Ink au verso. Un appareil haut de gamme offrant un très bon design.', 1),
(2, 3, 'GameCube', 'La Nintendo GameCube est la sixième console de salon produite par Nintendo. Elle est sortie le 14 septembre 2001 au Japon, le 18 novembre 2001 en Amérique du Nord, et le 3 mai 2002 en Europe. Elle est disponible en deux coloris : le violet et le noir, sans compter les éditions spéciales de différentes couleurs sorties par la suite.', 3),
(3, 3, 'Playstation 4', 'La PS4 Pro embarque deux fois plus de puissance graphique que la PS4 standard, ce qui vous offre une image ultra-nette, un gameplay plus fluide et des temps de chargement réduits avec les jeux optimisés pour PS4 Pro.', 7),
(4, 2, 'Carte cadeau 100 € Amazon', 'Carte cadeau', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `produits_details`
--

CREATE TABLE `produits_details` (
  `id` int(10) UNSIGNED NOT NULL,
  `produit` int(10) UNSIGNED NOT NULL,
  `detail` smallint(5) UNSIGNED NOT NULL,
  `valeur` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `produits_details`
--

INSERT INTO `produits_details` (`id`, `produit`, `detail`, `valeur`) VALUES
(1, 1, 1, '5\"'),
(2, 1, 2, '2500 mAh'),
(3, 1, 3, '2G, 3G, 4G LTE'),
(4, 2, 5, '64 Mo'),
(5, 2, 6, '4'),
(6, 3, 5, '500 Go'),
(7, 3, 6, '1'),
(8, 4, 4, 'Amazon');

-- --------------------------------------------------------

--
-- Structure de la vue `liste_encheres_courantes`
--
DROP TABLE IF EXISTS `liste_encheres_courantes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`admin`@`localhost` SQL SECURITY DEFINER VIEW `liste_encheres_courantes`  AS  select `p`.`id` AS `pid`,`p`.`nom` AS `nom`,`e`.`id` AS `id`,`e`.`produit` AS `produit`,`e`.`cout_mise` AS `cout_mise`,`e`.`prix_public` AS `prix_public`,`e`.`date_debut` AS `date_debut`,`e`.`date_fin` AS `date_fin`,`e`.`nb_mises` AS `nb_mises`,`c`.`nom` AS `nom_categorie`,`p`.`image` AS `image` from ((`produits` `p` join `encheres` `e` on((`e`.`produit` = `p`.`id`))) join `categories` `c` on((`c`.`id` = `p`.`categorie`))) where (now() between `e`.`date_debut` and `e`.`date_fin`) order by `e`.`date_fin` desc ;

-- --------------------------------------------------------

--
-- Structure de la vue `mes_mises`
--
DROP TABLE IF EXISTS `mes_mises`;

CREATE ALGORITHM=UNDEFINED DEFINER=`admin`@`localhost` SQL SECURITY DEFINER VIEW `mes_mises`  AS  select `mises`.`id` AS `id`,`mises`.`utilisateur` AS `utilisateur`,`mises`.`enchere` AS `enchere`,`mises`.`montant` AS `montant`,`mises`.`date` AS `date`,count(`mises`.`id`) AS `nb_mises` from `mises` group by `mises`.`enchere`,`mises`.`montant` order by `mises`.`montant` ;

-- --------------------------------------------------------

--
-- Structure de la vue `mise_gagnante`
--
DROP TABLE IF EXISTS `mise_gagnante`;

CREATE ALGORITHM=UNDEFINED DEFINER=`admin`@`localhost` SQL SECURITY DEFINER VIEW `mise_gagnante`  AS  select `mises`.`id` AS `id`,`mises`.`utilisateur` AS `utilisateur`,`mises`.`enchere` AS `enchere`,`mises`.`montant` AS `montant`,`mises`.`date` AS `date` from `mises` group by `mises`.`enchere`,`mises`.`montant` having (count(`mises`.`montant`) = 1) order by `mises`.`montant` limit 1 ;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `categories_details`
--
ALTER TABLE `categories_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categorie` (`categorie`);

--
-- Index pour la table `encheres`
--
ALTER TABLE `encheres`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produit` (`produit`);

--
-- Index pour la table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `produit` (`produit`,`hash`);

--
-- Index pour la table `membres`
--
ALTER TABLE `membres`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `mises`
--
ALTER TABLE `mises`
  ADD PRIMARY KEY (`id`),
  ADD KEY `membre` (`membre`),
  ADD KEY `enchere` (`enchere`);

--
-- Index pour la table `produits`
--
ALTER TABLE `produits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categorie` (`categorie`),
  ADD KEY `image` (`image`);
ALTER TABLE `produits` ADD FULLTEXT KEY `nom` (`nom`,`description`);

--
-- Index pour la table `produits_details`
--
ALTER TABLE `produits_details`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `produit` (`produit`,`detail`),
  ADD KEY `detail` (`detail`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `categories_details`
--
ALTER TABLE `categories_details`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT pour la table `encheres`
--
ALTER TABLE `encheres`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT pour la table `membres`
--
ALTER TABLE `membres`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `mises`
--
ALTER TABLE `mises`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT pour la table `produits`
--
ALTER TABLE `produits`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `produits_details`
--
ALTER TABLE `produits_details`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `categories_details`
--
ALTER TABLE `categories_details`
  ADD CONSTRAINT `categories_details_ibfk_1` FOREIGN KEY (`categorie`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `encheres`
--
ALTER TABLE `encheres`
  ADD CONSTRAINT `encheres_ibfk_1` FOREIGN KEY (`produit`) REFERENCES `produits` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `images_ibfk_1` FOREIGN KEY (`produit`) REFERENCES `produits` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `mises`
--
ALTER TABLE `mises`
  ADD CONSTRAINT `mises_ibfk_1` FOREIGN KEY (`membre`) REFERENCES `membres` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mises_ibfk_2` FOREIGN KEY (`enchere`) REFERENCES `encheres` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `produits`
--
ALTER TABLE `produits`
  ADD CONSTRAINT `produits_ibfk_1` FOREIGN KEY (`categorie`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `produits_ibfk_2` FOREIGN KEY (`image`) REFERENCES `images` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `produits_details`
--
ALTER TABLE `produits_details`
  ADD CONSTRAINT `produits_details_ibfk_1` FOREIGN KEY (`detail`) REFERENCES `categories_details` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `produits_details_ibfk_2` FOREIGN KEY (`produit`) REFERENCES `produits` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

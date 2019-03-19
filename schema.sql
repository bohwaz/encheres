SET FOREIGN_KEY_CHECKS=0;

CREATE TABLE IF NOT EXISTS membres (
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	email VARCHAR(191) NOT NULL UNIQUE,
	passe VARCHAR(255) NOT NULL,
	admin TINYINT NOT NULL DEFAULT 0,
	date_inscription TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	credit INTEGER NOT NULL DEFAULT 1000
) ENGINE=InnoDB;

INSERT INTO membres VALUES (1, 'admin@admin.fr', '$2y$10$oguCg9RCbHrpYt1kREqBU.q1byKAyJWiw4jjq.k2LwbuHC1fOuMQG', 1, NOW(), 10000);
INSERT INTO membres VALUES (2, 'utilisateur@mail.com', '$2y$10$oguCg9RCbHrpYt1kREqBU.q1byKAyJWiw4jjq.k2LwbuHC1fOuMQG', 0, NOW(), 10000);

CREATE TABLE IF NOT EXISTS categories (
	id MEDIUMINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	nom VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS categories_details (
	id SMALLINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	categorie MEDIUMINT UNSIGNED NULL,
	nom VARCHAR(191) NOT NULL,
	FOREIGN KEY (categorie) REFERENCES categories (id) ON DELETE SET NULL
) ENGINE=InnoDB;

INSERT INTO categories VALUES
	(1, 'Téléphones'),
	(2, 'Cartes cadeau'),
	(3, 'Consoles');

INSERT INTO categories_details VALUES
	(1, 1, 'Taille d''écran'),
	(2, 1, 'Batterie'),
	(3, 1, 'Réseaux'),
	(4, 2, 'Site marchand'),
	(5, 3, 'Taille stockage'),
	(6, 3, 'Nombre manettes');

CREATE TABLE IF NOT EXISTS produits (
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	categorie MEDIUMINT UNSIGNED NULL,
	nom VARCHAR(255) NOT NULL,
	description LONGTEXT,
	image INTEGER UNSIGNED NULL,
--	FULLTEXT (nom, description),
	FOREIGN KEY (categorie) REFERENCES categories (id) ON DELETE SET NULL,
	FOREIGN KEY (image) REFERENCES images (id) ON DELETE SET NULL
) ENGINE=InnoDB;

INSERT INTO `produits` (`id`, `categorie`, `nom`, `description`, `image`) VALUES
(1, 1, 'Yotaphone 2', 'Le Yotaphone 2 est un smartphone doté de deux écrans, un AMOLED au recto, et un E Ink au verso. Un appareil haut de gamme offrant un très bon design.', 1),
(2, 3, 'GameCube', 'La Nintendo GameCube est la sixième console de salon produite par Nintendo. Elle est sortie le 14 septembre 2001 au Japon, le 18 novembre 2001 en Amérique du Nord, et le 3 mai 2002 en Europe. Elle est disponible en deux coloris : le violet et le noir, sans compter les éditions spéciales de différentes couleurs sorties par la suite.', 3),
(3, 3, 'Playstation 4', 'La PS4 Pro embarque deux fois plus de puissance graphique que la PS4 standard, ce qui vous offre une image ultra-nette, un gameplay plus fluide et des temps de chargement réduits avec les jeux optimisés pour PS4 Pro.', 7),
(4, 2, 'Carte cadeau 100 € Amazon', 'Carte cadeau', NULL);

CREATE TABLE IF NOT EXISTS produits_details (
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	produit INTEGER UNSIGNED NOT NULL,
	detail SMALLINT UNSIGNED NOT NULL,
	valeur TEXT NOT NULL,
	UNIQUE (produit, detail),
	FOREIGN KEY (detail) REFERENCES categories_details (id) ON DELETE CASCADE,
	FOREIGN KEY (produit) REFERENCES produits (id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO `produits_details` (`id`, `produit`, `detail`, `valeur`) VALUES
(1, 1, 1, '5\"'),
(2, 1, 2, '2500 mAh'),
(3, 1, 3, '2G, 3G, 4G LTE'),
(4, 2, 5, '64 Mo'),
(5, 2, 6, '4'),
(6, 3, 5, '500 Go'),
(7, 3, 6, '1'),
(8, 4, 4, 'Amazon');

CREATE TABLE IF NOT EXISTS images (
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	produit INTEGER UNSIGNED NOT NULL,
	hash VARCHAR(191) NOT NULL,
	UNIQUE (produit, hash),
	FOREIGN KEY (produit) REFERENCES produits (id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO `images` (`id`, `produit`, `hash`) VALUES
(2, 1, '08a17dbe3fd8a8b79da0909f0db61e9329d5dc5c'),
(1, 1, '2018db00983885b4d5d32e6adfab048399deb893'),
(4, 2, '1b248ae6d72e987f77d450aa61b9fc6a3c04ff2f'),
(3, 2, 'f9ed66bdce0166a2dffbbe928545a70f8e67d716'),
(7, 3, '2bf7f6ca073dd88010caec5bb87c5c54c8f1f078'),
(5, 3, '4a5b61d773a60457799f08a0eaaeefff74f8d86f'),
(8, 4, '70a385b8b6a31b04c84aa251e4032563b04b16c2');

CREATE TABLE IF NOT EXISTS encheres (
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	produit INTEGER UNSIGNED NOT NULL,
	cout_mise SMALLINT UNSIGNED NOT NULL,
	prix_public SMALLINT UNSIGNED,
	date_debut DATETIME NOT NULL,
	date_fin DATETIME NOT NULL,
	FOREIGN KEY (produit) REFERENCES produits (id) ON DELETE CASCADE
) ENGINE=InnoDB;


INSERT INTO `encheres` (`id`, `produit`, `cout_mise`, `prix_public`, `date_debut`, `date_fin`) VALUES
(1, 2, 100, 20000, NOW() - INTERVAL 1 DAY, NOW() + INTERVAL 3 MONTH);

INSERT INTO `encheres` (`produit`, `prix_public`, `cout_mise`, `date_debut`, `date_fin`) VALUES
(3, "10000", "20", NOW() - INTERVAL 1 MONTH, NOW() - INTERVAL 2 WEEK);

CREATE TABLE IF NOT EXISTS mises (
	id BIGINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	membre INTEGER UNSIGNED NOT NULL,
	enchere INTEGER UNSIGNED NOT NULL,
	montant SMALLINT UNSIGNED NOT NULL,
	`date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (membre) REFERENCES membres (id) ON DELETE CASCADE,
	FOREIGN KEY (enchere) REFERENCES encheres (id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO `mises` (`membre`, `enchere`, `montant`) VALUES
(1, 1, 1),
(1, 1, 2),
(1, 1, 3),
(1, 1, 4),
(1, 1, 5),
(1, 1, 6),
(1, 1, 7),
(1, 1, 8),
(1, 1, 9),
(2, 1, 1),
(2, 1, 2),
(2, 1, 3),
(2, 1, 4);


DROP VIEW IF EXISTS mises_gagnantes;

CREATE VIEW mises_gagnantes AS
	SELECT *, 'gagnante' AS statut, 1 AS nb_mises
	FROM mises
	GROUP BY enchere, montant
	HAVING COUNT(montant) = 1
	ORDER BY montant LIMIT 1;

DROP VIEW IF EXISTS mises_uniques;

CREATE VIEW mises_uniques AS
	SELECT *, 'unique' AS statut, 1 AS nb_mises
	FROM mises
	GROUP BY enchere, montant
	HAVING COUNT(montant) = 1
	ORDER BY montant;

DROP VIEW IF EXISTS mises_multiples;

CREATE VIEW mises_multiples AS
	SELECT *, 'multiple' AS statut, COUNT(montant) AS nb_mises
	FROM mises
	GROUP BY enchere, montant
	HAVING COUNT(montant) > 1
	ORDER BY montant;

DROP VIEW IF EXISTS liste_encheres_courantes;

CREATE VIEW liste_encheres AS
	SELECT p.id AS pid, p.nom, e.*, c.nom AS nom_categorie, p.image
	FROM produits AS p
	INNER JOIN encheres AS e ON e.produit = p.id
	INNER JOIN categories AS c ON c.id = p.categorie
	ORDER BY e.date_fin DESC;

-- SELECT * FROM mises_statuts WHERE user = ? AND enchere = ?
DROP VIEW IF EXISTS mises_statuts;
CREATE VIEW mises_statuts AS
	SELECT * FROM mises_multiples
	UNION SELECT * FROM mises_uniques
	UNION SELECT * FROM mises_gagnantes
	GROUP BY montant
	ORDER BY montant;

SET FOREIGN_KEY_CHECKS=1;
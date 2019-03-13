SET FOREIGN_KEY_CHECKS=0;

CREATE TABLE IF NOT EXISTS membres (
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	email VARCHAR(255) NOT NULL UNIQUE,
	passe VARCHAR(255) NOT NULL,
	admin TINYINT NOT NULL DEFAULT 0,
	date_inscription TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	credit INTEGER NOT NULL DEFAULT 1000
) ENGINE=InnoDB;

INSERT INTO membres VALUES (1, 'admin@admin.fr', '$2y$10$oguCg9RCbHrpYt1kREqBU.q1byKAyJWiw4jjq.k2LwbuHC1fOuMQG', 1, NOW(), 10000);

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
	FULLTEXT (nom, description),
	FOREIGN KEY (categorie) REFERENCES categories (id) ON DELETE SET NULL,
	FOREIGN KEY (image) REFERENCES images (id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS produits_details (
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	produit INTEGER UNSIGNED NOT NULL,
	detail SMALLINT UNSIGNED NOT NULL,
	valeur TEXT NOT NULL,
	UNIQUE (produit, detail),
	FOREIGN KEY (detail) REFERENCES categories_details (id) ON DELETE CASCADE,
	FOREIGN KEY (produit) REFERENCES produits (id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS images (
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	produit INTEGER UNSIGNED NOT NULL,
	hash VARCHAR(191) NOT NULL,
	UNIQUE (produit, hash),
	FOREIGN KEY (produit) REFERENCES produits (id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS encheres (
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	produit INTEGER UNSIGNED NOT NULL,
	cout_mise SMALLINT UNSIGNED NOT NULL,
	prix_public SMALLINT UNSIGNED,
	date_debut DATETIME NOT NULL,
	date_fin DATETIME NOT NULL,
	nb_mises INTEGER UNSIGNED NOT NULL DEFAULT 0,
	FOREIGN KEY (produit) REFERENCES produits (id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS mises (
	id BIGINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	utilisateur INTEGER UNSIGNED NOT NULL,
	enchere INTEGER UNSIGNED NOT NULL,
	montant SMALLINT UNSIGNED NOT NULL,
	`date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (utilisateur) REFERENCES utilisateurs (id) ON DELETE CASCADE,
	FOREIGN KEY (enchere) REFERENCES encheres (id) ON DELETE CASCADE
) ENGINE=InnoDB;

DELIMITER //
CREATE TRIGGER encheres_nombre_mises_add AFTER INSERT ON mises
	FOR EACH ROW BEGIN
		UPDATE encheres SET nb_mises = COALESCE(nb_mises, 0) + 1 WHERE id = NEW.enchere;
	END;
//
CREATE TRIGGER encheres_nombre_mises_sub AFTER DELETE ON mises
	FOR EACH ROW BEGIN
		UPDATE encheres SET nb_mises = COALESCE(nb_mises, 0) - 1 WHERE id = OLD.enchere;
	END;
DELIMITER ;

CREATE VIEW mise_gagnante AS
	SELECT *
	FROM mises
	GROUP BY enchere, montant
	HAVING COUNT(montant) = 1
	ORDER BY montant LIMIT 1;

CREATE VIEW mes_mises AS
	SELECT *, COUNT(id) AS nb_mises
	FROM mises
	GROUP BY enchere, montant
	ORDER BY montant;

CREATE VIEW liste_encheres_courantes AS
	SELECT p.id AS pid, p.nom, e.*, c.nom AS nom_categorie
	FROM produits AS p
	INNER JOIN encheres AS e ON e.produit = p.id
	INNER JOIN categories AS c ON c.id = p.categorie
	WHERE NOW() BETWEEN e.date_debut AND e.date_fin
	ORDER BY e.date_fin DESC;

-- SELECT * FROM mises_statuts WHERE user = ? AND enchere = ?
CREATE VIEW mises_statuts AS
	SELECT * FROM (
		SELECT *, "unique" AS statut FROM mises_uniques
		UNION SELECT *, "multiple" AS statut FROM mises_multiples
		UNION SELECT *, "gagnante" AS statut FROM mises_gagnantes)
	ORDER BY montant;

SET FOREIGN_KEY_CHECKS=1;
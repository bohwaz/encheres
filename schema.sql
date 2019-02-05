CREATE TABLE membres (
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	email VARCHAR(255) NOT NULL UNIQUE,
	passe VARCHAR(255) NOT NULL,
	admin TINYINT NOT NULL DEFAULT 0,
	date_inscription DATETIME NOT NULL,
	credit INTEGER NOT NULL DEFAULT 1000
);

CREATE TABLE categories (
	id MEDIUMINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	nom VARCHAR(255) NOT NULL
);

CREATE TABLE produits (
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	categorie MEDIUMINT UNSIGNED NULL,
	nom VARCHAR(255) NOT NULL,
	description LONGTEXT,
	image INTEGER UNSIGNED NULL,
	FOREIGN KEY (categorie) REFERENCES categories (id) ON DELETE SET NULL,
	FOREIGN KEY (image) REFERENCES images (id) ON DELETE SET NULL
);

CREATE TABLE produits_details (
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	produit INTEGER UNSIGNED NOT NULL,
	nom VARCHAR(191) NOT NULL,
	valeur TEXT NOT NULL,
	UNIQUE (produit, nom)
	FOREIGN KEY (produit) REFERENCES produits (id) ON DELETE CASCADE
);

CREATE TABLE images (
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	produit INTEGER UNSIGNED NOT NULL,
	hash VARCHAR(191) NOT NULL,
	UNIQUE (produit, hash),
	FOREIGN KEY (produit) REFERENCES produits (id) ON DELETE CASCADE
);

CREATE TABLE encheres (
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	produit INTEGER UNSIGNED NOT NULL,
	cout_mise SMALLINT UNSIGNED NOT NULL,
	prix_public SMALLINT UNSIGNED,
	date_debut DATETIME NOT NULL,
	date_fin DATETIME NOT NULL,
	nb_mises INTEGER UNSIGNED NULL,
	FOREIGN KEY (produit) REFERENCES produits (id) ON DELETE CASCADE
);

CREATE TABLE mises (
	id BIGINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	utilisateur INTEGER UNSIGNED NOT NULL,
	enchere INTEGER UNSIGNED NOT NULL,
	montant SMALLINT UNSIGNED NOT NULL,
	`date` DATETIME NOT NULL,
	FOREIGN KEY (utilisateur) REFERENCES utilisateurs (id) ON DELETE CASCADE,
	FOREIGN KEY (enchere) REFERENCES encheres (id) ON DELETE CASCADE
);

CREATE TRIGGER encheres_nombre_mises_add AFTER INSERT ON mises
	FOR EACH ROW BEGIN
		UPDATE encheres SET nb_mises = COALESCE(nb_mises, 0) + 1 WHERE id = NEW.enchere;
	END;

CREATE TRIGGER encheres_nombre_mises_sub AFTER DELETE ON mises
	FOR EACH ROW BEGIN
		UPDATE encheres SET nb_mises = COALESCE(nb_mises, 0) - 1 WHERE id = OLD.enchere;
	END;

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
	SELECT p.id, p.nom, e.*, c.nom AS nom_categorie
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
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
	categorie MEDIUMINT UNSIGNED NULL REFERENCES categories (id) ON DELETE SET NULL,
	nom VARCHAR(255) NOT NULL,
	description LONGTEXT,
	image_apercu INTEGER UNSIGNED NULL REFERENCES images (id) ON DELETE SET NULL
);

CREATE TABLE images (
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	produit INTEGER UNSIGNED NOT NULL REFERENCES produits (id) ON DELETE CASCADE,
	hash BINARY(20) NOT NULL,
	UNIQUE (produit, hash)
);

CREATE TABLE encheres (
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	produit INTEGER UNSIGNED NOT NULL REFERENCES produits (id) ON DELETE CASCADE,
	cout_mise SMALLINT UNSIGNED NOT NULL,
	prix_public SMALLINT UNSIGNED,
	date_debut DATETIME NOT NULL,
	date_fin DATETIME NOT NULL,
	nb_mises INTEGER UNSIGNED NULL
);

CREATE TABLE mises (
	id BIGINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	utilisateur INTEGER UNSIGNED NOT NULL REFERENCES utilisateurs (id) ON DELETE CASCADE,
	enchere INTEGER UNSIGNED NOT NULL REFERENCES encheres (id) ON DELETE CASCADE,
	montant SMALLINT UNSIGNED NOT NULL,
	`date` DATETIME NOT NULL
);

CREATE TRIGGER encheres_nombre_mises_add AFTER INSERT ON mises
	FOR EACH ROW BEGIN
		UPDATE encheres SET nb_mises = COALESCE(nb_mises, 0) + 1 WHERE id = NEW.enchere;
	END;

CREATE TRIGGER encheres_nombre_mises_sub AFTER DELETE ON mises
	FOR EACH ROW BEGIN
		UPDATE encheres SET nb_mises = COALESCE(nb_mises, 0) - 1 WHERE id = OLD.enchere;
	END;

CREATE TABLE meta_form_fields (
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	label VARCHAR(255) NOT NULL,
	name VARCHAR(255) NOT NULL,
	type VARCHAR(255) NOT NULL,
	order INT UNSIGNED NOT NULL DEFAULT 0
);

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
# Enchères Inversées

## Requis

* PHP 7.1+
* MySQL 5.6+ ou MariaDB 10.1+

Modules PHP :

* MySQL
* PDO
* GD

Sous Debian / Ubuntu :

	sudo apt install php php-mysql php-gd

## Installation

* Créer une nouvelle base de données et utilisateur MySQL ayant les droits sur cette BDD
* Recopier le fichier `config.dist.php` en `config.local.php` et l'éditer pour modifier les paramètres de connexion à MySQL
* Lancer `make install` ou `php install.php` en ligne de commande pour importer la base de données
* Alternativement, importer le fichier `schema.sql` dans la base MySQL

## Lancement du site

Il est possible de lancer la commande `make dev-server` pour lancer un serveur HTTP de développement qui sera disponible sur l'adresse `http://localhost:8080/` pour tester l'application.

Une alternative est de configurer un virtualhost avec son serveur HTTP (Apache, nginx, etc.) et de pointer le *document root* sur le répertoire `www`.

Un compte administrateur par défaut a été ajouté et peut être utilisé avec les identifiants suivants :

* E-mail: admin@admin.fr
* Mot de passe: abcd

Un autre compte utilisateur est disponible :

* E-mail : utilisateur@mail.com
* Mot de passe : abcd

## Architecture du code

### Arborescence

Le projet est conçu autour d'un modèle MVC :

* `src/Projet` contient le code source des classes (modèles)
* `templates` contient les templates HTML/Smarty utilisés pour afficher le site (vues)
* `www` contient les contrôleurs appelés par le serveur web, qui récupèrent les données des modèles et les affiches dans des vues (contrôleurs)

Les modèles utilisent le namespace `Projet`, mais si le site était plus important il serait possible d'avoir plusieurs namespaces et donc plusieurs répertoires dans `src`.

Afin de séparer proprement les différentes parties de l'application il existe également les répertoires secondaires suivants :

* `cache` contient les éléments mis en cache
* `vendor` contient les bibliothèques tiers utilisées par le projet
* `www/static` contient les éléments statiques du front-end (images de logo, styles CSS, code Javascript)
* `www/images` contient les images uploadées (images de produits par exemple)

Les fichiers suivants existent également à la racine :

* `bootstrap.php` est le fichier d'initialisation et liant principal entre modèle, vue et contrôleur, qui va configurer l'application et l'autoloader PHP, ainsi qu'activer le gestionnaire d'erreur
* `config.dist.php` est une configuration d'exemple qui doit être recopiée en `config.local.php` avant d'être modifiée pour s'adapter à la configuration du serveur
* `schema.sql` contient le schéma des tables MySQL et des données d'exemple
* `install.php` importe le contenu de `schema.sql` dans la base de données
* `run_tests.php` lance les tests unitaires
* `template.php` configure le moteur de template et y ajoute quelques fonctions utiles pour ce projet

### Dépendances

Le projet se base sur un ensemble de bibliothèques que j'ai développées depuis 2003, regroupées dans le projet [KD2FW](https://fossil.kd2.org/kd2fw/wiki?name=about). Dans ce projet sont utilisées les bibliothèques suivantes :

* ErrorManager, un gestionnaire d'erreurs et d'exceptions
* Form, qui aide à gérer, valider et sécuriser les formulaires HTML
* DB, une surcouche de simplification à PDO
* Smartyer, un moteur de template compatible avec Smarty
* Image, qui permet d'identifier, redimensionner et appliquer divers traitements à des images

Ces dépendances sont situées dans le répertoire `vendor/KD2/`

### Méta-modélisation et entités

Chacun des modèles (classe) étend la classe `Entity`. Cette classe représente une entité et permet de méta-modéliser de manière générique chacune des entités (un produit, une catégorie, une enchère… = une entité).

Les propriétés de chaque entité sont représentées sous la forme de variables d'objet (qui sont protégées). Chaque propriété correspond à une colonne dans la table MySQL correspondante.

Chaque propriété d'objet reçoit des annotations, présentes dans le docblock (bloc de commentaire de documentation) précédant la proprité, permettant d'indiquer les spécificités de la propriété. Les spécificités possibles sont les suivantes :

* `@field` indique le type de champ utilisé dans un formulaire d'ajout / modification de l'entité
* `@name` est l'intitulé de la propriété
* `@unique` indique que cette propriété est une clé unique dans la table
* `@null` indique que cette propriété peut rester vide (NULL)
* `@references` indique que le champ référence une autre table

Ces spécificités (ou annotations) permettent de construire et valider les formulaires liés aux entités.

Les méthodes d'objet d'une entité s'appliquent à une entité spécifique (création, modification, suppression, lecture, etc.) alors que les méthodes de classe (statiques) d'une entité s'appliquent à toutes les entités (par exemple : liste des entités, recherche, etc.).
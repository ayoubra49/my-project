# API Foodtruck

API à destination des foodtrucks, leur permettant de réserver des emplacements.

## Contraintes:
- La société Hooly dispose de 7 emplacements pour les foodtrucks sauf le vendredi où elle n'en n'a que 6.
- Chaque foodtruck ne peut venir qu'une fois par semaine.
- Un foodtruck ne peut pas réserver pour le jour même ou une date passée.

## Utilisation:
Créer une réservation
Pour créer une réservation, envoyez une requête HTTP POST à l'URL /api/bookings. Les champs suivants doivent être fournis en tant que données JSON dans le corps de la requête :

food_truck_id : l'identifiant du foodtruck
booking_date : la date de la réservation au format YYYY-MM-DD
Si la réservation est créée avec succès, une réponse avec un code HTTP 201 et les détails de la réservation sera retournée. Sinon, une réponse avec un code HTTP 400 et un message d'erreur sera retournée.

## Prérequis

- PHP 8.1
- Composer
- Symfony CLI

## Installation

1. Cloner ce repository : `git clone https://github.com/ayoubra49/my-project.git`
2. Installer les dépendances : `composer install`
3. Configurer les variables d'environnement en créant un fichier `.env.local` et en y ajoutant les variables nécessaires (voir `.env` pour la liste des variables requises)
4. Créer la base de données : `php bin/console doctrine:database:create`
5. Exécuter les migrations : `php bin/console doctrine:migrations:migrate`

## Utilisation

1. Lancer le serveur : `symfony serve`
2. Utiliser l'API en envoyant des requêtes HTTP aux endpoints appropriés. Voir la documentation (public/swagger.yaml) de l'API pour plus d'informations.
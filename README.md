# README

## Introduction

Ce projet a été réalisé dans le cadre de ma formation pour m'initier au framework Symfony. Il ne s'agit pas d'un projet fini mais plutôt d'expérimentation.

## Le projet O'ciné

Il s'agit d'un site pour afficher et gérer les informations de films, avec des commentaires et avis d'utilisateurs.
Il inclut également l'accès à un back-office notamment pour la gestion des roles (admin, manager, user).
Je n'ai pas utilisé bootstrap pour me confronter à la réalisation d'un css très personnel.

## Installation du projet

Après avoir cloné le projet :

-faire la commande `composer install`  
-générer les clés JWT `bin/console lexik:jwt:generate-keypair`  
-créer le fichier .env.local à partir du fichier .env, et y renseigner vos informations de DATABASE_URL.  
-créer la base de données avec `bin/console doctrine:database:create`  
-migrer en base les migrations avec `bin/console doctrine:migrations:migrate`  
-appliquer les fixtures pour remplir le site de données de films avec `bin/console doctrine:fixtures:load --no-interaction`  
-trouver les affiches des films avec l'api *OMDbAPI* `bin/console app:movie:poster`  
-lancer un serveur de dev pour visualiser le site dans le navigateur sur localhost:8000 par exemple `php -S 0.0.0.0:8000 -t public`  

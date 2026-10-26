# Digi-Santé Junior

## Présentation

Digi-Santé Junior est une application web développée avec Symfony dans le cadre de ma formation Développeur Web et Web
Mobile.

Elle permet aux enfants de 8 à 14 ans de suivre leur temps d'écran et de recevoir des conseils pour leur bien-être.

## Fonctionnalités

- Inscription et connexion
- Gestion des profils enfants par les parents
- Définition d'une limite de temps d'écran
- Journal quotidien du temps d'écran
- Signalement des douleurs
- Conseils personnalisés
- Gestion des contenus par l'administrateur

## Technologies utilisées

- PHP 8.5
- Symfony 7.4
- Twig
- HTML5, CSS3, Bootstrap, JavaScript
- MySQL 8.4
- Doctrine ORM
- Docker et Docker Compose
- Git et GitHub

## Installation avec Docker

Depuis le dossier du projet, lancer les commandes suivantes :

```bash
docker compose up -d --build
docker compose exec app composer install
docker compose exec app php bin/console doctrine:database:create --if-not-exists
docker compose exec app php bin/console doctrine:migrations:migrate 
docker compose exec app php bin/console doctrine:fixtures:load 
```

Ouvrir ensuite l'application :

http://localhost:8081

Pour accéder à la base de données avec phpMyAdmin :

http://localhost:8082

Pour arrêter les conteneurs :

```bash
docker compose stop
```

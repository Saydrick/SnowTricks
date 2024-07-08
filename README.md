# SNOWTRICKS - Développez de A à Z le site communautaire SnowTricks

Dans le cadre du projet n°6 de la formation Développeur d'application - PHP/Symfony de OpenClassrooms,
ce site web regroupe de nombreuses figure de snowboard ajoutées par la communauté.


## PRÉREQUIS

- PHP version 8.2 ou supérieur
- Symfony 7.1 ou supérieur
- Apache server version 2.4 ou supérieur
- Composer
- Base de données MYSQL


## INSTALLATION

- Cloner le projet sur GitHub [Lien vers le projet GitHub](https://github.com/Saydrick/SnowTricks) et l’ajouter dans le dossier des projets de votre environnement de serveur apache local avec la commande :
```
git clone https://github.com/Saydrick/SnowTricks.git
```
- Créer une base de données en local nommée "snowtricks" et importer le fichier "snowtricks.sql" qui se trouve à la racine du projet.
- Mettre à jour le fichier `.env` avec les identifiants de connexion à votre base de données.
- Exécuter `composer install` à la racine du projet pour installer les bibliothèques du projet.

## UTILISATION

### Connexion
Se connecter sur le site avec les identifiants de connexion suivants :

- Nom d'utilisateur : user
- mot de passe : 123456

L'envoie de mails est actuellement intercepté par la platforme MailTrap.
Pour tester l'envoie de mails, il faut modifier les informations de connexion dans le fichier `.env` (MAILER_DSN ligne 41) avec vos identifiants.

### Fonctionnalités
Les principales fonctionnalités du projet accessibles suivant le type d'utilisateur connecté sont :

Tous types d'utilisateurs (connectés ou non) :
- Inscription / connexion / déconnexion du site.
- Lecture des articles et des commentaires associés.

Utilisateur connecté :
- Rédaction d'un article.
- Ajout de commentaires sur un article.
- Ajout de médias sur un article.
- Modification et/ou suppression d'un article (si l'utilisateur est l'auteur de l'article).
- Modification et/ou suppression d'un média de l'article (si l'utilisateur est l'auteur de l'article).
- Modification et/ou suppression d'un commentaire (si l'utilisateur est l'auteur du commentaire).

## BIBLIOTHÈQUES UTILISÉES

Twig

## BADGE SYMFONY INSIGHT
[![SymfonyInsight](https://insight.symfony.com/projects/9f7ab497-0c14-4f82-8c4c-5c31caece450/big.svg)](https://insight.symfony.com/projects/9f7ab497-0c14-4f82-8c4c-5c31caece450)

## AUTEUR

BOUZANQUET Cédric

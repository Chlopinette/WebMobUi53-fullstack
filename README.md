# Application de Sondage et de Blog

Ce projet est une application web complète développée avec Laravel et Vue.js. Elle permet aux utilisateurs de créer et gérer des sondages, de voter, ainsi que de lire et publier des articles de blog.

## Fonctionnalités

- **Gestion des Sondages**
  - Création, modification et suppression de sondages.
  - Configuration des options : choix unique ou multiple, visibilité des résultats.
  - Partage facile des sondages via un lien unique.
  - Vote en temps réel avec mise à jour des résultats par polling.
  - Affichage graphique des résultats.

- **Système de Blog**
  - Création et publication d'articles.
  - Consultation des articles avec un système de réactions.

- **Authentification**
  - Inscription et connexion des utilisateurs.
  - Gestion de profil utilisateur avec photo.

## Stack Technique

- **Backend**: Laravel 12
- **Frontend**: Vue.js 3
- **Base de données**: SQLite, MySQL, ou PostgreSQL
- **Styling**: Tailwind CSS
- **Build Tool**: Vite

---

## Prérequis

Avant de commencer, assurez-vous d'avoir les outils suivants installés sur votre machine :

- PHP >= 8.2
- Composer
- Node.js >= 18.x
- npm (ou yarn)
- Une base de données (ex: MySQL, PostgreSQL, ou SQLite)

---

## Installation

Suivez ces étapes pour installer et lancer le projet en local.

### 1. Cloner le Dépôt

```bash
git clone https://github.com/votre-username/votre-repo.git
cd votre-repo
```

### 2. Installer les Dépendances Backend

```bash
composer install
```

### 3. Installer les Dépendances Frontend

```bash
npm install
```

### 4. Configurer l'Environnement

Copiez le fichier d'environnement d'exemple et configurez vos variables.

```bash
cp .env.example .env
```

Ouvrez le fichier `.env` et configurez les informations de votre base de données (`DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).

**Exemple pour SQLite :**
Créez un fichier `database.sqlite` dans le dossier `database/` puis mettez à jour votre `.env` :

```
DB_CONNECTION=sqlite
DB_DATABASE=/path/to/your/project/database/database.sqlite
```

### 5. Générer la Clé d'Application

```bash
php artisan key:generate
```

### 6. Lancer les Migrations

Cette commande créera toutes les tables nécessaires dans votre base de données.

```bash
php artisan migrate
```

### 7. Compiler les Assets Frontend

Cette commande va compiler les fichiers JavaScript et CSS. Laissez-la tourner dans un terminal pendant que vous développez.

```bash
npm run dev
```

### 8. Lancer le Serveur de Développement

Ouvrez un **nouveau terminal** et lancez le serveur Laravel.

```bash
php artisan serve
```

Votre application est maintenant accessible à l'adresse `http://127.0.0.1:8000`.

---

## Utilisation

1.  **Créez un compte** en cliquant sur le bouton "Inscription".
2.  **Connectez-vous** à votre nouveau compte.
3.  **Accédez à la section "Sondages"** via le menu de navigation.
4.  **Créez un nouveau sondage** en remplissant le formulaire.
5.  **Partagez le lien** du sondage avec d'autres utilisateurs pour qu'ils puissent voter.
6.  **Consultez la section "Posts"** pour lire les articles ou en écrire un nouveau.

Profitez de l'application !

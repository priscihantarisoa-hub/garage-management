# 🚀 Installation Locale (Sans Docker)

Puisque Docker a des problèmes de connexion, voici comment installer le projet en local sur Windows.

## 📋 Prérequis

### 1. PHP 8.1+
```powershell
# Vérifier version PHP
php -v

# Si PHP n'est pas installé, utiliser XAMPP ou WampServer
# Ou installer via Chocolatey :
choco install php
```

### 2. Composer
```powershell
# Installer Composer
# Télécharger depuis https://getcomposer.org/Composer-Setup.exe

# Vérifier installation
composer --version
```

### 3. Base de données
**Option A - MySQL (recommandé)**
```powershell
# Via XAMPP/WampServer - MySQL inclus
# Ou installer MySQL Server 8.0+
```

**Option B - SQLite (plus simple)**
```powershell
# SQLite est inclus dans PHP 8.1+
# Aucune installation requise
```

## 🔧 Installation Étape par Étape

### Étape 1: Installer les dépendances
```powershell
cd "C:\Users\HP\Documents\STACY\S5\Mr Rojo\garage-project"
composer install
```

### Étape 2: Configurer l'environnement
```powershell
# Copier le fichier d'environnement
copy .env.example .env

# Générer la clé Laravel
php artisan key:generate
```

### Étape 3: Configurer la base de données

**Option A - MySQL**
```powershell
# 1. Créer la base de données dans MySQL
# CREATE DATABASE garage_db;

# 2. Éditer .env et configurer:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=garage_db
DB_USERNAME=root
DB_PASSWORD=  # Laisser vide si XAMPP
```

**Option B - SQLite (plus facile)**
```powershell
# 1. Créer fichier de base de données
echo "" > database/database.sqlite

# 2. Éditer .env et changer:
DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1  # Commenter cette ligne
# DB_PORT=3306      # Commenter cette ligne  
# DB_DATABASE=garage_db  # Commenter cette ligne
# DB_USERNAME=root  # Commenter cette ligne
# DB_PASSWORD=      # Commenter cette ligne
DB_DATABASE=database/database.sqlite
```

### Étape 4: Créer les tables
```powershell
# Lancer les migrations
php artisan migrate
```

### Étape 5: Insérer les données de test
```powershell
# Lancer les seeders
php artisan db:seed
```

### Étape 6: Démarrer le serveur
```powershell
# Démarrer le serveur Laravel
php artisan serve
```

## 🌐 Accéder à l'application

- **URL**: http://localhost:8000
- **Login**: admin@garage.com
- **Mot de passe**: admin123

## ✅ Vérification

Ouvrez http://localhost:8000 et vérifiez:
- [ ] Page de login s'affiche
- [ ] Connexion avec admin@garage.com / admin123 fonctionne
- [ ] Tableau de bord avec statistiques
- [ ] Menu "Interventions" fonctionne
- [ ] CRUD des interventions fonctionne

## 🛠️ En cas de problème

### Erreur "No application encryption key"
```powershell
php artisan key:generate
```

### Erreur de connexion base de données
- Vérifier que MySQL/XAMPP est démarré
- Vérifier identifiants dans .env
- Essayer l'option SQLite

### Permissions Windows
```powershell
# Donner permissions aux dossiers
icacls storage /grant Everyone:F /T
icacls bootstrap/cache /grant Everyone:F /T
```

## 📊 Fonctionnalités disponibles

Une fois installé, vous aurez:
- ✅ Authentification admin
- ✅ Tableau de bord statistiques  
- ✅ CRUD interventions (8 types)
- ✅ Interface moderne
- ✅ Base de données complète

**L'installation locale est plus rapide et fiable que Docker !** 🚀

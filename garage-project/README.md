# Garage Backoffice - Laravel

Backoffice web pour la gestion d'un garage automobile avec Laravel et Docker.

## Fonctionnalités

- 🔐 **Authentification admin** simple
- 📊 **Tableau de bord** avec statistiques en temps réel
- 🔧 **CRUD complet** pour les 8 types d'interventions
- 📈 **Statistiques** : montant total, nombre de clients, réparations en cours
- 🎨 **Interface moderne** avec Tailwind CSS
- 🐳 **Configuration Docker** facile

## Types d'interventions

1. **Frein** - Changement des plaquettes de frein
2. **Vidange** - Vidange moteur avec filtre
3. **Filtre** - Remplacement filtre à air
4. **Batterie** - Changement batterie
5. **Amortisseurs** - Remplacement amortisseurs
6. **Embrayage** - Changement kit d'embrayage
7. **Pneus** - Montage et équilibrage
8. **Refroidissement** - Vidange système de refroidissement

## Installation

### Prérequis
- Docker et Docker Compose
- Git

### Démarrage rapide

1. **Cloner le projet**
```bash
git clone <votre-repo>
cd garage-project
```

2. **Configurer l'environnement**
```bash
cp .env.example .env
```

3. **Démarrer les conteneurs**
```bash
docker-compose up -d --build
```

4. **Installer les dépendances et configurer Laravel**
```bash
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
```

5. **Accéder à l'application**
- URL: http://localhost:8000
- Login: admin@garage.com
- Mot de passe: admin123

## Structure du projet

```
├── app/
│   ├── Http/Controllers/
│   │   ├── Auth/
│   │   ├── DashboardController.php
│   │   └── InterventionController.php
│   ├── Models/
│   │   ├── Intervention.php
│   │   ├── Client.php
│   │   └── Reparation.php
│   └── Middleware/
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/views/
│   ├── layouts/
│   ├── auth/
│   ├── interventions/
│   └── dashboard.blade.php
├── docker-compose.yml
├── Dockerfile
└── README.md
```

## Commandes utiles

```bash
# Démarrer les conteneurs
docker-compose up -d

# Arrêter les conteneurs
docker-compose down

# Voir les logs
docker-compose logs -f app

# Exécuter une commande dans le conteneur app
docker-compose exec app bash

# Nouvelle migration
docker-compose exec app php artisan make:migration create_table

# Lancer les seeders
docker-compose exec app php artisan db:seed
```

## Base de données

Le projet utilise 3 tables principales :

- **interventions** : Catalogue des services avec prix et durée
- **clients** : Informations sur les clients et leurs véhicules  
- **reparations** : Suivi des réparations en cours/terminées

## Authentification

Pour le développement, une authentification simple est configurée :
- Email : `admin@garage.com`
- Mot de passe : `admin123`

## Prochaines étapes

- [ ] Intégration Firebase pour les notifications
- [ ] API REST pour l'application mobile
- [ ] FrontOffice public pour voir les réparations
- [ ] Système de paiement intégré
- [ ] Export PDF des factures

## Technologies utilisées

- **PHP 8.1** avec Laravel 10
- **MySQL 8.0** 
- **Nginx** comme reverse proxy
- **Tailwind CSS** pour le style
- **Docker & Docker Compose**

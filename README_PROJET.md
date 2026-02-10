# 🚗 Garage Management System - Projet Complet

## 📊 Résumé du Projet

Système de gestion de garage automobile avec 4 composants :
- **Backend** : Laravel REST API
- **Frontend Web** : Vue.js
- **Application Mobile** : React Native (Expo)
- **Jeu Godot** : Simulation de garage HTML5

## 🏗️ Architecture

```
garage-laravel/
├── garage-project/          # Backend Laravel
│   ├── app/
│   │   ├── Http/Controllers/Api/
│   │   │   ├── AuthController.php       # Authentification
│   │   │   ├── RepairController.php     # Réparations
│   │   │   ├── PaymentController.php    # Paiements
│   │   │   ├── ClientController.php     # Clients
│   │   │   └── InterventionController.php # Interventions
│   │   └── Models/
│   ├── routes/api.php        # Routes API REST
│   ├── docker-compose.yml     # Docker configuration
│   └── GARAGE_API.postman_collection.json
│
├── frontend/                 # Frontend Vue.js
│   ├── src/views/
│   │   ├── Home.vue
│   │   ├── Login.vue         # ✨ Nouveau
│   │   ├── Clients.vue       # ✨ Nouveau
│   │   ├── Statistics.vue    # ✨ Nouveau
│   │   ├── Backoffice.vue    # ✨ Nouveau
│   │   ├── ClientHistory.vue
│   │   └── Interventions.vue
│   └── src/router/
│
├── mobile/                  # Application Mobile Expo
│   ├── App.js
│   ├── LoginScreen.js       # ✨ Connecté API
│   ├── RegisterScreen.js    # ✨ Connecté API
│   ├── RepairsScreen.js     # ✨ Connecté API
│   ├── api.js               # ✨ Nouveau service API
│   └── firebaseConfig.js
│
├── jeu-godot/               # Jeu Godot HTML5
│   ├── UI.gd               # ✨ Amélioré
│   ├── CarController.gd
│   ├── Voiture1.gd
│   ├── Slot.gd
│   └── README_JEU.md       # ✨ Nouveau
│
└── MCD.md                   # Modèle Conceptuel de Données
```

## 🚀 Installation Rapide

### 1. Backend Laravel

```bash
cd garage-project

# Installer les dépendances
docker compose exec app composer install

# Configurer l'environnement
cp .env.example .env
php artisan key:generate

# Lancer les migrations
docker compose exec app php artisan migrate

# Lancer les seeders (interventions)
docker compose exec app php artisan db:seed

# Démarrer le serveur
docker compose up -d
```

**URL API** : `http://localhost:8000/api`

### 2. Frontend Vue.js

```bash
cd frontend

# Installer les dépendances
npm install

# Lancer le serveur de développement
npm run dev
```

**URL Frontend** : `http://localhost:5173`

### 3. Application Mobile Expo

```bash
cd mobile

# Installer les dépendances
npm install

# Lancer Expo
npx expo start

# Générer l'APK (Android)
npx expo build:android -t apk
```

### 4. Jeu Godot

```bash
# Ouvrir Godot Engine 4.x
# Importer le dossier jeu-godot
# Cliquer sur "Play"
```

## 📡 Documentation API

### Authentification

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| POST | `/api/auth/login` | Connexion |
| POST | `/api/auth/register` | Inscription |
| GET | `/api/auth/profile` | Profil utilisateur |
| POST | `/api/auth/logout` | Déconnexion |

### Interventions (Public)

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| GET | `/api/interventions` | Liste toutes les interventions |
| GET | `/api/interventions/types` | Liste les 8 types |
| GET | `/api/interventions/{id}` | Détails d'une intervention |

### Réparations (Protégé)

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| GET | `/api/repairs` | Liste les réparations |
| GET | `/api/repairs/active` | Réparations actives (max 3) |
| POST | `/api/repairs` | Créer une réparation |
| PUT | `/api/repairs/{id}/status` | Mettre à jour le statut |
| GET | `/api/repairs/stats` | Statistiques |

### Paiements (Protégé)

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| POST | `/api/payments/pay/{id}` | Payer une réparation |
| GET | `/api/payments` | Liste des paiements |
| GET | `/api/payments/stats` | Statistiques paiements |

## 🎯 Fonctionnalités Implémentées

### Backend (Laravel)
- ✅ REST API complète
- ✅ Authentification Sanctum
- ✅ Gestion des clients
- ✅ Gestion des interventions (8 types)
- ✅ Gestion des réparations avec slots (max 3)
- ✅ Système de paiements
- ✅ Relations Client → Réparations → Interventions
- ✅ Synchronisation Firebase (existante)

### Frontend (Vue.js)
- ✅ Dashboard avec statistiques
- ✅ Page de connexion
- ✅ Gestion des clients
- ✅ Tableau de bord Backoffice
- ✅ Visualisation des slots de réparation
- ✅ Historique des réparations

### Mobile (Expo/React Native)
- ✅ Écrans Login/Register
- ✅ Liste des réparations
- ✅ Notifications push (existantes)
- ✅ Paiement (interface)
- ✅ Service API intégré

### Jeu Godot
- ✅ Sélection et déplacement des voitures
- ✅ Placement dans 3 slots
- ✅ Menu de réparations (8 types)
- ✅ Barre de progression
- ✅ Système de paiement
- ✅ UI avec statistiques

## 📱 Captures d'Écran (à ajouter)

- [ ] Login Web
- [ ] Dashboard
- [ ] Backoffice
- [ ] Mobile Login
- [ ] Mobile Repairs
- [ ] Jeu Godot

## 🧪 Tests Postman

Importer la collection : `garage-project/GARAGE_API.postman_collection.json`

Variables d'environnement :
```
baseUrl: http://localhost:8000/api
token: <token après login>
```

## 📦 Livrables du Projet

1. **Code source** (ce dépôt)
2. **Collection Postman** (`GARAGE_API.postman_collection.json`)
3. **APK généré** (`mobile/app-debug.apk` après build)
4. **Instructions Docker** (`garage-project/INSTALLATION_LOCALE.md`)
5. **MCD** (`MCD.md`)

## 🔧 Technologies Utilisées

| Composant | Technologie |
|-----------|-------------|
| Backend | Laravel 10 + PHP 8.1 |
| Base de données | MySQL (Docker) |
| Authentification | Laravel Sanctum |
| Mobile | React Native + Expo |
| Frontend | Vue.js 3 + Vite |
| Jeu | Godot Engine 4.x |
| Notifications | Firebase Cloud Messaging |
| Conteneurisation | Docker + Docker Compose |

## 📝 Notes

- Le mode TEST est activé par défaut dans l'app mobile (`TEST_MODE = true`)
- Pour utiliser la vraie API, mettre `TEST_MODE = false` dans `mobile/api.js`
- Les tokens d'authentification sont stockés dans AsyncStorage
- Le jeu Godot fonctionne indépendamment mais peut être connecté à l'API

## 📞 Auteurs

Projet développé dans le cadre d'un cours de gestion de garage automobile.

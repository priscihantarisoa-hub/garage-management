# 📋 RAPPORT DE PROJET - Garage Management System

---

## 1. MCD (Modèle Conceptuel de Données)

Voir fichier : `MCD.md`

```
┌──────────────────┐                    ┌──────────────────┐
│      USER        │                    │      CLIENT      │
├──────────────────┤                    ├──────────────────┤
│ id               │1,n                │ id               │
│ name             │───────────────────│ nom              │1,1
│ email            │   peut avoir      │ email            │
│ password         │                    │ telephone        │
│ push_token       │                    │ voiture_marque   │
└──────────────────┘                    │ voiture_modele   │
                                        │ user_id (FK)     │
                                        └──────────────────┘
                                                  │
                                                  │1,n
                                                  ▼
┌─────────────────────────────────────────────────┐
│                   REPARATION                    │
├─────────────────────────────────────────────────┤
│ id                                               │
│ client_id (FK)                                  │
│ intervention_id (FK)                             │
│ statut (en_attente, en_cours, termine, paye)     │
│ slot (1, 2, 3, ou attente)                     │
│ debut_reparation                                 │
│ fin_reparation                                  │
│ montant_total                                    │
└────────────────────────┬────────────────────────┘
                         │1,1
                         ▼
┌────────────────────────┐┌────────────────────────┐
│       PAYMENT          ││    INTERVENTION        │
├────────────────────────┤├────────────────────────┤
│ id                     ││ id                     │
│ reparation_id (FK)     ││ nom                   │
│ montant                ││ description            │
│ date_paiement          ││ prix                  │
│ methode                ││ duree (secondes)      │
│ statut                 ││ type                  │
└────────────────────────┘└────────────────────────┘
```

---

## 2. SCÉNARIOS AVEC EXPLICATIONS

### 📱 Scénario 1 : API Backend Laravel

**Description** : Tester les API REST avec Postman

**Démonstration** :
1. Importer la collection `GARAGE_API.postman_collection.json`
2. Exécuter `POST /api/auth/login`
3. Copier le token dans la variable `{{token}}`
4. Tester `GET /api/interventions/types`
5. Tester `GET /api/stats`

**Screenshot à inclure** : Capture Postman avec réponse JSON des interventions

---

### 💻 Scénario 2 : Frontend Vue.js - Backoffice

**Description** : Gestion des 3 slots de réparation

**Démonstration** :
1. Lancer : `cd frontend && npm run dev`
2. Ouvrir `http://localhost:5173/backoffice`
3. Voir les 3 slots de réparation
4. Changer le statut d'une réparation
5. Voir les statistiques

**Screenshot à inclure** : Page Backoffice avec les 3 slots

---

### 📱 Scénario 3 : Frontend Vue.js - Statistics

**Description** : Tableau de bord statistiques

**Démonstration** :
1. Cliquer sur `/statistics`
2. Voir :
   - Total des interventions
   - Nombre de clients
   - Places disponibles (3 max)
   - Chiffre d'affaires

**Screenshot à inclure** : Page Statistics avec les cartes de stats

---

### 📱 Scénario 4 : Application Mobile

**Description** : Login et liste des réparations

**Démonstration** :
1. Lancer : `cd mobile && npx expo start`
2. Montrer l'écran de connexion
3. Montrer la liste des réparations

**Screenshot à inclure** : Écrans Login et Repairs

---

### 🎮 Scénario 5 : Jeu Godot

**Description** : Simulation de garage avec contrôles

**Démonstration** :
1. Ouvrir Godot 4.x → Importer `jeu-godot/` → Play
2. Contrôles :
   - **Tab** : Sélectionner voiture
   - **ZQSD** : Déplacer
   - **Espace** : Placer dans slot
   - **E** + **1-8** : Réparer

**Screenshot à inclure** : Jeu en action avec une réparation

---

## 3. LIEN GIT

```
https://github.com/priscihantarisoa-hub/garage-management
```

---

## 4. ZIP CONTENANT LES SOURCES

**Fichier** : `garage_projet_rendu.zip` ✅

**Commande utilisée** :
```bash
cd garage-laravel
zip -r garage_projet_rendu.zip . -x "**/node_modules/*" "**/vendor/*"
```

**Contenu du ZIP** :
```
garage-laravel/
├── garage-project/          # Backend Laravel API
├── frontend/                # Frontend Vue.js
├── mobile/                  # Application Mobile Expo
├── jeu-godot/               # Jeu Godot HTML5
├── MCD.md                   # Modèle de données
├── README_PROJET.md         # Documentation complète
├── RENDU_AUJOURDHUI.md      # Guide urgent
└── ETAPES_RENDU.md         # Guide étapes
```

---

## 5. INSTRUCTIONS POUR LANCER LES APPLICATIONS

### Backend Laravel (Docker)

```bash
cd garage-project

# Lancer Docker
docker compose up -d

# Vérifier que ça marche
curl http://localhost:8000/api/interventions/types

# Commandes utiles
docker compose logs -f app        # Voir les logs
docker compose exec app php artisan migrate  # Migrations
docker compose down               # Arrêter
```

**URL API** : `http://localhost:8000/api`

---

### Frontend Vue.js

```bash
cd frontend
npm install
npm run dev
```

**URL** : `http://localhost:5173`

---

### Application Mobile Expo

```bash
cd mobile
npm install
npx expo start

# Pour générer l'APK
npx expo build:android -t apk
```

**APK généré** : `mobile/android-app/build/outputs/apk/`

---

### Jeu Godot

```bash
# Ouvrir Godot Engine 4.x
# Importer le dossier jeu-godot/
# Cliquer sur "Play" (F5)
```

---

## 6. COLLECTION POSTMAN

**Fichier** : `garage-project/GARAGE_API.postman_collection.json` ✅

**Variables à configurer** :
```
baseUrl: http://localhost:8000/api
token: <token après login>
```

**Endpoints principaux** :
- `POST /api/auth/login` - Connexion
- `GET /api/interventions/types` - Types d'interventions
- `GET /api/repairs/active` - Réparations actives (3 max)
- `GET /api/stats` - Statistiques
- `POST /api/payments/pay/{id}` - Payer une réparation

---

## 7. TODO LIST AVEC AFFECTATION MEMBRE

| # | Tâche | Membre | Statut |
|---|-------|--------|--------|
| 1 | Analyse et MCD | Équipe | ✅ Terminé |
| 2 | Backend Laravel API | Équipe | ✅ Terminé |
| 3 | Frontend Vue.js | Équipe | ✅ Terminé |
| 4 | Application Mobile Expo | Équipe | ✅ Terminé |
| 5 | Jeu Godot | Équipe | ✅ Terminé |
| 6 | Documentation technique | Équipe | ✅ Terminé |
| 7 | Tests API Postman | Équipe | ✅ Terminé |
| 8 | APK généré | Priscilla | 🔄 En cours |
| 9 | Présentation finale | Équipe | À faire |

> **Note** : La todo list détaillée avec affectation par membre est disponible dans le fichier Excel du projet.

---

## 8. APK GÉNÉRÉ

**⚠️ Note importante** : La génération de l'APK nécessite un compte Expo et EAS Build (service cloud).

**Pour générer l'APK (30-60 minutes sur les serveurs Expo) :**

```bash
cd mobile

# 1. Se connecter à Expo (si pas déjà fait)
npx expo login

# 2. Configurer EAS (une fois)
npx eas build:configure

# 3. Lancer la build APK
npx eas build -p android --profile apk
```

**Alternative plus rapide - APK de développement :**

```bash
cd mobile
npx expo start
```

Puis scannez le QR code avec l'application Expo Go sur votre téléphone.

**Emplacement après build** : Lien de téléchargement envoyé par email Expo

---

## ✅ CHECKLIST FINALE

- [x] MCD
- [x] Scénarios documentés
- [x] Lien GIT : https://github.com/priscihantarisoa-hub/garage-management
- [x] ZIP avec sources (sans lib)
- [x] Instructions Docker
- [x] Collection Postman
- [x] Todo list affectée
- [ ] APK généré

---

## 📞 INFORMATIONS

**Projet** : Garage Management System
**Technologies** : Laravel, Vue.js, React Native, Godot, Docker
**Auteurs** : Équipe de projet
**Date** : 2024

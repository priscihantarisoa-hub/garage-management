# 📦 GUIDE DE RENDU RAPIDE - À FAIRE AUJOURD'HUI

## 🚨 URGENT - Ce qu'il faut vérifier maintenant

### 1. Lancer le Backend (5 min)

```bash
cd garage-project
docker compose down
docker compose up -d
```

Vérifier que ça marche :
```bash
curl http://localhost:8000/api/interventions/types
```

### 2. Tester une API (2 min)

Aller sur **Postman** → Importer `garage-project/GARAGE_API.postman_collection.json`

Faire un login :
```
POST http://localhost:8000/api/auth/login
Body: {
  "email": "test@example.com",
  "password": "password"
}
```

Copier le token dans la variable `{{token}}`

### 3. Vérifier le Frontend Vue.js (3 min)

```bash
cd frontend
npm install
npm run dev
```

Aller sur `http://localhost:5173`

### 4. APK Mobile (5 min - si Expo installé)

```bash
cd mobile
npx expo install
npx expo build:android -t apk
```

L'APK sera dans `mobile/android-app/build/outputs/apk/`

### 5. Zip du Projet (2 min)

```bash
# Créer le zip sans node_modules
cd garage-laravel
zip -r garage_projet.zip . -x "**/node_modules/*" "**/vendor/*" "**/.git/*"
```

## ✅ Checklist Finale

- [ ] Docker backend lancé et API accessible
- [ ] Collection Postman importée et testée
- [ ] Frontend Vue.js lancé
- [ ] APK généré (ou prêt à générer)
- [ ] README_PROJET.md lu
- [ ] MCD.md prêt pour la présentation

## 📞 Commandes Rapides à Connaître

```bash
# Backend
cd garage-project
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed

# Frontend
cd frontend
npm run dev

# Mobile
cd mobile
npx expo start
npx expo build:android -t apk 

# Logs
cd garage-project
docker compose logs -f app
```

## 🎮 Jeu Godot

Pas besoin de compilation :
- Ouvrir Godot 4.x
- Importer `jeu-godot/`
- Cliquer sur "Play"

## 📋 Fichiers à Inclure dans le Rendu

1. ✅ Code complet (ce dossier)
2. ✅ `GARAGE_API.postman_collection.json`
3. ✅ `MCD.md` (déjà existant)
4. ✅ APK mobile (si généré)
5. ✅ Instructions : `README_PROJET.md`

## 🎯 Points Clés pour la Présentation

**Backend** : 8 types d'interventions + 3 slots maximum
**Frontend** : Backoffice avec gestion des slots
**Mobile** : Login + liste réparations
**Jeu** : Réparer avec touches 1-8

**Allez-y, vous avez tout ce qu'il faut ! 💪**

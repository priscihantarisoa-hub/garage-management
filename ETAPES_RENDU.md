# 📋 ÉTAPES POUR LE RENDU - SUIVEZ CES INSTRUCTIONS

## ÉTAPE 1: Vérifier que tout est prêt (2 min)

```bash
cd garage-laravel

# Vérifier les fichiers créés
dir *.md
dir garage-project/*.json
dir garage-project/app/Http/Controllers/Api/
dir frontend/src/views/
dir mobile/*.js
```

**Vérifiez que ces fichiers existent :**
- ✅ README_PROJET.md
- ✅ RENDU_AUJOURDHUI.md
- ✅ MCD.md
- ✅ GARAGE_API.postman_collection.json
- ✅ RepairController.php
- ✅ PaymentController.php
- ✅ AuthController.php
- ✅ Login.vue
- ✅ Clients.vue
- ✅ Statistics.vue
- ✅ Backoffice.vue
- ✅ api.js (mobile)

## ÉTAPE 2: Créer le ZIP (3 min)

```bash
cd garage-laravel

# Créer le zip sans node_modules
zip -r garage_project_rendu.zip . -x "**/node_modules/*" "**/vendor/*" "**/.git/*" "**/docker-compose/volumes/*"
```

## ÉTAPE 3: Lister les livrables (1 min)

**Ce qui doit être dans votre rendu :**

1. ✅ **Code source complet** - Dossier `garage-laravel/`
2. ✅ **Collection Postman** - `garage-project/GARAGE_API.postman_collection.json`
3. ✅ **MCD** - `MCD.md`
4. ✅ **README** - `README_PROJET.md`
5. ✅ **Instructions Docker** - `garage-project/INSTALLATION_LOCALE.md`

## ÉTAPE 4: Scénarios à présenter (pour l'oral)

**Scénario 1 : Connexion admin**
```
1. Aller sur /backoffice
2. Voir les 3 slots de réparation
3. Changer le statut d'une réparation
```

**Scénario 2 : Statistiques**
```
1. Aller sur /statistics
2. Voir le montant total des interventions
3. Nombre de clients
4. Places disponibles
```

**Scénario 3 : API Postman**
```
1. Importer la collection
2. POST /auth/login
3. GET /interventions/types
4. GET /stats
```

**Scénario 4 : Jeu Godot**
```
1. Ouvrir Godot
2. Cliquer Play
3. Sélectionner une voiture (Tab)
4. Placer dans un slot (Espace)
5. Réparer (touches 1-8)
```

## ÉTAPE 5: Commandes à connaître

| Action | Commande |
|--------|----------|
| Lancer Docker | `cd garage-project && docker compose up -d` |
| Tester API | `curl http://localhost:8000/api/interventions/types` |
| Lancer Frontend | `cd frontend && npm run dev` |
| Logs Docker | `cd garage-project && docker compose logs -f` |

## ✅ CHECKLIST FINALE

- [ ] ZIP créé
- [ ] Collection Postman prête
- [ ] MCD imprimé/lu
- [ ] README lu
- [ ] Scénarios d'oral préparés
- [ ] Commandes Docker pratiquées

---

**Allez-y, vous avez tout ce qu'il faut ! 🎓**

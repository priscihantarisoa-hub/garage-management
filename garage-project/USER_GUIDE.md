# 📋 Guide Utilisateur - Garage Backoffice

## 🚀 **Table des matières**

1. [Introduction](#introduction)
2. [Installation](#installation)
3. [Connexion](#connexion)
4. [Dashboard](#dashboard)
5. [Gestion des Interventions](#gestion-des-interventions)
6. [Gestion des Réparations](#gestion-des-réparations)
7. [Export de données](#export-de-données)
8. [Firebase Synchronisation](#firebase-synchronisation)
9. [Dépannage](#dépannage)

---

## 🎯 **Introduction**

**Garage Backoffice** est une application web complète pour la gestion d'un garage automobile avec synchronisation temps réel Firebase.

### **Fonctionnalités principales :**
- ✅ **Dashboard analytique** avec graphiques en temps réel
- ✅ **Gestion interventions** CRUD complet
- ✅ **Gestion réparations** (limite 3 voitures simultanées)
- ✅ **Synchronisation Firebase** automatique
- ✅ **Export PDF/Excel** des statistiques
- ✅ **Interface responsive** mobile/tablette

---

## 🔧 **Installation**

### **Prérequis**
- Docker Desktop installé
- Git
- Navigateur web moderne

### **Étapes d'installation**

1. **Cloner le projet**
   ```bash
   git clone <repository-url>
   cd garage-project
   ```

2. **Démarrer Docker**
   ```bash
   docker-compose up -d
   ```

3. **Installer les dépendances**
   ```bash
   docker exec -w /var/www garage-app composer install
   ```

4. **Configurer la base de données**
   ```bash
   docker exec -w /var/www garage-app php artisan migrate
   ```

5. **Accéder à l'application**
   - URL : `http://localhost:8000`
   - Login : `admin@garage.com`
   - Mot de passe : `admin123`

---

## 🔑 **Connexion**

### **Page de connexion**
1. Allez sur `http://localhost:8000/login`
2. Entrez vos identifiants
3. Cliquez sur "Se connecter"

### **Identifiants par défaut**
- **Email** : `admin@garage.com`
- **Mot de passe** : `admin123`

### **Sécurité**
- 🔒 Session sécurisée avec timeout 2 heures
- 🔒 Protection CSRF activée
- 🔒 Validation des inputs côté serveur

---

## 📊 **Dashboard**

### **Accès**
Cliquez sur **"Tableau de bord"** dans le menu de navigation.

### **Statistiques affichées**
- 📈 **Total réparations** : Nombre total de réparations
- 👥 **Nombre de clients** : Clients uniques
- 💰 **Chiffre d'affaires** : Revenus totaux
- ⏰ **Réparations en cours** : X/3 (limite respectée)
- ✅ **Réparations terminées aujourd'hui**
- ⚠️ **Paiements en attente**

### **Graphiques**
- 📊 **Revenus par statut** : Payé vs En attente
- 🍩 **Types d'interventions** : Répartition des services

### **Actualisation automatique**
- 🔄 **Toutes les 30 secondes** : Mise à jour automatique
- 📱 **Optimisé mobile** : Graphiques adaptés

### **Export des données**
- 📄 **Export PDF** : Rapport complet avec tableaux
- 📊 **Export Excel** : Feuilles multiples (Statistiques, Revenus, Interventions)

---

## 🔧 **Gestion des Interventions**

### **Accès**
Cliquez sur **"Interventions"** dans le menu.

### **Actions disponibles**

#### **Créer une intervention**
1. Cliquez sur **"Nouvelle intervention"**
2. Remplissez le formulaire :
   - **Nom** (requis, 2-255 caractères)
   - **Description** (requis, 10-1000 caractères)
   - **Prix** (requis, format numérique)
   - **Durée** (requis, en minutes)
   - **Type** (requis, liste déroulante)
   - **Actif** (optionnel)
3. Cliquez sur **"Enregistrer"**

#### **Modifier une intervention**
1. Cliquez sur l'icône ✏️ dans la liste
2. Modifiez les champs nécessaires
3. Cliquez sur **"Mettre à jour"**

#### **Supprimer une intervention**
1. Cliquez sur l'icône 🗑️ dans la liste
2. Confirmez la suppression

#### **Types d'interventions**
- 🛢️ **Vidange**
- 🛑 **Frein**
- ❄️ **Climatisation**
- ⚙️ **Moteur**
- 🏚️ **Carrosserie**
- 🔌 **Électricité**
- 🔧 **Entretien**
- 🔍 **Diagnostic**
- 📦 **Autre**

---

## 🚗 **Gestion des Réparations**

### **Accès**
Cliquez sur **"Réparations"** dans le menu.

### **Règle importante**
⚠️ **Maximum 3 voitures simultanées** en réparation

### **Workflow des réparations**

#### **États possibles**
1. **En attente** 🟡 : Réparation planifiée
2. **En cours** 🔵 : Réparation active
3. **Terminée** 🟢 : Réparation finie
4. **Payée** 💰 : Paiement enregistré

#### **Créer une réparation**
1. Cliquez sur **"Nouvelle réparation"**
2. Sélectionnez le **client** existant
3. Choisissez l'**intervention**
4. Assignez un **créneau** (1, 2 ou 3)
5. Définissez le **montant total**
6. Cliquez sur **"Créer"**

#### **Mettre à jour le statut**
1. Dans la liste des réparations
2. Cliquez sur le bouton de transition :
   - 🟡 **En attente** → 🔵 **En cours**
   - 🔵 **En cours** → 🟢 **Terminée**
   - 🟢 **Terminée** → 💰 **Payée**

#### **Informations affichées**
- 📋 **Informations client** : Nom, téléphone, véhicule
- 🔧 **Intervention** : Type et description
- 💰 **Montant** : Prix total en Ariary
- 📅 **Dates** : Création et fin de réparation
- 📊 **Statut** : État actuel avec couleur

---

## 📤 **Export de données**

### **Depuis le Dashboard**
1. Allez dans le **Dashboard**
2. Cliquez sur **"Export PDF"** ou **"Export Excel"**
3. Le fichier se télécharge automatiquement

### **Contenu des exports**

#### **PDF**
- 📄 **Rapport complet** avec :
  - Date de génération
  - Tableau des statistiques
  - Format professionnel

#### **Excel**
- 📊 **3 feuilles** :
  - **Statistiques** : Tous les indicateurs
  - **Revenus** : Payé vs En attente
  - **Interventions** : Top 5 des services

### **Nomination des fichiers**
- Format : `garage-statistiques-AAAA-MM-JJ.pdf/xlsx`
- Exemple : `garage-statistiques-2026-02-03.pdf`

---

## 🔥 **Firebase Synchronisation**

### **Qu'est-ce que Firebase ?**
Firebase est une base de données temps réel qui synchronise automatiquement vos données.

### **Données synchronisées**
- ✅ **Réparations** : Création, mise à jour, suppression
- ✅ **Interventions** : Modifications en temps réel
- ✅ **Notifications** : Alertes instantanées

### **Types de notifications**
- 🔔 **Nouvelle réparation** créée
- 🔄 **Changement de statut** de réparation
- 💰 **Paiement** enregistré
- 🔧 **Intervention** mise à jour
- ⚠️ **Alertes système**

### **Vérification**
1. Testez la connexion : `http://localhost:8000/test/firebase`
2. Vérifiez dans **Firebase Console** → **Realtime Database**
3. Les données doivent apparaître en temps réel

---

## 🛠️ **Dépannage**

### **Problèmes courants**

#### **Docker ne démarre pas**
```bash
# Vérifier Docker Desktop
docker --version

# Redémarrer les services
docker-compose down
docker-compose up -d
```

#### **Page blanche**
```bash
# Vider le cache
docker exec -w /var/www garage-app php artisan cache:clear
docker exec -w /var/www garage-app php artisan config:clear
```

#### **Erreur de connexion Firebase**
1. Vérifiez les variables `.env`
2. Testez avec : `http://localhost:8000/test/firebase`
3. Consultez les logs : `storage/logs/laravel.log`

#### **Validation des formulaires**
- ✅ **Champs requis** : Vérifiez tous les champs obligatoires
- ✅ **Formats** : Respectez les formats indiqués
- ✅ **Limites** : Respectez les longueurs maximales

### **Messages d'erreur fréquents**

#### **"Ce créneau horaire est déjà occupé"**
- 🚗 **Solution** : Choisissez un autre créneau (1, 2 ou 3)
- 📋 **Règle** : Maximum 3 réparations simultanées

#### **"Format d'immatriculation invalide"**
- ✅ **Format correct** : `ABC123` ou `1234ABC`
- 🔤 **Lettres majuscules** + **Chiffres**

#### **"Numéro de téléphone invalide"**
- 📞 **Formats acceptés** : `0321234567` ou `321234567`
- 🇲🇬 **Indicatifs malgaches** : 32, 33, 34

### **Support technique**

#### **Logs d'erreurs**
```bash
# Voir les logs récents
docker exec -w /var/www garage-app tail -f storage/logs/laravel.log
```

#### **Test de connexion**
```bash
# Test Firebase
curl http://localhost:8000/test/firebase

# Test base de données
docker exec -w /var/www garage-app php artisan tinker
>>> \App\Models\Client::count();
```

---

## 📱 **Utilisation Mobile**

### **Navigation**
- 📱 **Menu hamburger** : Cliquez sur ☰ en haut à gauche
- 👆 **Navigation tactile** : Boutons optimisés pour le touch
- 🔄 **Swipe** : Support des gestes tactiles

### **Performance**
- ⚡ **Graphiques optimisés** : Pas d'animations sur mobile
- 📊 **Données allégées** : Affichage essentiel uniquement
- 🚀 **Chargement rapide** : Moins de 3 secondes

---

## 🔐 **Sécurité**

### **Mesures de sécurité**
- 🔒 **CSRF Token** : Protection contre les attaques
- 🔐 **Validation serveur** : Tous les inputs sont validés
- 🛡️ **Session sécurisée** : Timeout automatique
- 🔍 **Input sanitization** : Protection XSS

### **Bonnes pratiques**
- 🔄 **Déconnexion** : Cliquez sur "Déconnexion" après utilisation
- 🔑 **Mot de passe** : Changez le mot de passe par défaut
- 📱 **HTTPS** : Utilisez HTTPS en production

---

## 📞 **Contact Support**

### **Pour obtenir de l'aide**
1. 📋 **Consultez ce guide** d'abord
2. 🔍 **Vérifiez les logs** pour les erreurs
3. 📧 **Contactez l'administrateur** si nécessaire

### **Informations utiles**
- 🌐 **URL locale** : `http://localhost:8000`
- 📊 **Dashboard** : `http://localhost:8000/dashboard`
- 🔧 **Interventions** : `http://localhost:8000/interventions`
- 🚗 **Réparations** : `http://localhost:8000/admin/repairs`

---

## 🎉 **Conclusion**

**Garage Backoffice** est maintenant prêt à être utilisé !

### **Points forts**
- ✅ **Interface moderne** et intuitive
- ✅ **Synchronisation temps réel** avec Firebase
- ✅ **Export professionnel** des données
- ✅ **Responsive design** mobile/tablette
- ✅ **Sécurité avancée** et validation

### **Prochaines étapes**
1. 🚀 **Explorez** toutes les fonctionnalités
2. 📊 **Testez** les exports
3. 🔥 **Vérifiez** la synchronisation Firebase
4. 📱 **Testez** sur mobile/tablette

**Bon usage de votre Garage Backoffice !** 🎯✨

---

*Guide utilisateur version 1.0 - Dernière mise à jour : 3 Février 2026*

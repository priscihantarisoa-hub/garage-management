# Modèle Conceptuel de Données (MCD) - Garage Laravel

## Diagramme des Entités et Relations

```
┌─────────────────────────────────────────────────────────────────────────────────────┐
│                              MODELE CONCEPTUEL DE DONNEES                            │
│                              Projet Garage - Laravel                                │
└─────────────────────────────────────────────────────────────────────────────────────┘

┌──────────────────┐                    ┌──────────────────┐
│      USER        │                    │      CLIENT      │
├──────────────────┤                    ├──────────────────┤
│ id               │1,n                │ id               │
│ name             │───────────────────│ nom              │1,1
│ email            │   peut avoir      │ email            │
│ password         │                    │ telephone        │
│ push_token       │                    │ voiture_marque   │
│ created_at       │                    │ voiture_modele   │
│ updated_at       │                    │ voiture_annee    │
└──────────────────┘                    │ user_id (FK)     │
                                       └─────────┬────────┘
                                                 │
                                                 │1,n
                                                 ▼
┌─────────────────────────────────────────────────────────────────────────────────────┐
│                              REPARATION                                             │
├─────────────────────────────────────────────────────────────────────────────────────┤
│ id                                                                           1,1 │
│ client_id (FK) ─────────────────┐                                              │ │
│ intervention_id (FK)            │     ┌────────────────────┐                    │ │
│ statut (en_attente,            │     │    INTERVENTION    │                    │ │
│          en_cours,             │     ├────────────────────┤                    │ │
│          termine,              │     │ id                 │                    │ │
│          paye)                 │     │ nom                │                    │ │
│ slot (1, 2, 3, ou attente)     │     │ description        │                    │ │
│ debut_reparation (datetime)     │     │ prix               │                    │ │
│ fin_reparation (datetime)       │     │ duree (secondes)   │◄──────────────────┘
│ montant_total                   │     │ type               │   1,n
│ firebase_sync                               │    │     │ actif ┌────────────────┐
└────────────────────────┬────────┘     └────────────────────┘    │                │
                         │1,1                                      │1,n            │
                         ▼                                         ▼                │
┌────────────────────────┐┌────────────────────────┐┌────────────────────────┐     │
│       PAYMENT          ││        NOTIFICATION     ││      SLOT              │     │
├────────────────────────┤├────────────────────────┤├────────────────────────┤     │
│ id                     ││ id                     ││ id                     │     │
│ reparation_id (FK)     ││ user_id (FK)           ││ numero (1, 2, 3)       │     │
│ montant                ││ titre                   ││ statut (libre, occupe) │     │
│ date_paiement          ││ message                 ││                        │     │
│ methode (especes,      ││ type (info, warning,    │└────────────────────────┘     │
│           carte)       ││           success, error)│                                 │
│ statut                 ││ statut (envoye, lu)     │                                 │
│ firebase_sync          ││ firebase_sync           │                                 │
└────────────────────────┘└────────────────────────┘                                 │
```

## Liste des Entités avec Attributs Détaillés

### 1. ENTITÉ : USER (Utilisateur Mobile)
```
Description : Utilisateurs de l'application mobile
Attributs :
  - id (INTEGER, PK) : Identifiant unique
  - name (VARCHAR) : Nom complet
  - email (VARCHAR) : Adresse email unique
  - password (VARCHAR) : Mot de passe hashé
  - push_token (VARCHAR) : Token pour les notifications Push
  - created_at (TIMESTAMP) : Date de création
  - updated_at (TIMESTAMP) : Date de modification

Contraintes :
  - email unique
  - mot de passe minimum 8 caractères
```

### 2. ENTITÉ : CLIENT (Client du Garage)
```
Description : Clients enregistrés auprès du garage
Attributs :
  - id (INTEGER, PK) : Identifiant unique
  - nom (VARCHAR) : Nom du client
  - email (VARCHAR) : Email du client
  - telephone (VARCHAR) : Numéro de téléphone
  - voiture_marque (VARCHAR) : Marque du véhicule
  - voiture_modele (VARCHAR) : Modèle du véhicule
  - voiture_annee (INTEGER) : Année de mise en circulation
  - user_id (INTEGER, FK) : Lien vers utilisateur mobile
  - created_at (TIMESTAMP) : Date d'inscription
  - updated_at (TIMESTAMP) : Dernière mise à jour

Contraintes :
  - email unique
  - telephone format valide
  - user_id optionnel (client sans compte mobile)
```

### 3. ENTITÉ : INTERVENTION (Type de Réparation)
```
Description : Les 8 types de réparations disponibles
Attributs :
  - id (INTEGER, PK) : Identifiant unique
  - nom (VARCHAR) : Nom de l'intervention
  - description (TEXT) : Description détaillée
  - prix (DECIMAL) : Prix en euros
  - duree (INTEGER) : Durée en secondes
  - type (ENUM) : Catégorie de l'intervention
  - actif (BOOLEAN) : Intervention active/inactive

Types d'interventions disponibles :
  1. Frein (frein)
  2. Vidange (vidange)
  3. Filtre (filtre)
  4. Batterie (batterie)
  5. Amortisseurs (amortisseurs)
  6. Embrayage (embrayage)
  7. Pneus (pneus)
  8. Système de refroidissement (refroidissement)

Contraintes :
  - nom unique
  - prix > 0
  - duree > 0 (minimum 60 secondes)
```

### 4. ENTITÉ : REPARATION (Réparation en Cours)
```
Description : Instance d'une réparation pour un client
Attributs :
  - id (INTEGER, PK) : Identifiant unique
  - client_id (INTEGER, FK) : Lien vers le client
  - intervention_id (INTEGER, FK) : Intervention principale
  - statut (ENUM) : État de la réparation
    * en_attente : En attente de prise en charge
    * en_cours : Réparation en cours
    * termine : Réparation terminée
    * paye : Payée et clôturée
  - slot (INTEGER) : Slot d'atelier (1, 2, 3) ou NULL si en attente
  - debut_reparation (DATETIME) : Début de la réparation
  - fin_reparation (DATETIME) : Fin de la réparation
  - montant_total (DECIMAL) : Montant total de la réparation
  - firebase_sync (BOOLEAN) : Synchronisé avec Firebase

Contraintes :
  - slot compris entre 1 et 3 (ou NULL)
  - debut_reparation <= fin_reparation
  - montant_total calculé selon interventions
```

### 5. ENTITÉ : PAYMENT (Paiement)
```
Description : Paiement effectué pour une réparation
Attributs :
  - id (INTEGER, PK) : Identifiant unique
  - reparation_id (INTEGER, FK) : Lien vers la réparation
  - montant (DECIMAL) : Montant du paiement
  - date_paiement (DATETIME) : Date et heure du paiement
  - methode (ENUM) : Méthode de paiement
    * especes : Paiement en espèces
    * carte : Paiement par carte bancaire
    * mobile : Paiement mobile
  - statut (ENUM) : État du paiement
    * en_attente : En attente
    * valide : Validée
    * annule : Annulé
  - firebase_sync (BOOLEAN) : Synchronisé avec Firebase

Contraintes :
  - montant > 0
  - date_paiement obligatoire si statut = valide
```

### 6. ENTITÉ : NOTIFICATION (Notification Push)
```
Description : Notifications envoyées aux utilisateurs
Attributs :
  - id (INTEGER, PK) : Identifiant unique
  - user_id (INTEGER, FK) : Destinataire
  - titre (VARCHAR) : Titre de la notification
  - message (TEXT) : Corps du message
  - type (ENUM) : Type de notification
    * info : Information générale
    * warning : Avertissement
    * success : Succès
    * error : Erreur
  - data (JSON) : Données supplémentaires (JSON)
  - statut (ENUM) : État d'envoi
    * envoye : Envoyée avec succès
    * lu : Lue par l'utilisateur
    * echoue : Échec d'envoi
  - firebase_sync (BOOLEAN) : Synchronisé avec Firebase
  - created_at (TIMESTAMP) : Date de création

Contraintes :
  - titre maximum 100 caractères
  - message maximum 500 caractères
```

### 7. ENTITÉ : SLOT (Place d'Atelier)
```
Description : Les 3 places disponibles dans l'atelier
Attributs :
  - id (INTEGER, PK) : Identifiant unique
  - numero (INTEGER) : Numéro du slot (1, 2, 3)
  - statut (ENUM) : État du slot
    * libre : Available for repairs
    * occupe : Currently in use
  - reparation_id (INTEGER, FK) : Réparation en cours si occupé
  - created_at (TIMESTAMP) : Date de création
  - updated_at (TIMESTAMP) : Dernière mise à jour

Contraintes :
  - numero unique (1, 2, 3)
  - Un seul slot actif par reparation
```

## Relations Détaillées

### RELATION : USER - CLIENT (1,n)
```
Cardinalité : Un utilisateur peut avoir plusieurs clients
             Un client peut avoir un utilisateur (optionnel)

Règle de gestion :
  - L'utilisateur mobile peut gérer plusieurs véhicules
  - Un client peut s'inscrire sans compte mobile

Impact sur les traitements :
  - Si user_id est NULL → client enregistré par l'administrateur
  - Si user_id est présent → client propriétaire du compte
```

### RELATION : CLIENT - REPARATION (1,n)
```
Cardinalité : Un client peut avoir plusieurs réparations
             Une réparation concerne un seul client

Règle de gestion :
  - Le client dépose son véhicule pour réparation
  - Chaque réparation est facturée au client

Impact sur les traitements :
  - Calcul du montant total par client
  - Historique des réparations par client
```

### RELATION : INTERVENTION - REPARATION (n,n)
```
Cardinalité : Une intervention peut être dans plusieurs réparations
             Une réparation peut contenir plusieurs interventions

Table de jointure : repair_intervention

Attributs de la jointure :
  - status : Statut de l'intervention dans la réparation
  - debut : Date de début de l'intervention
  - fin : Date de fin de l'intervention

Règle de gestion :
  - Une réparation peut combiner plusieurs interventions
  - Exemple : Vidange + Filtre + Freins sur même véhicule
```

### RELATION : REPARATION - SLOT (n,1)
```
Cardinalité : Une réparation occupe un slot
             Un slot peut contenir une réparation

Règle de gestion :
  - Maximum 3 réparations simultanées
  - Slot NULL si réparation en attente (pas encore prise en charge)

Impact sur les traitements :
  - Vérification de disponibilité avant assignation
  - Calcul du temps d'attente
```

### RELATION : REPARATION - PAYMENT (1,1)
```
Cardinalité : Une réparation a un paiement
             Un paiement concerne une réparation

Règle de gestion :
  - Paiement obligatoire avant récupération du véhicule
  - Montant = somme des interventions

Impact sur les traitements :
  - Libération du slot après paiement
  - Génération de facture
```

### RELATION : USER - NOTIFICATION (1,n)
```
Cardinalité : Un utilisateur peut recevoir plusieurs notifications
             Une notification est destinée à un utilisateur

Règle de gestion :
  - Notification lors de changement de statut
  - Notification de réparation prête

Types de notifications :
  - "Votre véhicule est prêt" (statut = termine)
  - "Paiement effectué" (statut = paye)
  - "Place disponible" (slot attribué)
```

## Schéma de la Base de Données

```
┌──────────────────────────────────────────────────────────────────────────────┐
│                           SCHEMA RELATIONNEL                                 │
└──────────────────────────────────────────────────────────────────────────────┘

-- Table : users
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    push_token VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table : clients
CREATE TABLE clients (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    telephone VARCHAR(50) NULL,
    voiture_marque VARCHAR(255) NULL,
    voiture_modele VARCHAR(255) NULL,
    voiture_annee INT NULL,
    user_id BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Table : interventions
CREATE TABLE interventions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL UNIQUE,
    description TEXT NULL,
    prix DECIMAL(10, 2) NOT NULL,
    duree INT NOT NULL COMMENT 'Durée en secondes',
    type VARCHAR(50) NOT NULL,
    actif BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table : reparations
CREATE TABLE reparations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    client_id BIGINT UNSIGNED NOT NULL,
    intervention_id BIGINT UNSIGNED NULL,
    statut ENUM('en_attente', 'en_cours', 'termine', 'paye') DEFAULT 'en_attente',
    slot INT NULL COMMENT 'Slot 1, 2, 3 ou NULL si en attente',
    debut_reparation DATETIME NULL,
    fin_reparation DATETIME NULL,
    montant_total DECIMAL(10, 2) DEFAULT 0.00,
    firebase_sync BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
    FOREIGN KEY (intervention_id) REFERENCES interventions(id) ON DELETE SET NULL,
    CHECK (slot IS NULL OR (slot >= 1 AND slot <= 3))
);

-- Table pivot : repair_intervention
CREATE TABLE repair_intervention (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    reparation_id BIGINT UNSIGNED NOT NULL,
    intervention_id BIGINT UNSIGNED NOT NULL,
    status ENUM('en_attente', 'en_cours', 'termine') DEFAULT 'en_attente',
    debut DATETIME NULL,
    fin DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (reparation_id) REFERENCES reparations(id) ON DELETE CASCADE,
    FOREIGN KEY (intervention_id) REFERENCES interventions(id) ON DELETE CASCADE,
    UNIQUE KEY unique_repair_intervention (reparation_id, intervention_id)
);

-- Table : payments
CREATE TABLE payments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    reparation_id BIGINT UNSIGNED NOT NULL,
    montant DECIMAL(10, 2) NOT NULL,
    date_paiement DATETIME NULL,
    methode ENUM('especes', 'carte', 'mobile') NULL,
    statut ENUM('en_attente', 'valide', 'annule') DEFAULT 'en_attente',
    firebase_sync BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (reparation_id) REFERENCES reparations(id) ON DELETE CASCADE
);

-- Table : notifications
CREATE TABLE notifications (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    titre VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('info', 'warning', 'success', 'error') DEFAULT 'info',
    data JSON NULL,
    statut ENUM('envoye', 'lu', 'echoue') DEFAULT 'envoye',
    firebase_sync BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Table : slots
CREATE TABLE slots (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    numero INT NOT NULL UNIQUE COMMENT '1, 2 ou 3',
    statut ENUM('libre', 'occupe') DEFAULT 'libre',
    reparation_id BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (reparation_id) REFERENCES reparations(id) ON DELETE SET NULL,
    CHECK (numero IN (1, 2, 3))
);

-- Index pour optimiser les requêtes
CREATE INDEX idx_reparations_client ON reparations(client_id);
CREATE INDEX idx_reparations_statut ON reparations(statut);
CREATE INDEX idx_reparations_slot ON reparations(slot);
CREATE INDEX idx_payments_reparation ON payments(reparation_id);
CREATE INDEX idx_notifications_user ON notifications(user_id);
CREATE INDEX idx_notifications_statut ON notifications(statut);
```

## Flux de Données

```
┌──────────────────────────────────────────────────────────────────────────────┐
│                           FLUX DE DONNEES PRINCIPAUX                         │
└──────────────────────────────────────────────────────────────────────────────┘

1. FLUX INSCRIPTION MOBILE
   User ──► ──► ──► Clients (avec user_id)

2. FLUX CRÉATION RÉPARATION
   Client + Intervention ──► Reparation ──► Slot (si disponible)

3. FLUX NOTIFICATION
   Reparation (changement statut) ──► Notification ──► User (via Firebase)

4. FLUX PAIEMENT
   Reparation (termine) ──► Payment ──► Reparation (paye) ──► Slot (libre)

5. FLUX SYNCHRONISATION FIREBASE
   Reparation/Payment/Notification ──► Firebase Realtime Database
```

## Règles de Gestion des Slots

```
GESTION DES 3 SLOTS D'ATELIER :

Règle 1 : Attribution
   - Un slot ne peut être attribué qu'à une réparation
   - Slot NULL = réparation en liste d'attente

Règle 2 : Disponibilité
   - Si 3 slots occupés → nouvelle réparation en attente
   - Si slot libre → attribution automatique ou manuelle

Règle 3 : Libération
   - Après paiement → slot remis à libre
   - Réparation passe au statut "en_attente_client"

Règle 4 : Temps réel
   - Mise à jour Firebase à chaque changement
   - Mobile et Game notifiés immédiatement
```

## Statuts des Réparations (Cycle de Vie)

```
┌─────────────┐     ┌─────────────┐     ┌─────────────┐     ┌─────────────┐
│  en_attente  │────►│   en_cours  │────►│   termine   │────►│    paye     │
└─────────────┘     └─────────────┘     └─────────────┘     └─────────────┘
      │                   │                   │                   │
      │                   │                   │                   │
      ▼                   ▼                   ▼                   ▼
Waiting for        Mechanic working     Ready for        Payment done
slot assignment    on the car           pickup           Slot freed
```

## Notes Techniques

```
COMPATIBILITÉ :
- MySQL 8.0+ ou PostgreSQL 12+
- Laravel 9.x ou 10.x

FIREBASE :
- Realtime Database pour la synchronisation
- Cloud Messaging pour les notifications

SÉCURITÉ :
- Hachage des mots de passe avec Bcrypt
- JWT pour l'authentification API
- Validation des entrées côté serveur
- Protection CSRF sur les formulaires web

PERFORMANCES :
- Index sur les colonnes fréquemment interrogées
- Pagination pour les listes
- Cache Redis pour les statistiques
```

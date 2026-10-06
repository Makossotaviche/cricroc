# 🟠 Cric Croc — Système de Gestion Commerciale

Application Laravel de gestion commerciale pour **Cric Croc**, fabricant de chips artisanales à Pointe-Noire, Congo.

---

## Fonctionnalités

| Module | Fonctionnalités |
|--------|----------------|
| **Clients** | CRUD, historique commandes |
| **Commandes** | Création avec lignes dynamiques, validation/rejet, calcul automatique |
| **Factures** | Génération automatique, impression PDF-ready, paiement |
| **Stock** | Entrées/sorties, alertes de rupture, historique mouvements |
| **Production** | Enregistrement production, consommation matières premières, mise à jour stock auto |
| **Livraisons** | Création, assignation livreur, suivi de statut |
| **Utilisateurs** | Gestion des rôles (admin, agent commercial, gestionnaire stock, resp. production, livreur) |
| **Dashboard** | Vue d'ensemble : CA mensuel, alertes stock, commandes récentes |

---

## Prérequis

- PHP >= 8.1
- Composer
- MySQL / MariaDB
- Node.js (optionnel, pour assets)

---

## Installation

```bash
# 1. Cloner le projet
git clone <votre-repo> cricroc
cd cricroc

# 2. Installer les dépendances PHP
composer install

# 3. Copier et configurer l'environnement
cp .env.example .env
php artisan key:generate

# 4. Configurer la base de données dans .env
# DB_DATABASE=cricroc
# DB_USERNAME=root
# DB_PASSWORD=votre_mot_de_passe

# 5. Créer la base de données
mysql -u root -p -e "CREATE DATABASE cricroc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 6. Exécuter les migrations
php artisan migrate

# 7. Insérer les données de démonstration
php artisan db:seed

# 8. Lancer le serveur
php artisan serve
```

Accédez à http://localhost:8000

---

## Comptes de démonstration

| Email | Rôle | Mot de passe |
|-------|------|-------------|
| admin@cricroc.cg | Administrateur | password |
| commercial@cricroc.cg | Agent Commercial | password |
| stock@cricroc.cg | Gestionnaire de Stock | password |
| production@cricroc.cg | Responsable de Production | password |
| livreur@cricroc.cg | Livreur | password |

---

## Structure du projet

```
app/
├── Http/Controllers/
│   ├── Auth/LoginController.php
│   ├── DashboardController.php
│   ├── ClientController.php
│   ├── ProduitController.php
│   ├── StockController.php
│   ├── CommandeController.php
│   ├── FactureController.php
│   ├── LivraisonController.php
│   ├── ProductionController.php
│   └── UserController.php
├── Models/
│   ├── User.php
│   ├── Client.php
│   ├── Produit.php
│   ├── Stock.php
│   ├── MouvementStock.php
│   ├── Commande.php
│   ├── LigneCommande.php
│   ├── Facture.php
│   ├── Paiement.php
│   ├── Livraison.php
│   ├── Production.php
│   └── ConsommationProduction.php
database/
├── migrations/         # 8 migrations
└── seeders/
    └── DatabaseSeeder.php
resources/views/
├── layouts/app.blade.php
├── auth/login.blade.php
├── dashboard.blade.php
├── clients/            # index, create, edit, show
├── commandes/          # index, create, show
├── factures/           # index, show, imprimer
├── stock/              # index, mouvements
├── production/         # index, create, show
├── livraisons/         # index, create, show
├── produits/           # index, create, edit
└── users/              # index, create, edit
routes/web.php
```

---

## Flux métier

```
Client → Commande (en_attente)
                ↓ valider
         Commande (validée) + Facture (générée automatiquement)
                ↓ paiement + sortie stock
         Facture (payée)
                ↓ livraison créée
         Livraison (en_attente → en_cours → livrée)

Production → consomme Matières Premières (sortie stock)
           → ajoute Produits Finis (entrée stock)
```

---

## Technologies

- **Backend** : Laravel 10+, PHP 8.1+
- **Base de données** : MySQL avec Eloquent ORM
- **Frontend** : Bootstrap 5.3, Bootstrap Icons, Vanilla JS
- **Auth** : Laravel Auth natif avec gestion de rôles

-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: cricroc
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `cricroc`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `cricroc` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `cricroc`;

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clients` (
  `idclient` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type_client` varchar(255) NOT NULL DEFAULT 'Particulier',
  `nom` varchar(255) NOT NULL,
  `prenoms` varchar(255) DEFAULT NULL,
  `numero_piece_identite` varchar(255) DEFAULT NULL,
  `code_client` varchar(255) DEFAULT NULL,
  `date_enregistrement` date DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `telephone_secondaire` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `whatsapp` varchar(255) DEFAULT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `pays` varchar(255) DEFAULT NULL,
  `ville` varchar(255) DEFAULT NULL,
  `commune` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idclient`),
  UNIQUE KEY `clients_code_client_unique` (`code_client`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `commandes`
--

DROP TABLE IF EXISTS `commandes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `commandes` (
  `idcommande` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `date_commande` date NOT NULL,
  `idclient` bigint(20) unsigned NOT NULL,
  `statut` enum('en_attente','validee','rejetee') NOT NULL DEFAULT 'en_attente',
  `montant_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idcommande`),
  KEY `commandes_idclient_foreign` (`idclient`),
  CONSTRAINT `commandes_idclient_foreign` FOREIGN KEY (`idclient`) REFERENCES `clients` (`idclient`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `consommations_production`
--

DROP TABLE IF EXISTS `consommations_production`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `consommations_production` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `idproduction` bigint(20) unsigned NOT NULL,
  `idproduit` bigint(20) unsigned NOT NULL,
  `quantite_consommee` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `consommations_production_idproduction_foreign` (`idproduction`),
  KEY `consommations_production_idproduit_foreign` (`idproduit`),
  CONSTRAINT `consommations_production_idproduction_foreign` FOREIGN KEY (`idproduction`) REFERENCES `productions` (`idproduction`) ON DELETE CASCADE,
  CONSTRAINT `consommations_production_idproduit_foreign` FOREIGN KEY (`idproduit`) REFERENCES `produits` (`idproduit`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `factures`
--

DROP TABLE IF EXISTS `factures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `factures` (
  `idfacture` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `numero_facture` varchar(255) NOT NULL,
  `date_facture` date NOT NULL,
  `montant_total` decimal(10,2) NOT NULL,
  `taux_tva` decimal(5,2) NOT NULL DEFAULT 0.00,
  `montant_ht` decimal(10,2) NOT NULL,
  `montant_tva` decimal(10,2) NOT NULL DEFAULT 0.00,
  `idcommande` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idfacture`),
  UNIQUE KEY `factures_numero_facture_unique` (`numero_facture`),
  KEY `factures_idcommande_foreign` (`idcommande`),
  CONSTRAINT `factures_idcommande_foreign` FOREIGN KEY (`idcommande`) REFERENCES `commandes` (`idcommande`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `parametres_facturation`;
CREATE TABLE `parametres_facturation` (
  `id` tinyint(3) unsigned NOT NULL,
  `taux_tva` decimal(5,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `lignes_commande`
--

DROP TABLE IF EXISTS `lignes_commande`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lignes_commande` (
  `idligne` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `quantite` int(10) unsigned NOT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL,
  `idcommande` bigint(20) unsigned NOT NULL,
  `idproduit` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idligne`),
  KEY `lignes_commande_idcommande_foreign` (`idcommande`),
  KEY `lignes_commande_idproduit_foreign` (`idproduit`),
  CONSTRAINT `lignes_commande_idcommande_foreign` FOREIGN KEY (`idcommande`) REFERENCES `commandes` (`idcommande`) ON DELETE CASCADE,
  CONSTRAINT `lignes_commande_idproduit_foreign` FOREIGN KEY (`idproduit`) REFERENCES `produits` (`idproduit`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `livraisons`
--

DROP TABLE IF EXISTS `livraisons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `livraisons` (
  `idlivraison` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `date_livraison` date DEFAULT NULL,
  `statut` enum('en_attente','en_cours','livree') NOT NULL DEFAULT 'en_attente',
  `idcommande` bigint(20) unsigned NOT NULL,
  `idlivreur` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idlivraison`),
  KEY `livraisons_idcommande_foreign` (`idcommande`),
  KEY `livraisons_idlivreur_foreign` (`idlivreur`),
  CONSTRAINT `livraisons_idcommande_foreign` FOREIGN KEY (`idcommande`) REFERENCES `commandes` (`idcommande`) ON DELETE CASCADE,
  CONSTRAINT `livraisons_idlivreur_foreign` FOREIGN KEY (`idlivreur`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mouvements_stock`
--

DROP TABLE IF EXISTS `mouvements_stock`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mouvements_stock` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('entree','sortie') NOT NULL,
  `quantite` int(10) unsigned NOT NULL,
  `motif` varchar(255) DEFAULT NULL,
  `idstock` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mouvements_stock_idstock_foreign` (`idstock`),
  CONSTRAINT `mouvements_stock_idstock_foreign` FOREIGN KEY (`idstock`) REFERENCES `stocks` (`idstock`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `notifications_internes`
--

DROP TABLE IF EXISTS `notifications_internes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications_internes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `titre` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `icone` varchar(255) NOT NULL DEFAULT 'bi-bell',
  `couleur` varchar(255) NOT NULL DEFAULT 'primary',
  `lien` varchar(255) DEFAULT NULL,
  `lue` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_internes_user_id_foreign` (`user_id`),
  CONSTRAINT `notifications_internes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `paiements`
--

DROP TABLE IF EXISTS `paiements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paiements` (
  `idpaiement` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `montant` decimal(10,2) NOT NULL,
  `date_paiement` date NOT NULL,
  `mode` enum('especes','mobile_money','virement') NOT NULL DEFAULT 'especes',
  `idfacture` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idpaiement`),
  KEY `paiements_idfacture_foreign` (`idfacture`),
  CONSTRAINT `paiements_idfacture_foreign` FOREIGN KEY (`idfacture`) REFERENCES `factures` (`idfacture`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `productions`
--

DROP TABLE IF EXISTS `productions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `productions` (
  `idproduction` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `date_production` date NOT NULL,
  `quantite_produite` int(10) unsigned NOT NULL,
  `idproduit` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idproduction`),
  KEY `productions_idproduit_foreign` (`idproduit`),
  CONSTRAINT `productions_idproduit_foreign` FOREIGN KEY (`idproduit`) REFERENCES `produits` (`idproduit`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `produits`
--

DROP TABLE IF EXISTS `produits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `produits` (
  `idproduit` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `type` enum('matiere_premiere','produit_fini','emballage') NOT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL DEFAULT 0.00,
  `prix_revient` decimal(10,2) DEFAULT NULL,
  `unite` varchar(255) NOT NULL DEFAULT 'unité',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idproduit`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `stocks`
--

DROP TABLE IF EXISTS `stocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stocks` (
  `idstock` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `quantite_disponible` int(10) unsigned NOT NULL DEFAULT 0,
  `seuil_alerte` int(10) unsigned NOT NULL DEFAULT 10,
  `idproduit` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idstock`),
  KEY `stocks_idproduit_foreign` (`idproduit`),
  CONSTRAINT `stocks_idproduit_foreign` FOREIGN KEY (`idproduit`) REFERENCES `produits` (`idproduit`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','agent_commercial','gestionnaire_stock','responsable_production','livreur') NOT NULL DEFAULT 'agent_commercial',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping events for database 'cricroc'
--

--
-- Dumping routines for database 'cricroc'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-06 16:37:16

-- Historique des migrations déjà appliquées dans ce schéma.
INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2024_01_01_000000_create_users_table', 1),
('2024_01_01_000001_create_clients_table', 1),
('2024_01_01_000002_create_produits_table', 1),
('2024_01_01_000003_create_stocks_table', 1),
('2024_01_01_000004_create_commandes_table', 1),
('2024_01_01_000005_create_factures_table', 1),
('2024_01_01_000006_create_livraisons_table', 1),
('2024_01_01_000007_create_productions_table', 1),
('2024_01_01_000008_create_sessions_and_password_resets', 1),
('2024_01_01_000009_create_notifications_internes_table', 1),
('2026_06_02_000000_add_two_factor_fields_to_users_table', 1),
('2026_06_04_000001_add_details_to_clients_table', 1),
('2026_06_08_000001_update_client_identity_fields', 1),
('2026_08_25_000000_remove_two_factor_fields_from_users_table', 1),
('2026_10_09_000000_convert_quantities_to_integers', 2),
('2026_10_09_000001_set_finished_product_unit_to_ballot', 2),
('2026_10_09_000002_add_prix_revient_to_produits', 2),
('2026_10_09_000003_add_invoice_numbering_and_vat', 2);

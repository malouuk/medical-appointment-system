# Système de Gestion des Rendez-vous Médicaux

## Description
Application Web complète permettant de gérer les prises de rendez-vous d'un cabinet médical. Ce projet est développé en **Laravel 11** avec une architecture **MVC** complète, authentification sécurisée, **API REST**, recherche dynamique et notifications par email.

## Fonctionnalités principales

### 1. **Architecture & Données** ?
- ?? Migrations pour créer les tables users, services, appointments
- ?? Relations Eloquent (One-to-Many, Has-Many)
- ?? Seeders pour peupler la base de données
- ?? Indexes sur les colonnes clés

### 2. **Interface & Authentification** ?
- ?? Système d'authentification complet (Breeze)
- ?? Layouts responsive avec Bootstrap 5
- ?? Pages de connexion/inscription

### 3. **Gestion CRUD** ?
- ?? Créer, Lire, Modifier, Annuler les rendez-vous
- ?? Validation serveur et client
- ?? Statuts: pending, confirmed, cancelled, completed

### 4. **Interfaces Interactives** ?
- ?? Modales Bootstrap pour confirmations
- ?? Formulaires avec validations
- ?? Alertes et notifications

### 5. **Recherche Dynamique** ?
- ?? Recherche asynchrone avec Axios
- ?? Filtrage par service ou notes

### 6. **API REST** ?
- ?? Endpoints JSON pour CRUD
- ?? Authentification Sanctum
- ?? Réponses structurées

## Installation rapide

`ash
# 1. Dépendances
composer install
npm install

# 2. Configuration
php artisan key:generate

# 3. Base de données
php artisan migrate
php artisan db:seed

# 4. Assets
npm run build

# 5. Démarrer
php artisan serve
`

Accédez à: http://localhost:8000

## Utilisateurs de test

- **Email**: user@example.com | **Pwd**: password123
- **Email**: jane@example.com | **Pwd**: password123

## Routes API

`
GET    /api/appointments
POST   /api/appointments
GET    /api/appointments/{id}
PUT    /api/appointments/{id}
DELETE /api/appointments/{id}
GET    /api/services
`

## Statut du projet

? **Complet pour examen**  
Version: 1.0.0  
Date: Avril 2026

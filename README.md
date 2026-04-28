# Système de Gestion des Rendez-vous Médicaux

Une application web pour gérer les rendez-vous médicaux construite avec Laravel 11 et les technologies web modernes.

## Aperçu

Ce projet est un système de gestion des rendez-vous pour une clinique médicale. Il permet aux patients de prendre rendez-vous, aux médecins de gérer leurs horaires, et aux administrateurs de superviser l'ensemble des opérations de la clinique.

## Fonctionnalités

### Rôles Utilisateurs
- **Patient** : Peut prendre, consulter et gérer ses rendez-vous
- **Médecin** : Peut consulter et valider les rendez-vous des patients
- **Administrateur** : Contrôle complet du système et gestion des utilisateurs

### Fonctionnalités Principales
- Réservation et gestion des rendez-vous
- Gestion de l'horaire des médecins
- Recherche en temps réel avec Axios
- Confirmations par email pour les rendez-vous
- Support multilingue (Français, Anglais, Espagnol)
- Authentification utilisateur avec Laravel Sanctum
- Design réactif

### Stack Technique
- **Backend** : Laravel 11, PHP 8.2
- **Frontend** : Vue.js, Bootstrap 5, Axios
- **Base de données** : SQLite/MySQL
- **Outils de build** : Vite, NPM

---

## Installation

### Prérequis
- PHP >= 8.2
- Composer
- Node.js & NPM
- Git

### Étapes d'installation

1. **Cloner le dépôt**
   ```bash
   git clone <repository-url>
   cd medical-appointment-system
   ```

2. **Installer les dépendances PHP**
   ```bash
   composer install
   ```

3. **Installer les dépendances frontend et compiler les assets**
   ```bash
   npm install
   npm run build
   ```

4. **Configurer l'environnement**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Initialiser la base de données**
   ```bash
   php artisan migrate --seed
   ```

6. **Démarrer le serveur de développement**
   ```bash
   php artisan serve
   ```

L'application sera disponible à l'adresse `http://localhost:8000`

---

## Identifiants par Défaut

Le seeder crée trois comptes de test :

| Rôle | Email | Mot de passe |
|------|-------|----------|
| Admin | admin@mediluxe.com | password |
| Médecin | medecin@mediluxe.com | password |
| Patient | patient@mediluxe.com | password |

---

## Points Terminaux de l'API

L'API est disponible à `/api` et retourne des réponses JSON.

### Rendez-vous
- `GET /api/appointments` - Liste des rendez-vous
- `POST /api/appointments` - Créer un rendez-vous
- `GET /api/appointments/{id}` - Obtenir les détails d'un rendez-vous
- `PUT /api/appointments/{id}` - Modifier un rendez-vous
- `DELETE /api/appointments/{id}` - Annuler un rendez-vous

### Services
- `GET /api/services` - Liste des services disponibles

---

## Schéma de Base de Données

### Table Utilisateurs
- id, name, email, password, role (admin/medecin/patient)

### Table Rendez-vous
- id, user_id, service_id, appointment_date, status, notes

### Table Services
- id, name, description, duration, price

---

## Structure du Projet

```
app/
├── Http/Controllers/          # Contrôleurs API et Web
├── Models/                    # Modèles Eloquent
└── Policies/                  # Politiques d'autorisation

database/
├── migrations/                # Migrations de base de données
├── seeders/                   # Seeders de données
└── factories/                 # Factories de modèles

resources/
├── js/                        # Composants JavaScript/Vue
├── css/                       # Feuilles de style
└── views/                     # Templates Blade

routes/
├── api.php                    # Routes API
├── web.php                    # Routes Web
└── auth.php                   # Routes d'authentification
```

---

## Langues Supportées

L'application inclut les traductions pour :
- **Français**
- **Anglais** (English)
- **Espagnol** (Español)

Changez de langue dans les paramètres de votre profil.

---

## Développement

### Exécuter les Tests
```bash
php artisan test
```

### Compiler les Assets
```bash
npm run build    # Production
npm run dev      # Développement avec rechargement en direct
```

---

## Notes

- Tous les mots de passe en développement sont définis à "password"
- Les confirmations par email utilisent le système de mailing de Laravel
- L'authentification est gérée via les jetons API Laravel Sanctum
- Le système utilise les politiques Laravel pour l'autorisation

---

## Auteur

Créé comme un projet étudiant pour apprendre Laravel et les pratiques modernes de développement web.
- ✅ **Authentification & Layouts** (2 pts)
- ✅ **Migrations & Seeders / Factories** (3 pts)
- ✅ **Gestion CRUD** (3 pts)
- ✅ **Modales & i18n** (3 pts)
- ✅ **Recherche Axios** (2 pts)
- ✅ **Mailing** (2 pts)
- ✅ **API REST** (3 pts)
- ✅ **Git & README** (2 pts)

---
*Développé avec passion pour l'excellence médicale.*

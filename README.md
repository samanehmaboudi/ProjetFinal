# 🍷 Projet Web 2 – Vino

**Vino** est une application web permettant à chaque utilisateur de gérer un ou plusieurs celliers de vin.  
Elle intègre le catalogue officiel de la SAQ via une API GraphQL, permet d’ajouter des bouteilles personnalisées, de noter ses dégustations et de trier/rechercher facilement.  
Développée en équipe selon la méthode Agile/Scrum dans le cadre du cours **582-41W** au Collège de Maisonneuve. 

---

## 📌 Objectif du projet

Permettre à un utilisateur de :
- Gérer un ou plusieurs celliers de vin.
- Ajouter, modifier et supprimer des bouteilles.
- Importer et consulter le catalogue officiel de la SAQ.

---

## 🚀 Fonctionnalités clés (en cours de développement)

- ✅ Maquettes mobiles (Accueil, Cellier, Authentification)
- ✅ Base technique Laravel avec support MySQL/SQLite
- ✅ **Import automatisé du catalogue SAQ** via GraphQL (Adobe Commerce API)
- ⏳ Authentification (connexion / inscription)
- ⏳ Gestion multi-celliers par utilisateur
- ⏳ CRUD complet sur les bouteilles de cellier
- ⏳ Recherche & filtres (nom, type, pays, millésime…)
- ⏳ Notes de dégustation, liste d’achat, partage social

---

## ⚙️ Stack technique

| Couche        | Technologie                        |
|---------------|------------------------------------|
| **Backend**   | Laravel 12, PHP 8.2                |
| **Frontend**  | Blade, Tailwind CSS v4, Vite       |
| **Base de données** | SQLite (migrations incluses) |
| **API externe** | GraphQL (Adobe Commerce – SAQ)   |
| **HTTP client** | Guzzle 7.10                      |
| **Tests**     | PHPUnit 11.5                       |
| **Design**    | Figma (mobile-first)               |
| **Gestion projet** | Jira (Scrum/Agile)            |

---

## 📚 Service SaqScraper

Le service **SaqScraper** permet d'importer automatiquement le catalogue de produits de la SAQ dans la base de données locale via l'API GraphQL d'Adobe Commerce.

Pour une documentation complète sur le service, consultez [SAQSCRAPER_README.md](SAQSCRAPER_README.md).

**Utilisation rapide** :
```bash
# Importer 10 produits pour tester
php artisan saq:import --limite=10
```

---

## 🔗 Liens utiles
Maquettes Figma
Backlog & Sprint Board (Jira)
Dépôt GitHub

---

## 👥 Équipe de développement
Samaneh Mahboudi
Philippe Cossette
Adil El Amrani
Tommy Bourgeois

---

## 🛠️ Installation & démarrage

### Prérequis
- PHP 8.2+
- Composer
- Node.js 
- MySQL

### Étapes

1. **Cloner le dépôt**
   ```bash
   git clone https://github.com/ProjetFinal-Maisonneuve/ProjetFinal.git
   cd ProjetFinal
   ```

2. **Installer les dépendances PHP**
   ```bash
   composer install
   ```

3. **Configurer l'environnement**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configurer la base de données**
   
   Modifiez le fichier `.env` pour configurer votre base de données (SQLite recommandé pour le développement) :
   ```env
   DB_CONNECTION=sqlite
   SESSION_DRIVER=file
   ```

   Créez le fichier de base de données SQLite :
   ```bash
   touch database/database.sqlite
   ```

5. **Exécuter les migrations**
   ```bash
   php artisan migrate
   ```

6. **Installer les dépendances frontend**
   ```bash
   npm install
   ```

7. **Créer le lien symbolique pour le stockage**
   ```bash
   php artisan storage:link
   ```

8. **Lancer le serveur de développement**
   ```bash
   php artisan serve
   ```

   L'application sera accessible à `http://localhost:8000`

9. **Importer le catalogue SAQ (optionnel)**
   ```bash
   php artisan saq:import --limite=10
   ```

   Voir [SAQSCRAPER_README.md](SAQSCRAPER_README.md) pour la documentation complète du service.

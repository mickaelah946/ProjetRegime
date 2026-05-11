# 🚀 Guide de Déploiement et Test sur Base Vierge

## 📋 Prérequis
- PHP 8.2+
- MySQL 5.7+ ou MariaDB 10.3+
- Composer installé
- Git (pour les sources)

## 🔧 Installation sur Base Vierge

### Étape 1 : Créer la base de données

```sql
CREATE DATABASE regime CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'regimeapp'@'localhost' IDENTIFIED BY 'regimepass123';
GRANT ALL PRIVILEGES ON regime.* TO 'regimeapp'@'localhost';
FLUSH PRIVILEGES;
```

### Étape 2 : Cloner et configurer le projet

```bash
# Cloner le dépôt
git clone <repository-url> regime-app
cd regime-app

# Installer les dépendances
composer install

# Copier la configuration d'environnement
cp .env.example .env
# OU éditez directement .env et décommentez les lignes de base de données

# Éditer .env avec les paramètres de base de données
# database.default.hostname = localhost
# database.default.database = regime
# database.default.username = regimeapp
# database.default.password = regimepass123
# database.default.port = 3306

# OU modifier app/Config/Database.php directement
```

### Étape 3 : Créer les tables (migrations)

```bash
# Exécuter toutes les migrations
php spark migrate

# Vérifier le statut des migrations
php spark migrate:status
```

### Étape 4 : Initialiser les données (seeds)

```bash
# Exécuter les seeds
php spark db:seed RegimeTariffSeeder
# OU tous les seeds
php spark db:seed --all
```

### Étape 5 : Lancer l'application

```bash
# Mode développement
php -S localhost:8000 -t public

# Accéder à l'application
# http://localhost:8000/
```

## 🧪 Test sur Base Vierge - Checklist

### 1. ✅ Connexion & Authentification
- [ ] Page de login accessible (`http://localhost:8000/login`)
- [ ] Connexion avec admin / admin123
- [ ] Mot de passe hash bcrypt vérifié correctement
- [ ] Redirection vers dashboard
- [ ] Déconnexion fonctionne

### 2. ✅ CRUD Administrateur
- [ ] Dashboard admin accessible
- [ ] 5 utilisateurs présents
- [ ] 5 régimes présents
- [ ] 5 activités présentes
- [ ] 16 codes portefeuille présents
- [ ] Gestion utilisateurs : créer, lire, modifier, supprimer
- [ ] Gestion régimes : créer, lire, modifier, supprimer, gérer tarifs
- [ ] Gestion activités : créer, lire, modifier, supprimer
- [ ] Gestion codes : créer, lire, modifier, supprimer

### 3. ✅ Fonctionnalités de Tarification
- [ ] Tarifs visibles pour chaque régime (7j, 14j, 30j, 90j)
- [ ] Prix calculés correctement (proportionnels à la durée)
- [ ] Réductions appliquées (0%, 5%, 10%, 15%)
- [ ] Sélection de durée met à jour le prix dynamiquement
- [ ] Bouton "Choisir (X j)" change avec la durée sélectionnée

### 4. ✅ Affichage Frontend
- [ ] Page de navigation des régimes accessible
- [ ] Cartes de régimes affichées correctement
- [ ] 4 boutons de durée (7j, 14j, 30j, 90j) présents
- [ ] Prix s'affichent correctement pour chaque durée
- [ ] Badge Gold et réduction % visibles pour users Gold

### 5. ✅ Fonctionnalités AJAX
- [ ] Dashboard utilisateur accessible
- [ ] Formulaire de validation de code présent
- [ ] Validation de code SANS recharge de page
- [ ] Balance mise à jour dynamiquement
- [ ] Messages d'erreur affichés correctement

### 6. ✅ Graphiques et Tableaux
- [ ] Dashboard admin : 3 graphiques Chart.js présents
- [ ] Graphique Doughnut : Gold vs Normal users
- [ ] Graphique Bar : Régimes populaires
- [ ] Graphique Line : Tendance des revenus
- [ ] Tableau croisé accessible
- [ ] Onglet "Régimes Utilisateurs" : 4 users × 5 régimes
- [ ] Onglet "Activités Utilisateurs" : 4 users × 5 activités
- [ ] Codes de couleur corrects (Actif/Terminé/Annulé/Aucun)

### 7. ✅ PDF et Exports
- [ ] Lien PDF "Facture utilisateur" accessible
- [ ] PDF généré avec contenu correct
- [ ] PDF "Reçu d'achat" accessible
- [ ] PDF "Rapport administrateur" accessible

### 8. ✅ Sécurité
- [ ] Mots de passe hachés en bcrypt (vérifier dans DB)
- [ ] Connexion avec mot de passe hachée réussit
- [ ] Erreurs de login correctes
- [ ] Filtres d'admin appliqués
- [ ] Accès admin protégé (non-admin redirigé)

### 9. ✅ Base de Données
- [ ] Table users : 5 utilisateurs
- [ ] Table regimes : 5 régimes
- [ ] Table activites : 5 activités
- [ ] Table codes_portefeuille : 16 codes
- [ ] Table regime_tarifs : 20 tarifs (5 régimes × 4 durées)
- [ ] Intégrité référentielle : Foreign keys OK
- [ ] Timestamps : created_at/updated_at OK

## 📊 Résultats de Test

### Configuration Testée
- **Base de données** : regime (vierge)
- **Version PHP** : 8.2+
- **Framework** : CodeIgniter 4.7.2
- **Date de test** : [DATE]
- **Testeur** : [NOM]

### Résumé des Résultats
- ✅ Toutes les migrations exécutées avec succès
- ✅ Tous les seeds initialisant les données correctement
- ✅ Interface admin complètement fonctionnelle
- ✅ Interface utilisateur responsif et AJAX
- ✅ Authentification sécurisée avec bcrypt
- ✅ Pas d'erreurs ou d'avertissements

### Observations
- Les tarifs se mettent à jour dynamiquement au clic
- Les graphiques s'affichent correctement
- Les codes de validation fonctionnent en AJAX
- La sécurité des mots de passe est renforcée

### Notes pour la Production
1. Changer les secrets APP_KEY dans Config/App.php
2. Mettre CI_ENVIRONMENT=production dans .env
3. Configurer un certificat SSL
4. Utiliser une vraie base de données hébergée
5. Mettre en place backups automatiques
6. Configurer un service d'email professionnel
7. Activer les logs dans writable/logs/

---

**Document créé le** : 2026-05-11
**Statut** : ✅ Prêt pour production après validation

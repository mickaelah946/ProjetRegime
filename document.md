
## Partie 1 : Base de donnees

J’ai utilise la base `bibliotheque`.

Tables utilisees :
- `livres`
- `emprunts`

Le fichier SQL est dans `database.sql`.

Connexion configuree dans `app/Config/Database.php` avec :
- hôte : `127.0.0.1`
- utilisateur : `root`
- base : `bibliotheque`

---

## Partie 2 : Routes

J’ai ajoute les routes principales dans `app/Config/Routes.php` :
- `/` : liste des livres
- `/livres/create` : formulaire d’ajout
- `/livres/store` : enregistrement
- `/livres/{id}` : detail
- `/livres/delete/{id}` : suppression
- `/livres/preter/{id}` : pret
- `/livres/retourner/{id}` : retour
- `/livres/search` : recherche AJAX

---

## Partie 3 : Modele Livre

Fichier : `app/Models/LivreModel.php`

J’ai mis :
- la table `livres`,
- les champs autorises,
- la validation des champs,
- une verification de l’annee (pas dans le futur),
- la pagination,
- la recherche par mot-cle et categorie.

Le mot-cle peut chercher dans :
- titre,
- auteur,
- isbn,
- resume.

---

## Partie 4 : Modele Emprunt

Fichier : `app/Models/EmpruntModel.php`

J’ai mis :
- la table `emprunts`,
- les champs autorises,
- la methode pour recuperer le dernier emprunt.

Le tri a ete corrige pour prendre le plus recent.

---

## Partie 5 : Contrôleur Livre

Fichier : `app/Controllers/LivreController.php`

Fonctions realisees :
- `index()` : liste + recherche + pagination
- `search()` : recherche sans recharger la page
- `show()` : detail d’un livre
- `create()` : afficher formulaire
- `store()` : enregistrer un livre + upload image
- `delete()` : supprimer le livre

Correction faite :
- avant suppression d’un livre, je supprime ses emprunts pour eviter l’erreur de cle etrangere.

---

## Partie 6 : Contrôleur Emprunt

Fichier : `app/Controllers/EmpruntController.php`

Fonctions realisees :
- `preter()` : enregistrer un pret et mettre le statut à `prete`
- `retourner()` : enregistrer la date de retour et remettre `disponible`

---

## Partie 7 : Vues

Fichiers :
- `app/Views/layout/main.php`
- `app/Views/livres/index.php`
- `app/Views/livres/show.php`
- `app/Views/livres/create.php`
- `app/Views/livres/_table_rows.php`

Dans les vues, j’ai fait :
- affichage simple Bootstrap,
- formulaire d’ajout,
- tableau des livres,
- boutons pret/retour/suppression,
- recherche AJAX (JavaScript simple).

---

## Partie 8 : Securite

J’ai active CSRF dans `app/Config/Filters.php`.

J’ai utilise :
- `csrf_field()` dans les formulaires POST,
- `esc()` pour eviter les problemes XSS.

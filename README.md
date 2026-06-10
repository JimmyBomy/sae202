# BACKROOMS — Site événementiel (SAÉ 2.02)

Escape game nocturne immersif inspiré de la légende urbaine des Backrooms.
Projet réalisé par **Jimmy BOMY** (agence étudiante **Lumina Studio**, BUT MMI — IUT de Troyes).

## URLs
- Site : https://sae202.mmi25c02.mmi-troyes.fr
- Back-office : https://sae202.mmi25c02.mmi-troyes.fr/gestion (protégé par htpasswd)
- Site agence (WordPress, semaine 1) : https://sae202-agence.mmi25c02.mmi-troyes.fr

## Stack
PHP 8.2 (MVC procédural, sans framework ni CMS) · MariaDB (PDO, requêtes préparées) · Apache · CSS pur (responsive, sans JS pour le menu).

## Architecture
```
index.php            Front controller
conf/                Configuration + routeur (/{controleur}/{action}/{params})
controller/          Une fonction par action (accueil, concept, reservation, profil, ...)
model/               Accès BDD (bdd.php = connexion PDO ; 1 fichier par table)
view/                Vues (header/footer communs, 1 dossier par contrôleur)
admin/               Back-office /gestion (stats, modération avis, réservations, scores)
sql/schema.sql       Création des 5 tables (utilisateurs, equipes, reservations, scores, commentaires)
```

## Fonctionnalités
Inscription/réservation **par équipe** (création ou code d'invitation), calendrier de disponibilités,
questionnaire santé, paiement (démo), espace privé (profil, équipe, statistiques, parties, avis),
avis modérés, back-office complet, email de confirmation.

## Sécurité / éco-conception
Requêtes préparées (anti-injection), `htmlspecialchars` (anti-XSS), jetons **CSRF** sur tous les
formulaires, `password_hash` bcrypt, anti-bruteforce, en-têtes de sécurité ; polices auto-hébergées,
images optimisées, cache navigateur.

> Les identifiants BDD sont dans `conf/secrets.local.php` (non versionné).

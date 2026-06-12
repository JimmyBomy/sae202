# BACKROOMS — Site événementiel (SAÉ 2.02)

Escape game nocturne immersif inspiré de la légende urbaine des Backrooms.
Projet réalisé par **Jimmy BOMY** (agence étudiante **Lumina Studio**, BUT MMI — IUT de Troyes).

## URLs
- Site : https://sae202.mmi25c02.mmi-troyes.fr
- Back-office : https://sae202.mmi25c02.mmi-troyes.fr/gestion (protégé par htpasswd)
- Site agence (WordPress, semaine 1) : https://sae202-agence.mmi25c02.mmi-troyes.fr

## Stack
PHP 8.2 (MVC procédural, sans framework ni CMS) · MariaDB (PDO, requêtes préparées) · Apache · CSS pur (responsive, menu mobile sans JavaScript).

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
- Inscription/réservation **par équipe** (création ou code d'invitation à 6 caractères)
- Calendrier de disponibilités (capacité : 1 équipe par salle et par soirée, jours complets grisés)
- Âge minimum par salle (10 / 14 / 16 ans, vérifié via la date de naissance)
- Questionnaire santé (alertes visibles par l'organisateur au back-office)
- Paiement (démo) : carte → confirmée, sur place → en attente + email de confirmation
- Espace privé : profil (photo, infos, mot de passe), équipe, statistiques, parties (avec annulation), avis
- Avis modérés (publication après validation admin) + classement public des équipes
- Mot de passe oublié (lien par email, valable 1 h, usage unique)
- Back-office : statistiques (et graphique par salle), modération, gestion des réservations, saisie des scores, export CSV

## Sécurité / éco-conception
Requêtes préparées (anti-injection) · `htmlspecialchars` (anti-XSS) · jetons **CSRF** sur tous les
formulaires · `password_hash` bcrypt · anti-bruteforce · `session_regenerate_id` · en-têtes de
sécurité · uploads validés (type/poids, recompression GD) · polices auto-hébergées (zéro requête
externe) · images optimisées · cache navigateur.

> Les identifiants BDD sont dans `conf/secrets.local.php` (non versionné).

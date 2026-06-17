# 🏚️ BACKROOMS — Escape Game Nocturne

Site événementiel de l'escape game **BACKROOMS**, développé pour la **SAE202** (BUT MMI, IUT de Troyes) par l'agence **Lumina Studio**.

Une expérience immersive inspirée de la légende des *Backrooms* : une nuit entière (jeu, repas et hébergement) dans des couloirs liminaux, à Villeurbanne.

> **PHP procédural en architecture MVC, sans framework ni CMS.**

---

## 🔗 Accès

| | URL |
|---|---|
| Site public | `https://sae202.<id>.mmi-troyes.fr` |
| Back-office | `https://sae202.<id>.mmi-troyes.fr/gestion` |
| Site agence (WordPress) | `https://sae202-agence.<id>.mmi-troyes.fr` |

*(`<id>` = identifiant MMI du VPS — le projet est déployé sur les VPS de toute l'agence.)*

---

## ✨ Fonctionnalités

**Visiteur**
- Pages Accueil (avec bande-annonce), Concept, Les salles, Infos pratiques (carte, tarifs, calculateur de prix), Règles, Contact, Classement public.
- **Site multilingue 🇫🇷 / 🇬🇧 / 🇪🇸** (sélecteur de langue, mémorisé en session).
- **Réservation par équipe** : inscription / connexion (ou code d'invitation), calendrier des disponibilités (capacité + jours d'ouverture), âge minimum par salle, questionnaire santé + régime, paiement (démo).
- **Espace privé** : profil modifiable + photo, équipe, statistiques, historique des parties (annulation possible), **partage du score sur les réseaux sociaux**.
- **Avis** : dépôt d'un commentaire après la partie, publié après modération.
- **Contact** : message enregistré + **accusé de réception par email** (dans la langue du visiteur).

**Back-office (`/gestion`)**
- Double protection : mot de passe Apache (htpasswd) **+** connexion par compte administrateur.
- Statistiques (+ graphique par salle), **messagerie** (boîte de réception du formulaire de contact), modération des avis, gestion des réservations, saisie des scores, liste des inscrits + **export CSV**.

---

## 🛠️ Stack technique

- **PHP 8.2** procédural, architecture **MVC** (routeur du cours).
- **MariaDB / MySQL** via **PDO** + requêtes préparées.
- **PHPMailer** (envoi d'emails SMTP / TLS).
- HTML / CSS sans framework ; menu mobile sans JavaScript ; polices auto-hébergées.
- Déploiement **Git** sur VPS Apache + **HTTPS** (Let's Encrypt).

## 🗂️ Architecture

```
index.php              Point d'entrée (front controller)
conf/
  routeur.php          Routage URL → contrôleur/action  (/{controleur}/{action}/{params})
  conf.inc.php         Constantes, session, CSRF, tarifs, jours d'ouverture
  lang.php             Internationalisation (FR/EN/ES) + fonction t()
  secrets.local.php    Identifiants BDD + SMTP  (NON versionné)
controller/            1 fichier par page (accueil, reservation, profil…)
model/                 Accès données (utilisateur, equipe, reservation, score,
                       commentaire, message) + bdd.php + mailer.php
view/                  Vues par page + css/ fonts/ img/ inc/ uploads/
admin/                 Back-office (+ auth.php, .htaccess)
lib/PHPMailer/         Librairie d'envoi d'emails
sql/                   schema.sql (5 tables : utilisateurs, equipes, reservations, scores, commentaires)
```

---

## 🔒 Sécurité

- Mots de passe **hachés (bcrypt)**, **jetons CSRF** sur tous les formulaires.
- **Requêtes préparées** (anti-injection SQL), **échappement** des sorties (anti-XSS).
- Anti-bruteforce + `session_regenerate_id` à la connexion.
- Réinitialisation du mot de passe par **lien email à usage unique** (valable 1 h).
- Uploads d'avatars validés (type, poids, recadrage GD).
- Back-office : htpasswd **+** compte administrateur (rôle en base).
- **Aucun identifiant dans le dépôt** : tout est dans `conf/secrets.local.php` (gitignoré).

## ♿ Accessibilité & 🌱 éco-conception

- `lang` dynamique, labels de formulaires, `aria-label`, focus clavier visible, lien d'évitement, `prefers-reduced-motion`.
- Polices et ressources auto-hébergées (zéro requête externe), images optimisées, `loading="lazy"`, cache navigateur.

---

## 🚀 Installation / déploiement

```bash
# 1. Récupérer le code
sudo git clone https://github.com/JimmyBomy/sae202.git /var/www/sae202-event

# 2. Base de données
sudo mysql -e "CREATE DATABASE sae202_event CHARACTER SET utf8mb4;"
sudo mysql -e "CREATE USER 'sae202_event'@'localhost' IDENTIFIED BY 'MotDePasseFort';"
sudo mysql -e "GRANT ALL ON sae202_event.* TO 'sae202_event'@'localhost';"
sudo mysql sae202_event < /var/www/sae202-event/sql/schema.sql

# 3. Identifiants locaux (copier le modèle puis remplir DB_* et, au besoin, SMTP_*)
cp conf/secrets.local.php.exemple conf/secrets.local.php

# 4. Protection du back-office (fichier htpasswd déjà présent sur le VPS)
#    Adapter le chemin dans admin/.htaccess : AuthUserFile /home/<id>/htpassword.mmi

# 5. Vhost Apache (DocumentRoot = /var/www/sae202-event) + modules + HTTPS
sudo a2enmod rewrite headers expires ssl
sudo certbot --apache -d sae202.<id>.mmi-troyes.fr
```

### Envoi d'emails
Fonctionne via le **Postfix local** du VPS par défaut. Pour une délivrabilité garantie, renseigner un relais **SMTP** (Gmail, Brevo…) dans `conf/secrets.local.php` (`SMTP_HOST`, `SMTP_USER`, `SMTP_PASS`…). Voir `conf/secrets.local.php.exemple`.

---

## 👥 Agence

**Lumina Studio** — Jimmy BOMY · Pierre-Ange POUJOL · Sacha DREVON · Jolan LAURENT TAPPREST · Noah MATHIAS.

*Projet pédagogique — BUT MMI, IUT de Troyes (2026).*

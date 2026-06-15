<?php
/**
 * Configuration globale du site événementiel "BACKROOMS".
 * (Architecture MVC procédurale, comme vu en cours.)
 */

// Préfixe d'URL : '' si le site est servi à la racine du domaine
// (https://sae202.mmi25c02.mmi-troyes.fr), '/sous-dossier' sinon.
define('BASE_URL', '');

// Identité du site (l'événement = l'escape game de nuit Backrooms)
define('NOM_SITE', 'BACKROOMS');
define('SLOGAN', 'Escape game nocturne immersif');

// --- Base de données ---
// Les identifiants réels sont dans conf/secrets.local.php (NON versionné).
// On garde des valeurs par défaut ici pour pouvoir tester en local.
define('DB_HOST', 'localhost');
define('DB_NAME', 'sae202_event');
define('DB_CHARSET', 'utf8mb4');
if (file_exists(__DIR__ . '/secrets.local.php')) {
    require_once __DIR__ . '/secrets.local.php'; // définit DB_USER et DB_PASS
}
if (!defined('DB_USER')) { define('DB_USER', 'root'); }
if (!defined('DB_PASS')) { define('DB_PASS', ''); }

// --- Clé API Google Maps ---
// Utilisée pour la carte stylée (style "Pamplona" de Snazzy Maps) sur la page Infos pratiques.
// À définir dans conf/secrets.local.php : define('GOOGLE_MAPS_KEY', 'VOTRE_CLE');
// Si vide, la page affiche une carte OpenStreetMap de secours (jamais cassée).
if (!defined('GOOGLE_MAPS_KEY')) { define('GOOGLE_MAPS_KEY', ''); }

// --- Sessions ---
// On démarre la session une seule fois, pour gérer la connexion utilisateur.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Affichage des erreurs : ON en local, à couper en production (pas d'erreur PHP visible)
// define('DEBUG', true);
error_reporting(E_ALL);
ini_set('display_errors', '0'); // 0 en prod ; passer à 1 en dev local

// --- Protection CSRF ---
// Un jeton secret est stocké en session et glissé dans chaque formulaire.
// Au POST, on vérifie que le jeton reçu correspond : un site tiers ne peut
// pas forger de requête à la place de l'utilisateur.

// Renvoie (et crée si besoin) le jeton CSRF de la session.
function csrf_token() {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

// Champ caché à insérer dans chaque formulaire POST.
function csrf_input() {
    return '<input type="hidden" name="csrf" value="' . csrf_token() . '">';
}

// Vérifie le jeton reçu (à appeler au début de chaque traitement POST).
function csrf_verifie() {
    return isset($_POST['csrf'], $_SESSION['csrf'])
        && hash_equals($_SESSION['csrf'], $_POST['csrf']);
}

// --- Tarification ---
// Tarif dégressif par personne (hébergement + repas inclus), selon l'effectif (2 à 10).
function tarif_par_personne($nb) {
    if ($nb >= 7) return 145;
    $grille = [2 => 170, 3 => 165, 4 => 160, 5 => 155, 6 => 150];
    return $grille[$nb] ?? 170;
}

// Prix par personne pour une salle donnée (Hardcore : majoration de 10 €).
function prix_par_personne($salle, $nb) {
    return tarif_par_personne($nb) + ($salle === 'hardcore' ? 10 : 0);
}

// Prix total de la réservation.
function prix_total($salle, $nb) {
    return prix_par_personne($salle, $nb) * $nb;
}

// --- Jours d'ouverture ---
// L'escape n'ouvre que : vendredi soir, samedi soir, jours fériés et vacances
// scolaires — JAMAIS le lundi. Les autres jours sont "fermés" (non réservables).

// Jours fériés français 2026 (l'événement se déroule en 2026).
function jours_feries_2026() {
    return ['2026-01-01','2026-04-06','2026-05-01','2026-05-08','2026-05-14',
            '2026-05-25','2026-07-14','2026-08-15','2026-11-01','2026-11-11','2026-12-25'];
}

// Vacances scolaires (zone A — Lyon/Villeurbanne), plages utiles à partir de juin 2026.
// (Été, Toussaint et Noël sont communs à toutes les zones.)
function en_vacances_scolaires($ymd) {
    $plages = [
        ['2026-07-04', '2026-08-31'], // été
        ['2026-10-17', '2026-11-02'], // Toussaint
        ['2026-12-19', '2027-01-04'], // Noël
    ];
    foreach ($plages as [$debut, $fin]) {
        if ($ymd >= $debut && $ymd <= $fin) return true;
    }
    return false;
}

// Le créneau (date AAAA-MM-JJ) est-il ouvert à la réservation ?
function creneau_ouvert($ymd) {
    $jour = (int) date('N', strtotime($ymd)); // 1 = lundi … 7 = dimanche
    if ($jour === 1) return false;                              // jamais le lundi
    if ($jour === 5 || $jour === 6) return true;                // vendredi / samedi
    if (in_array($ymd, jours_feries_2026(), true)) return true; // jours fériés
    if (en_vacances_scolaires($ymd)) return true;              // vacances scolaires
    return false;
}

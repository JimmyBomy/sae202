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

// --- Sessions ---
// On démarre la session une seule fois, pour gérer la connexion utilisateur.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Affichage des erreurs : ON en local, à couper en production (pas d'erreur PHP visible)
// define('DEBUG', true);
error_reporting(E_ALL);
ini_set('display_errors', '0'); // 0 en prod ; passer à 1 en dev local

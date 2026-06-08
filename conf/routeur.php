<?php
/**
 * Routeur : transforme l'URL en appel de contrôleur/action.
 * Format des URL : /{controleur}/{action}/{param1}/{param2}...
 * Exemples : /            -> accueil/index
 *            /compte/connexion -> compte/connexion
 *            /profil/avis  -> profil/avis
 */

// 1) Chemin demandé (sans le domaine ni la query string)
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// 2) On retire le préfixe de base si défini
if (BASE_URL !== '' && strpos($path, BASE_URL) === 0) {
    $path = substr($path, strlen(BASE_URL));
}

$items = explode('/', $path);

// 3) Contrôleur (1er segment) -> 'accueil' par défaut
$controller = empty($items[1]) ? 'accueil' : $items[1];

// 4) Action (2e segment) -> 'index' par défaut
$action = empty($items[2]) ? 'index' : $items[2];

// 5) Paramètres éventuels (segments suivants)
$params = array_slice($items, 3);

// 6) Sécurité : on n'accepte que des noms simples (anti ../, anti caractères exotiques)
$fichier = 'controller/' . $controller . '_controller.php';
if (!preg_match('/^[a-z_]+$/', $controller) || !preg_match('/^[a-z_]+$/', $action) || !file_exists($fichier)) {
    http_response_code(404);
    require_once('controller/erreur_controller.php');
    erreur_404();
    exit;
}

require_once($fichier);

if (!function_exists($action)) {
    http_response_code(404);
    require_once('controller/erreur_controller.php');
    erreur_404();
    exit;
}

// 7) On exécute l'action demandée avec ses paramètres
$action(...$params);

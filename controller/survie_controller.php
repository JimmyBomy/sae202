<?php
/**
 * Mini-jeu caché « ÉVASION DES BACKROOMS » + classement.
 * Pages autonomes (hors menu) : /survie (jeu), /survie/soumettre (POST score, JSON),
 * /survie/classement (JSON). Le meilleur score gagne 4 places en avant-première.
 */
require_once('model/jeu.php');

function index() {
    require('view/survie/index.php');
}

// Enregistre un score (AJAX). POST + CSRF. Renvoie du JSON.
function soumettre() {
    header('Content-Type: application/json; charset=utf-8');
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST' || !csrf_verifie()) {
        echo json_encode(['ok' => false, 'err' => 'Requête invalide.']);
        return;
    }
    $pseudo = trim(strip_tags($_POST['pseudo'] ?? ''));
    $pseudo = mb_substr($pseudo, 0, 30);
    $score  = (int) ($_POST['score'] ?? 0);
    $niveau = (int) ($_POST['niveau'] ?? 1);
    if ($pseudo === '' || $score < 0 || $score > 50000000 || $niveau < 1 || $niveau > 50) {
        echo json_encode(['ok' => false, 'err' => 'Données invalides.']);
        return;
    }
    ajouter_score_jeu($pseudo, $score, $niveau);
    echo json_encode(['ok' => true]);
}

// Renvoie le classement en JSON.
function classement() {
    header('Content-Type: application/json; charset=utf-8');
    $rows = get_classement_jeu(15);
    foreach ($rows as &$r) { $r['pseudo'] = htmlspecialchars($r['pseudo'], ENT_QUOTES, 'UTF-8'); }
    echo json_encode($rows);
}

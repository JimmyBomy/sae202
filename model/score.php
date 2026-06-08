<?php
/**
 * Modèle Score : résultat d'une équipe après une partie.
 * Les scores sont saisis par un administrateur dans le back-office,
 * puis affichés dans l'espace privé du joueur.
 */
require_once('model/bdd.php');

// Ajoute un score pour une équipe (back-office).
function ajouter_score($equipe_id, $points, $temps_secondes, $reussi) {
    $pdo = getBdd();
    $sql = "INSERT INTO scores (equipe_id, points, temps_secondes, reussi)
            VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$equipe_id, $points, $temps_secondes, $reussi]);
}

// Scores d'une équipe (espace privé).
function get_scores_by_equipe($equipe_id) {
    $pdo = getBdd();
    $stmt = $pdo->prepare("SELECT * FROM scores WHERE equipe_id = ? ORDER BY date_partie DESC");
    $stmt->execute([$equipe_id]);
    return $stmt->fetchAll();
}

// Tous les scores avec le nom de l'équipe (back-office + classement).
function get_tous_scores() {
    $pdo = getBdd();
    $sql = "SELECT s.*, e.nom AS equipe_nom
            FROM scores s
            JOIN equipes e ON e.id = s.equipe_id
            ORDER BY s.points DESC, s.temps_secondes ASC";
    return $pdo->query($sql)->fetchAll();
}

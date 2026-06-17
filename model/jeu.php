<?php
require_once('model/bdd.php');

// Enregistre un score du mini-jeu d'évasion.
function ajouter_score_jeu(string $pseudo, int $score, int $niveau): bool {
    $pdo = getBdd();
    $sql = "INSERT INTO jeu_scores (pseudo, score, niveau) VALUES (?, ?, ?)";
    return $pdo->prepare($sql)->execute([$pseudo, $score, $niveau]);
}

// Classement : meilleurs scores (un par joueur n'est pas garanti, on prend les meilleurs bruts).
function get_classement_jeu(int $limit = 15): array {
    $pdo = getBdd();
    $limit = max(1, min(100, $limit));
    $sql = "SELECT pseudo, score, niveau, date_creation
            FROM jeu_scores
            ORDER BY score DESC, date_creation ASC
            LIMIT $limit";
    return $pdo->query($sql)->fetchAll();
}

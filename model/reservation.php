<?php
/**
 * Modèle Réservation : une équipe réserve une session (une salle, une date).
 * Statut par défaut "en_attente", confirmé/annulé par un administrateur.
 */
require_once('model/bdd.php');

// Crée une réservation pour une équipe.
function creer_reservation($equipe_id, $salle, $date_session, $nb_joueurs) {
    $pdo = getBdd();
    $sql = "INSERT INTO reservations (equipe_id, salle, date_session, nb_joueurs)
            VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$equipe_id, $salle, $date_session, $nb_joueurs]);
}

// Réservations d'une équipe (espace privé).
function get_reservations_by_equipe($equipe_id) {
    $pdo = getBdd();
    $stmt = $pdo->prepare("SELECT * FROM reservations WHERE equipe_id = ? ORDER BY date_session DESC");
    $stmt->execute([$equipe_id]);
    return $stmt->fetchAll();
}

// Toutes les réservations avec le nom de l'équipe (back-office).
function get_toutes_reservations() {
    $pdo = getBdd();
    $sql = "SELECT r.*, e.nom AS equipe_nom
            FROM reservations r
            JOIN equipes e ON e.id = r.equipe_id
            ORDER BY r.date_session DESC";
    return $pdo->query($sql)->fetchAll();
}

// Change le statut d'une réservation (confirmee / annulee / en_attente).
function update_statut_reservation($id, $statut) {
    $pdo = getBdd();
    $stmt = $pdo->prepare("UPDATE reservations SET statut = ? WHERE id = ?");
    return $stmt->execute([$statut, $id]);
}

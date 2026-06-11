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

// Une réservation précise (pour vérifier qu'elle appartient bien à l'équipe du joueur).
function get_reservation_by_id($id) {
    $pdo = getBdd();
    $stmt = $pdo->prepare("SELECT * FROM reservations WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Capacité : UNE équipe par salle et par soirée. La salle est-elle libre ce soir-là ?
function salle_disponible($salle, $date_sql) {
    $pdo = getBdd();
    $sql = "SELECT COUNT(*) FROM reservations
            WHERE salle = ? AND DATE(date_session) = DATE(?) AND statut != 'annulee'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$salle, $date_sql]);
    return $stmt->fetchColumn() == 0;
}

// Jours du mois où les 3 salles sont déjà prises (affichés "complet" dans le calendrier).
function get_jours_complets($mois) {
    $pdo = getBdd();
    $sql = "SELECT DATE(date_session) AS jour
            FROM reservations
            WHERE DATE_FORMAT(date_session, '%Y-%m') = ? AND statut != 'annulee'
            GROUP BY DATE(date_session)
            HAVING COUNT(DISTINCT salle) >= 3";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$mois]);
    return array_column($stmt->fetchAll(), 'jour');
}

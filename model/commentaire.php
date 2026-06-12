<?php
require_once('model/bdd.php');

function ajouter_commentaire($utilisateur_id, $note, $texte) {
    $pdo = getBdd();
    $sql = "INSERT INTO commentaires (utilisateur_id, note, texte, statut) VALUES (?, ?, ?, 'en_attente')";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$utilisateur_id, $note, $texte]);
}

function get_tous_commentaires() {
    $pdo = getBdd();
    $sql = "SELECT c.*, u.pseudo, u.nom, u.prenom FROM commentaires c JOIN utilisateurs u ON c.utilisateur_id = u.id ORDER BY c.date_creation DESC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

function get_commentaires_approuves() {
    $pdo = getBdd();
    // u.photo : avatar du joueur, affiché à côté de son avis
    $sql = "SELECT c.*, u.pseudo, u.photo FROM commentaires c JOIN utilisateurs u ON c.utilisateur_id = u.id WHERE c.statut = 'approuve' ORDER BY c.date_creation DESC LIMIT 3";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

function update_statut_commentaire($id, $statut) {
    $pdo = getBdd();
    $sql = "UPDATE commentaires SET statut = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$statut, $id]);
}

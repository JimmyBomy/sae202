<?php
require_once('model/bdd.php');

function creer_utilisateur($nom, $prenom, $pseudo, $email, $telephone, $mot_de_passe) {
    $pdo = getBdd();
    $sql = "INSERT INTO utilisateurs (nom, prenom, pseudo, email, telephone, mot_de_passe) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);
    return $stmt->execute([$nom, $prenom, $pseudo, $email, $telephone, $hash]);
}

function get_utilisateur_by_email($email) {
    $pdo = getBdd();
    $sql = "SELECT * FROM utilisateurs WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    return $stmt->fetch();
}

function get_utilisateur_by_id($id) {
    $pdo = getBdd();
    $sql = "SELECT * FROM utilisateurs WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function update_utilisateur($id, $nom, $prenom, $pseudo, $email, $telephone) {
    $pdo = getBdd();
    $sql = "UPDATE utilisateurs SET nom = ?, prenom = ?, pseudo = ?, email = ?, telephone = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$nom, $prenom, $pseudo, $email, $telephone, $id]);
}

function update_utilisateur_password($id, $mot_de_passe) {
    $pdo = getBdd();
    $sql = "UPDATE utilisateurs SET mot_de_passe = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);
    return $stmt->execute([$hash, $id]);
}

function get_tous_utilisateurs() {
    $pdo = getBdd();
    // Jointure avec les équipes pour afficher le nom de l'équipe dans le back-office.
    $sql = "SELECT u.*, e.nom AS equipe_nom
            FROM utilisateurs u
            LEFT JOIN equipes e ON e.id = u.equipe_id
            ORDER BY u.date_inscription DESC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

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

// Enregistre la date de naissance, le questionnaire santé et le régime alimentaire (réservation).
function update_sante_naissance($id, $date_naissance, $cardiaque, $epilepsie, $respiratoire, $claustro, $regime = null) {
    $pdo = getBdd();
    $sql = "UPDATE utilisateurs SET date_naissance = ?, sante_cardiaque = ?, sante_epilepsie = ?,
            sante_respiratoire = ?, sante_claustro = ?, regime = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$date_naissance, $cardiaque, $epilepsie, $respiratoire, $claustro, $regime, $id]);
}

// --- Réinitialisation du mot de passe (lien par email, valable 1 h) ---
function set_reset_token($id, $token) {
    $pdo = getBdd();
    $sql = "UPDATE utilisateurs SET reset_token = ?, reset_expire = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$token, $id]);
}

function get_utilisateur_by_reset_token($token) {
    $pdo = getBdd();
    $sql = "SELECT * FROM utilisateurs WHERE reset_token = ? AND reset_expire > NOW()";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$token]);
    return $stmt->fetch();
}

function clear_reset_token($id) {
    $pdo = getBdd();
    $stmt = $pdo->prepare("UPDATE utilisateurs SET reset_token = NULL, reset_expire = NULL WHERE id = ?");
    return $stmt->execute([$id]);
}

// Met à jour le chemin de la photo de profil.
function update_photo($id, $chemin) {
    $pdo = getBdd();
    $stmt = $pdo->prepare("UPDATE utilisateurs SET photo = ? WHERE id = ?");
    return $stmt->execute([$chemin, $id]);
}

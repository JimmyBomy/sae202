<?php
/**
 * Modèle Équipe : on s'inscrit à l'escape game EN ÉQUIPE.
 * Un joueur crée une équipe (et reçoit un code d'invitation) ou rejoint
 * une équipe existante grâce à ce code.
 */
require_once('model/bdd.php');

// Génère un code d'invitation de 6 caractères, unique (sans I/O/0/1 ambigus).
function generer_code_invite() {
    $caracteres = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    do {
        $code = '';
        for ($i = 0; $i < 6; $i++) {
            $code .= $caracteres[random_int(0, strlen($caracteres) - 1)];
        }
    } while (get_equipe_by_code($code)); // on recommence si le code existe déjà
    return $code;
}

// Crée une équipe et renvoie son id.
function creer_equipe($nom, $createur_id) {
    $pdo = getBdd();
    $code = generer_code_invite();
    $sql = "INSERT INTO equipes (nom, code_invite, createur_id) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nom, $code, $createur_id]);
    return (int) $pdo->lastInsertId();
}

function get_equipe_by_id($id) {
    $pdo = getBdd();
    $stmt = $pdo->prepare("SELECT * FROM equipes WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function get_equipe_by_code($code) {
    $pdo = getBdd();
    $stmt = $pdo->prepare("SELECT * FROM equipes WHERE code_invite = ?");
    $stmt->execute([$code]);
    return $stmt->fetch();
}

function get_equipe_by_nom($nom) {
    $pdo = getBdd();
    $stmt = $pdo->prepare("SELECT * FROM equipes WHERE nom = ?");
    $stmt->execute([$nom]);
    return $stmt->fetch();
}

// Liste des membres d'une équipe.
function get_membres_equipe($equipe_id) {
    $pdo = getBdd();
    $stmt = $pdo->prepare("SELECT id, pseudo, prenom, nom FROM utilisateurs WHERE equipe_id = ? ORDER BY pseudo");
    $stmt->execute([$equipe_id]);
    return $stmt->fetchAll();
}

// Rattache un utilisateur à une équipe.
function assigner_equipe($user_id, $equipe_id) {
    $pdo = getBdd();
    $stmt = $pdo->prepare("UPDATE utilisateurs SET equipe_id = ? WHERE id = ?");
    return $stmt->execute([$equipe_id, $user_id]);
}

// Liste de toutes les équipes avec leur nombre de membres (back-office).
function get_toutes_equipes() {
    $pdo = getBdd();
    $sql = "SELECT e.*, COUNT(u.id) AS nb_membres
            FROM equipes e
            LEFT JOIN utilisateurs u ON u.equipe_id = e.id
            GROUP BY e.id
            ORDER BY e.date_creation DESC";
    return $pdo->query($sql)->fetchAll();
}

<?php
require_once('model/bdd.php');

// Enregistre un message envoyé depuis le formulaire de contact (boîte de réception du back-office).
function ajouter_message($nom, $email, $sujet, $message) {
    $pdo = getBdd();
    $sql = "INSERT INTO messages (nom, email, sujet, message) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$nom, $email, $sujet, $message]);
}

// Tous les messages reçus, du plus récent au plus ancien.
function get_tous_messages() {
    $pdo = getBdd();
    return $pdo->query("SELECT * FROM messages ORDER BY date_creation DESC")->fetchAll();
}

// Marque un message comme lu.
function marquer_message_lu($id) {
    $pdo = getBdd();
    return $pdo->prepare("UPDATE messages SET lu = 1 WHERE id = ?")->execute([$id]);
}

// Supprime un message.
function supprimer_message($id) {
    $pdo = getBdd();
    return $pdo->prepare("DELETE FROM messages WHERE id = ?")->execute([$id]);
}

// Nombre de messages non lus (badge du back-office).
function compter_messages_non_lus() {
    $pdo = getBdd();
    return (int) $pdo->query("SELECT COUNT(*) FROM messages WHERE lu = 0")->fetchColumn();
}

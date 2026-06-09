<?php
/**
 * Contrôleur Réservation : inscription PAR ÉQUIPE puis réservation d'une session.
 * Accessible uniquement aux utilisateurs connectés.
 */
require_once('model/utilisateur.php');
require_once('model/equipe.php');
require_once('model/reservation.php');
require_once('model/score.php');

function index() {
    // Garde d'accès : il faut être connecté pour réserver.
    if (!isset($_SESSION['user_id'])) {
        header('Location: ' . BASE_URL . '/compte/connexion');
        exit;
    }

    $utilisateur = get_utilisateur_by_id($_SESSION['user_id']);
    $erreur = '';
    $succes = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $form = $_POST['form_type'] ?? '';

        // --- Créer une nouvelle équipe ---
        if ($form === 'creer_equipe') {
            $nom = trim($_POST['nom_equipe'] ?? '');
            if (!empty($utilisateur['equipe_id'])) {
                $erreur = "Vous faites déjà partie d'une équipe.";
            } elseif (empty($nom)) {
                $erreur = "Le nom de l'équipe est obligatoire.";
            } elseif (get_equipe_by_nom($nom)) {
                $erreur = "Ce nom d'équipe est déjà pris, choisissez-en un autre.";
            } else {
                $equipe_id = creer_equipe($nom, $utilisateur['id']);
                assigner_equipe($utilisateur['id'], $equipe_id);
                $succes = "Équipe créée ! Partagez le code d'invitation avec vos coéquipiers.";
            }

        // --- Rejoindre une équipe existante via son code ---
        } elseif ($form === 'rejoindre_equipe') {
            $code = strtoupper(trim($_POST['code_invite'] ?? ''));
            if (!empty($utilisateur['equipe_id'])) {
                $erreur = "Vous faites déjà partie d'une équipe.";
            } elseif ($code === '') {
                $erreur = "Veuillez saisir un code d'invitation.";
            } else {
                $equipe = get_equipe_by_code($code);
                if (!$equipe) {
                    $erreur = "Code d'invitation invalide.";
                } else {
                    assigner_equipe($utilisateur['id'], $equipe['id']);
                    $succes = "Vous avez rejoint l'équipe « " . htmlspecialchars($equipe['nom']) . " ».";
                }
            }

        // --- Réserver une session (il faut déjà avoir une équipe) ---
        } elseif ($form === 'reserver') {
            $utilisateur = get_utilisateur_by_id($_SESSION['user_id']); // données à jour
            $salle = $_POST['salle'] ?? '';
            $date_session = $_POST['date_session'] ?? '';
            $nb_joueurs = (int) ($_POST['nb_joueurs'] ?? 0);
            $salles_valides = ['facile', 'standard', 'hardcore'];

            if (empty($utilisateur['equipe_id'])) {
                $erreur = "Vous devez d'abord créer ou rejoindre une équipe.";
            } elseif (!in_array($salle, $salles_valides, true)) {
                $erreur = "Veuillez choisir une salle valide.";
            } elseif (empty($date_session) || strtotime($date_session) === false || strtotime($date_session) < time()) {
                $erreur = "Veuillez choisir une date et une heure dans le futur.";
            } elseif ($nb_joueurs < 2 || $nb_joueurs > 6) {
                $erreur = "Le nombre de joueurs doit être compris entre 2 et 6.";
            } else {
                // Conversion du format "datetime-local" (2026-06-20T20:00) vers DATETIME MySQL.
                $date_sql = date('Y-m-d H:i:s', strtotime($date_session));
                creer_reservation($utilisateur['equipe_id'], $salle, $date_sql, $nb_joueurs);
                $succes = "Votre réservation a bien été enregistrée (en attente de confirmation).";
            }
        }

        // On recharge l'utilisateur (son équipe a pu changer).
        $utilisateur = get_utilisateur_by_id($_SESSION['user_id']);
    }

    // Données à afficher dans la vue.
    $equipe        = !empty($utilisateur['equipe_id']) ? get_equipe_by_id($utilisateur['equipe_id']) : null;
    $membres       = $equipe ? get_membres_equipe($equipe['id']) : [];
    $reservations  = $equipe ? get_reservations_by_equipe($equipe['id']) : [];
    $scores        = $equipe ? get_scores_by_equipe($equipe['id']) : [];

    $titrePage = 'Réserver une session';
    require('view/inc/header.php');
    require('view/reservation/index.php');
    require('view/inc/footer.php');
}

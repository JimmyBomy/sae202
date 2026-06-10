<?php
/**
 * Contrôleur Réservation : page complète d'inscription à une session
 * (infos joueur + équipe, calendrier des disponibilités, questionnaire
 * santé & sécurité, paiement). Accessible uniquement connecté.
 */
require_once('model/utilisateur.php');
require_once('model/equipe.php');
require_once('model/reservation.php');

function index() {
    // Garde d'accès : il faut être connecté pour réserver.
    if (!isset($_SESSION['user_id'])) {
        header('Location: ' . BASE_URL . '/compte/connexion');
        exit;
    }

    $utilisateur = get_utilisateur_by_id($_SESSION['user_id']);
    $erreur = '';
    $succes = '';

    // Mois affiché dans le calendrier (par défaut juin 2026, mois de l'événement).
    $mois = (isset($_GET['mois']) && preg_match('/^\d{4}-\d{2}$/', $_GET['mois'])) ? $_GET['mois'] : '2026-06';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // --- 1) Infos personnelles (on met à jour le compte) ---
        $nom       = trim($_POST['nom'] ?? '');
        $prenom    = trim($_POST['prenom'] ?? '');
        $email     = trim($_POST['email'] ?? '');
        $telephone = trim($_POST['telephone'] ?? '');

        // --- 2) Équipe + session ---
        $nom_equipe   = trim($_POST['nom_equipe'] ?? '');
        $nb_joueurs   = (int) ($_POST['nb_joueurs'] ?? 0);
        $date_session = $_POST['date_session'] ?? '';      // format AAAA-MM-JJ (calendrier)
        $paiement     = $_POST['paiement'] ?? 'sur_place'; // 'carte' ou 'sur_place'

        // --- Validations ---
        if ($nom === '' || $prenom === '' || $email === '') {
            $erreur = "Merci de remplir vos nom, prénom et email.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreur = "L'adresse email n'est pas valide.";
        } elseif (empty($utilisateur['equipe_id']) && $nom_equipe === '') {
            $erreur = "Indiquez un nom d'équipe.";
        } elseif (empty($utilisateur['equipe_id']) && get_equipe_by_nom($nom_equipe)) {
            $erreur = "Ce nom d'équipe est déjà pris.";
        } elseif ($date_session === '' || strtotime($date_session) === false) {
            $erreur = "Choisissez une date dans le calendrier des disponibilités.";
        } elseif (strtotime($date_session . ' 20:00:00') < time()) {
            $erreur = "Cette date est déjà passée, choisissez-en une autre.";
        } elseif ($nb_joueurs < 2 || $nb_joueurs > 6) {
            $erreur = "Le nombre de participants doit être compris entre 2 et 6.";
        } else {
            // Email déjà pris par quelqu'un d'autre ?
            $autre = get_utilisateur_by_email($email);
            if ($autre && (int)$autre['id'] !== (int)$utilisateur['id']) {
                $erreur = "Cette adresse email est déjà utilisée par un autre compte.";
            } else {
                // Mise à jour des infos du compte (pseudo inchangé).
                update_utilisateur($utilisateur['id'], $nom, $prenom, $utilisateur['pseudo'], $email, $telephone);

                // Équipe : on prend celle de l'utilisateur, sinon on la crée.
                if (!empty($utilisateur['equipe_id'])) {
                    $equipe_id = (int) $utilisateur['equipe_id'];
                } else {
                    $equipe_id = creer_equipe($nom_equipe, $utilisateur['id']);
                    assigner_equipe($utilisateur['id'], $equipe_id);
                }

                // Réservation (heure par défaut 20h, salle standard par défaut).
                $date_sql = date('Y-m-d', strtotime($date_session)) . ' 20:00:00';
                creer_reservation($equipe_id, 'standard', $date_sql, $nb_joueurs);

                // Paiement par carte = confirmée tout de suite ; sur place = en attente.
                if ($paiement === 'carte') {
                    $pdo = getBdd();
                    $rid = (int) $pdo->lastInsertId();
                    update_statut_reservation($rid, 'confirmee');
                    $succes = "Paiement accepté et réservation confirmée ! Rendez-vous le "
                            . htmlspecialchars(date('d/m/Y', strtotime($date_session))) . " à 20h.";
                } else {
                    $succes = "Réservation enregistrée (paiement sur place) pour le "
                            . htmlspecialchars(date('d/m/Y', strtotime($date_session))) . " à 20h.";
                }

                $utilisateur = get_utilisateur_by_id($_SESSION['user_id']);
            }
        }
    }

    // Données pour la vue.
    $equipe       = !empty($utilisateur['equipe_id']) ? get_equipe_by_id($utilisateur['equipe_id']) : null;
    $reservations = $equipe ? get_reservations_by_equipe($equipe['id']) : [];

    $titrePage = 'Réserver une session';
    $page_class = 'page-concept'; // même fond liminal
    require('view/inc/header.php');
    require('view/reservation/index.php');
    require('view/inc/footer.php');
}

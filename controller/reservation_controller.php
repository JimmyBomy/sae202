<?php
/**
 * Contrôleur Réservation : page d'inscription à une session (maquette).
 * Accessible à TOUS (c'est la page d'inscription). Si l'utilisateur n'est pas
 * connecté, le formulaire crée son compte ; s'il l'est, on réutilise son compte.
 */
require_once('model/utilisateur.php');
require_once('model/equipe.php');
require_once('model/reservation.php');

function index() {
    $estConnecte = isset($_SESSION['user_id']);
    $utilisateur = $estConnecte ? get_utilisateur_by_id($_SESSION['user_id']) : null;
    $erreur = '';
    $succes = '';

    // Mois affiché dans le calendrier (par défaut juin 2026, mois de l'événement).
    $mois = (isset($_GET['mois']) && preg_match('/^\d{4}-\d{2}$/', $_GET['mois'])) ? $_GET['mois'] : '2026-06';

    // Navigation du calendrier : on change SEULEMENT le mois affiché.
    // La saisie de l'utilisateur est renvoyée dans $_POST et re-remplie par la vue.
    $changeMois = ($_SERVER['REQUEST_METHOD'] === 'POST'
                   && isset($_POST['mois_aff'])
                   && preg_match('/^\d{4}-\d{2}$/', $_POST['mois_aff']));
    if ($changeMois) {
        $mois = $_POST['mois_aff'];
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$changeMois) {
        $nom       = trim($_POST['nom'] ?? '');
        $prenom    = trim($_POST['prenom'] ?? '');
        $email     = trim($_POST['email'] ?? '');
        $telephone = trim($_POST['telephone'] ?? '');

        $nom_equipe   = trim($_POST['nom_equipe'] ?? '');
        $nb_joueurs   = (int) ($_POST['nb_joueurs'] ?? 0);
        $date_session = $_POST['date_session'] ?? '';      // AAAA-MM-JJ (calendrier)
        $paiement     = $_POST['paiement'] ?? 'sur_place';

        // --- Validations communes ---
        if ($nom === '' || $prenom === '' || $email === '') {
            $erreur = "Merci de remplir vos nom, prénom et email.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreur = "L'adresse email n'est pas valide.";
        } elseif (empty($utilisateur['equipe_id']) && $nom_equipe === '') {
            $erreur = "Indiquez un nom d'équipe.";
        } elseif ($date_session === '' || strtotime($date_session) === false) {
            $erreur = "Choisissez une date dans le calendrier des disponibilités.";
        } elseif (strtotime($date_session . ' 20:00:00') < time()) {
            $erreur = "Cette date est déjà passée, choisissez-en une autre.";
        } elseif ($nb_joueurs < 2 || $nb_joueurs > 6) {
            $erreur = "Le nombre de participants doit être compris entre 2 et 6.";
        } elseif (empty($utilisateur['equipe_id']) && get_equipe_by_nom($nom_equipe)) {
            $erreur = "Ce nom d'équipe est déjà pris.";
        } else {
            // --- Compte : création (visiteur) ou mise à jour (connecté) ---
            if (!$estConnecte) {
                if (get_utilisateur_by_email($email)) {
                    $erreur = "Un compte existe déjà avec cet email — connectez-vous d'abord.";
                } else {
                    // Pseudo dérivé de l'email (l'unicité porte sur l'email).
                    $pseudo = substr(explode('@', $email)[0], 0, 60);
                    // Réservation visiteur : mot de passe généré automatiquement.
                    $mdpAuto = bin2hex(random_bytes(5));
                    creer_utilisateur($nom, $prenom, $pseudo, $email, $telephone, $mdpAuto);
                    $utilisateur = get_utilisateur_by_email($email);
                    $_SESSION['user_id']   = $utilisateur['id'];
                    $_SESSION['user_role'] = $utilisateur['role'];
                    $estConnecte = true;
                }
            } else {
                $autre = get_utilisateur_by_email($email);
                if ($autre && (int)$autre['id'] !== (int)$utilisateur['id']) {
                    $erreur = "Cette adresse email est déjà utilisée par un autre compte.";
                } else {
                    update_utilisateur($utilisateur['id'], $nom, $prenom, $utilisateur['pseudo'], $email, $telephone);
                    $utilisateur = get_utilisateur_by_id($utilisateur['id']);
                }
            }

            // --- Équipe + réservation (si pas d'erreur de compte) ---
            if ($erreur === '') {
                if (!empty($utilisateur['equipe_id'])) {
                    $equipe_id = (int) $utilisateur['equipe_id'];
                } else {
                    $equipe_id = creer_equipe($nom_equipe, $utilisateur['id']);
                    assigner_equipe($utilisateur['id'], $equipe_id);
                }

                $date_sql = date('Y-m-d', strtotime($date_session)) . ' 20:00:00';
                creer_reservation($equipe_id, 'standard', $date_sql, $nb_joueurs);

                if ($paiement === 'carte') {
                    $rid = (int) getBdd()->lastInsertId();
                    update_statut_reservation($rid, 'confirmee');
                    $succes = "Paiement accepté, réservation confirmée ! Rendez-vous le "
                            . htmlspecialchars(date('d/m/Y', strtotime($date_session))) . " à 20h.";
                } else {
                    $succes = "Réservation enregistrée (paiement sur place) pour le "
                            . htmlspecialchars(date('d/m/Y', strtotime($date_session))) . " à 20h.";
                }
                $utilisateur = get_utilisateur_by_id($utilisateur['id']);
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

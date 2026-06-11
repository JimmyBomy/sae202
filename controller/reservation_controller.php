<?php
/**
 * Contrôleur Réservation : page d'inscription à une session (maquette).
 * Accessible à TOUS (c'est la page d'inscription). Si l'utilisateur n'est pas
 * connecté, le formulaire crée son compte ; s'il l'est, on réutilise son compte.
 * On peut soit CRÉER une équipe (nom), soit en REJOINDRE une (code d'invitation).
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
        $code_invite  = strtoupper(trim($_POST['code_invite'] ?? ''));
        $nb_joueurs   = (int) ($_POST['nb_joueurs'] ?? 0);
        $salle        = $_POST['salle'] ?? '';
        $date_session = $_POST['date_session'] ?? '';      // AAAA-MM-JJ (calendrier)
        $paiement     = $_POST['paiement'] ?? 'sur_place';
        $equipeRejointe = null;

        // --- Validations communes ---
        if (!csrf_verifie()) {
            $erreur = "Session expirée, veuillez renvoyer le formulaire.";
        } elseif ($nom === '' || $prenom === '' || $email === '') {
            $erreur = "Merci de remplir vos nom, prénom et email.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreur = "L'adresse email n'est pas valide.";
        } elseif (empty($utilisateur['equipe_id']) && $nom_equipe === '' && $code_invite === '') {
            $erreur = "Indiquez un nom d'équipe (pour la créer) OU un code d'invitation (pour la rejoindre).";
        } elseif (!in_array($salle, ['facile', 'standard', 'hardcore'], true)) {
            $erreur = "Choisissez une salle.";
        } elseif ($date_session === '' || strtotime($date_session) === false) {
            $erreur = "Choisissez une date dans le calendrier des disponibilités.";
        } elseif (strtotime($date_session . ' 20:00:00') < time()) {
            $erreur = "Cette date est déjà passée, choisissez-en une autre.";
        } elseif ($nb_joueurs < 2 || $nb_joueurs > 6) {
            $erreur = "Le nombre de participants doit être compris entre 2 et 6.";
        } elseif (empty($utilisateur['equipe_id']) && $code_invite !== '') {
            // Rejoindre une équipe existante grâce à son code d'invitation.
            $equipeRejointe = get_equipe_by_code($code_invite);
            if (!$equipeRejointe) {
                $erreur = "Code d'invitation invalide.";
            }
        } elseif (empty($utilisateur['equipe_id']) && get_equipe_by_nom($nom_equipe)) {
            $erreur = "Ce nom d'équipe est déjà pris. (Pour la rejoindre, utilisez son code d'invitation.)";
        }

        if ($erreur === '') {
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
                    $_SESSION['mdp_auto']  = 1; // il devra définir son mot de passe dans Mon espace
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
                } elseif ($equipeRejointe) {
                    $equipe_id = (int) $equipeRejointe['id'];
                    assigner_equipe($utilisateur['id'], $equipe_id);
                } else {
                    $equipe_id = creer_equipe($nom_equipe, $utilisateur['id']);
                    assigner_equipe($utilisateur['id'], $equipe_id);
                }

                // Date de naissance (3 listes) + questionnaire santé : on les conserve
                // sur le compte (l'organisateur doit connaître les contre-indications).
                $j = (int)($_POST['naiss_jour'] ?? 0); $m = (int)($_POST['naiss_mois'] ?? 0); $a = (int)($_POST['naiss_annee'] ?? 0);
                $naissance = checkdate($m, $j, $a) ? sprintf('%04d-%02d-%02d', $a, $m, $j) : null;
                $sante = fn($k) => in_array($_POST[$k] ?? '', ['oui', 'non'], true) ? $_POST[$k] : null;
                update_sante_naissance($utilisateur['id'], $naissance,
                    $sante('sante_cardiaque'), $sante('sante_epilepsie'),
                    $sante('sante_respiratoire'), $sante('sante_claustro'));

                $date_sql = date('Y-m-d', strtotime($date_session)) . ' 20:00:00';
                creer_reservation($equipe_id, $salle, $date_sql, $nb_joueurs);

                $confirmee = ($paiement === 'carte');
                if ($confirmee) {
                    $rid = (int) getBdd()->lastInsertId();
                    update_statut_reservation($rid, 'confirmee');
                    $succes = "Paiement accepté, réservation confirmée ! Rendez-vous le "
                            . htmlspecialchars(date('d/m/Y', strtotime($date_session))) . " à 20h.";
                } else {
                    $succes = "Réservation enregistrée (paiement sur place) pour le "
                            . htmlspecialchars(date('d/m/Y', strtotime($date_session))) . " à 20h.";
                }
                if (!empty($_SESSION['mdp_auto'])) {
                    $succes .= " Pensez à définir votre mot de passe dans Mon espace pour pouvoir vous reconnecter.";
                }

                // --- Email de confirmation (promis dans les mentions légales) ---
                envoyer_confirmation_reservation($utilisateur, $equipe_id, $salle, $date_sql, $nb_joueurs, $confirmee);

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

// Envoie le mail de confirmation de réservation au joueur.
function envoyer_confirmation_reservation($utilisateur, $equipe_id, $salle, $date_sql, $nb_joueurs, $confirmee) {
    $equipe = get_equipe_by_id($equipe_id);
    $noms   = ['facile' => 'Salle 1 — Le Niveau 0', 'standard' => 'Salle 2 — Les Couloirs jaunes', 'hardcore' => 'Salle 3 — Le Niveau !'];

    $sujet = 'BACKROOMS — Confirmation de votre réservation';
    $corps = "Bonjour " . $utilisateur['prenom'] . ",\n\n"
           . "Votre réservation a bien été " . ($confirmee ? "confirmée (payée en ligne)" : "enregistrée (paiement sur place)") . " :\n\n"
           . "  Équipe   : " . $equipe['nom'] . " (code d'invitation : " . $equipe['code_invite'] . ")\n"
           . "  Salle    : " . ($noms[$salle] ?? $salle) . "\n"
           . "  Date     : " . date('d/m/Y', strtotime($date_sql)) . " à 20h00\n"
           . "  Joueurs  : " . $nb_joueurs . "\n\n"
           . "Présentez-vous 30 minutes avant le début de la session.\n"
           . "12 rue des Liminaux, 10000 Troyes\n\n"
           . "Vous n'auriez jamais dû trouver cet endroit…\n"
           . "L'équipe BACKROOMS";
    $headers = "From: BACKROOMS <no-reply@sae202.mmi25c02.mmi-troyes.fr>\r\n"
             . "Content-type: text/plain; charset=utf-8\r\n";
    // @ : l'échec d'envoi ne doit pas casser la réservation.
    @mail($utilisateur['email'], $sujet, $corps, $headers);
}

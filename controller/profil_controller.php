<?php
require_once('model/utilisateur.php');
require_once('model/equipe.php');
require_once('model/score.php');
require_once('model/reservation.php');

function index() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ' . BASE_URL . '/compte/connexion');
        exit;
    }

    $utilisateur = get_utilisateur_by_id($_SESSION['user_id']);
    $erreur = '';
    $succes = '';

    // --- Annulation d'une réservation par le joueur (uniquement celles de SON équipe, à venir) ---
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['annuler_resa'])) {
        if (!csrf_verifie()) {
            $erreur = 'Session expirée, veuillez réessayer.';
        } else {
            $resa = get_reservation_by_id((int) $_POST['annuler_resa']);
            if ($resa
                && (int) $resa['equipe_id'] === (int) ($utilisateur['equipe_id'] ?? 0)
                && strtotime($resa['date_session']) > time()
                && $resa['statut'] !== 'annulee') {
                update_statut_reservation($resa['id'], 'annulee');
                $succes = 'Réservation annulée.';
            } else {
                $erreur = 'Cette réservation ne peut pas être annulée.';
            }
        }

    // --- "MODIFIER MES INFORMATIONS" (pseudo / email / téléphone / mot de passe) ---
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $pseudo      = htmlspecialchars(trim($_POST['pseudo'] ?? ''));
        $email       = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $telephone   = htmlspecialchars(trim($_POST['telephone'] ?? ''));
        $nouveau_mdp = $_POST['mot_de_passe'] ?? '';

        if (!csrf_verifie()) {
            $erreur = 'Session expirée, veuillez renvoyer le formulaire.';
        } elseif (empty($pseudo) || empty($email)) {
            $erreur = 'Le pseudo et l\'email sont obligatoires.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreur = 'L\'adresse email n\'est pas valide.';
        } elseif ($nouveau_mdp !== '' && strlen($nouveau_mdp) < 6) {
            $erreur = 'Le nouveau mot de passe doit faire au moins 6 caractères.';
        } else {
            $exist = get_utilisateur_by_email($email);
            if ($exist && $exist['id'] != $utilisateur['id']) {
                $erreur = 'Cette adresse email est déjà utilisée.';
            } else {
                // On garde le nom/prénom existants (la maquette ne les édite pas ici).
                update_utilisateur($utilisateur['id'], $utilisateur['nom'], $utilisateur['prenom'], $pseudo, $email, $telephone);
                if ($nouveau_mdp !== '') {
                    update_utilisateur_password($utilisateur['id'], $nouveau_mdp);
                    unset($_SESSION['mdp_auto']); // il connaît désormais son mot de passe
                }

                // --- Photo de profil (optionnelle) ---
                if (!empty($_FILES['photo']['tmp_name']) && is_uploaded_file($_FILES['photo']['tmp_name'])) {
                    $resultat = enregistrer_avatar($_FILES['photo'], $utilisateur);
                    if ($resultat !== true) {
                        $erreur = $resultat; // message d'erreur de validation
                    }
                }

                if ($erreur === '') { $succes = 'Vos informations ont été mises à jour.'; }
                $utilisateur = get_utilisateur_by_id($_SESSION['user_id']);
            }
        }
    }

    // --- Données de l'espace privé ---
    $equipe       = !empty($utilisateur['equipe_id']) ? get_equipe_by_id($utilisateur['equipe_id']) : null;
    $membres      = $equipe ? get_membres_equipe($equipe['id']) : [];
    $scores       = $equipe ? get_scores_by_equipe($equipe['id']) : [];
    $reservations = $equipe ? get_reservations_by_equipe($equipe['id']) : [];

    // --- Statistiques calculées ---
    $nbParties = count($scores);
    $points    = array_sum(array_column($scores, 'points'));
    $reussis   = array_filter($scores, fn($s) => $s['reussi']);
    $taux      = $nbParties ? round(count($reussis) / $nbParties * 100) : 0;
    $temps     = array_filter(array_column($reussis, 'temps_secondes'), fn($t) => $t !== null);
    $tempsMoyen = $temps ? (int) (array_sum($temps) / count($temps)) : null;
    $sallesExplorees = $reservations ? count(array_unique(array_column($reservations, 'salle'))) : 0;
    // Niveau le plus difficile joué
    $ordre = ['facile' => 1, 'standard' => 2, 'hardcore' => 3];
    $niveauMax = '—';
    $maxRang = 0;
    foreach ($reservations as $r) {
        if (isset($ordre[$r['salle']]) && $ordre[$r['salle']] > $maxRang) {
            $maxRang = $ordre[$r['salle']];
            $niveauMax = ucfirst($r['salle']);
        }
    }

    $titrePage = 'Mon espace';
    $page_class = 'page-concept';
    require('view/inc/header.php');
    require('view/profil/index.php');
    require('view/inc/footer.php');
}

function password() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ' . BASE_URL . '/compte/connexion');
        exit;
    }

    $utilisateur = get_utilisateur_by_id($_SESSION['user_id']);
    $erreur = '';
    $succes = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $mdp_actuel = $_POST['mot_de_passe_actuel'] ?? '';
        $nouveau_mdp = $_POST['nouveau_mot_de_passe'] ?? '';
        $confirm_mdp = $_POST['confirmation_mot_de_passe'] ?? '';

        if (!csrf_verifie()) {
            $erreur = 'Session expirée, veuillez renvoyer le formulaire.';
        } elseif (empty($mdp_actuel) || empty($nouveau_mdp) || empty($confirm_mdp)) {
            $erreur = 'Tous les champs sont obligatoires.';
        } elseif (!password_verify($mdp_actuel, $utilisateur['mot_de_passe'])) {
            $erreur = 'Le mot de passe actuel est incorrect.';
        } elseif ($nouveau_mdp !== $confirm_mdp) {
            $erreur = 'Les nouveaux mots de passe ne correspondent pas.';
        } elseif (strlen($nouveau_mdp) < 6) {
            $erreur = 'Le nouveau mot de passe doit contenir au moins 6 caractères.';
        } else {
            if (update_utilisateur_password($utilisateur['id'], $nouveau_mdp)) {
                unset($_SESSION['mdp_auto']); // il connaît désormais son mot de passe
                $succes = 'Mot de passe mis à jour avec succès.';
            } else {
                $erreur = 'Erreur lors de la mise à jour du mot de passe.';
            }
        }
    }

    $titrePage = 'Modifier le mot de passe';
    require('view/inc/header.php');
    require('view/profil/password.php');
    require('view/inc/footer.php');
}

function commentaire() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ' . BASE_URL . '/compte/connexion');
        exit;
    }

    require_once('model/commentaire.php');
    $erreur = '';
    $succes = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $note = (int)($_POST['note'] ?? 0);
        $texte = htmlspecialchars(trim($_POST['texte'] ?? ''));

        if (!csrf_verifie()) {
            $erreur = 'Session expirée, veuillez renvoyer le formulaire.';
        } elseif ($note < 1 || $note > 5 || empty($texte)) {
            $erreur = 'Veuillez remplir tous les champs correctement.';
        } else {
            if (ajouter_commentaire($_SESSION['user_id'], $note, $texte)) {
                $succes = 'Votre commentaire a été soumis et est en attente de modération.';
            } else {
                $erreur = 'Une erreur est survenue lors de l\'envoi du commentaire.';
            }
        }
    }

    $titrePage = 'Laisser un avis';
    require('view/inc/header.php');
    require('view/profil/commentaire.php');
    require('view/inc/footer.php');
}

/**
 * Valide et enregistre l'avatar envoyé (max 2 Mo, JPEG/PNG/WebP).
 * L'image est recadrée en carré et recompressée en JPEG 240x240 via GD :
 * on ne stocke JAMAIS le fichier d'origine tel quel (sécurité + poids).
 * Renvoie true si OK, sinon le message d'erreur à afficher.
 */
function enregistrer_avatar($fichier, $utilisateur) {
    if ($fichier['error'] !== UPLOAD_ERR_OK || $fichier['size'] > 2 * 1024 * 1024) {
        return "Photo refusée : fichier invalide ou supérieur à 2 Mo.";
    }
    // Le vrai type est lu dans le contenu (pas l'extension, falsifiable).
    $infos = getimagesize($fichier['tmp_name']);
    $types = [IMAGETYPE_JPEG => 'jpeg', IMAGETYPE_PNG => 'png', IMAGETYPE_WEBP => 'webp'];
    if ($infos === false || !isset($types[$infos[2]])) {
        return "Photo refusée : formats acceptés JPEG, PNG ou WebP.";
    }

    $creer  = 'imagecreatefrom' . $types[$infos[2]];
    $source = @$creer($fichier['tmp_name']);
    if (!$source) {
        return "Photo illisible, réessayez avec une autre image.";
    }

    // Recadrage carré centré puis redimensionnement en 240x240.
    $larg = imagesx($source); $haut = imagesy($source);
    $cote = min($larg, $haut);
    $avatar = imagecreatetruecolor(240, 240);
    imagecopyresampled($avatar, $source, 0, 0,
        (int) (($larg - $cote) / 2), (int) (($haut - $cote) / 2),
        240, 240, $cote, $cote);

    // Nom imprévisible + suppression de l'ancienne photo.
    $nomFichier = 'u' . $utilisateur['id'] . '_' . bin2hex(random_bytes(6)) . '.jpg';
    $dossier = 'view/uploads/avatars/';
    if (!empty($utilisateur['photo']) && file_exists($dossier . $utilisateur['photo'])) {
        unlink($dossier . $utilisateur['photo']);
    }
    imagejpeg($avatar, $dossier . $nomFichier, 85);
    imagedestroy($source);
    imagedestroy($avatar);

    update_photo($utilisateur['id'], $nomFichier);
    return true;
}

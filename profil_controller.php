<?php
require_once('model/utilisateur.php');
require_once('model/equipe.php');
require_once('model/score.php');

function index() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ' . BASE_URL . '/compte/connexion');
        exit;
    }

    $utilisateur = get_utilisateur_by_id($_SESSION['user_id']);

    $erreur = '';
    $succes = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom = htmlspecialchars(trim($_POST['nom'] ?? ''));
        $prenom = htmlspecialchars(trim($_POST['prenom'] ?? ''));
        $pseudo = htmlspecialchars(trim($_POST['pseudo'] ?? ''));
        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $telephone = htmlspecialchars(trim($_POST['telephone'] ?? ''));

        if (empty($nom) || empty($prenom) || empty($pseudo) || empty($email)) {
            $erreur = 'Tous les champs avec * sont obligatoires.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreur = 'L\'adresse email n\'est pas valide.';
        } else {
            $exist = get_utilisateur_by_email($email);
            if ($exist && $exist['id'] != $utilisateur['id']) {
                $erreur = 'Cette adresse email est déjà utilisée.';
            } else {
                if (update_utilisateur($utilisateur['id'], $nom, $prenom, $pseudo, $email, $telephone)) {
                    $succes = 'Votre profil a été mis à jour avec succès.';
                    $utilisateur = get_utilisateur_by_id($_SESSION['user_id']);
                } else {
                    $erreur = 'Une erreur est survenue lors de la mise à jour.';
                }
            }
        }
    }

    // Équipe et scores du joueur (affichés en lecture seule dans l'espace privé).
    $equipe = !empty($utilisateur['equipe_id']) ? get_equipe_by_id($utilisateur['equipe_id']) : null;
    $scores = $equipe ? get_scores_by_equipe($equipe['id']) : [];

    $titrePage = 'Mon Profil';
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

        if (empty($mdp_actuel) || empty($nouveau_mdp) || empty($confirm_mdp)) {
            $erreur = 'Tous les champs sont obligatoires.';
        } elseif (!password_verify($mdp_actuel, $utilisateur['mot_de_passe'])) {
            $erreur = 'Le mot de passe actuel est incorrect.';
        } elseif ($nouveau_mdp !== $confirm_mdp) {
            $erreur = 'Les nouveaux mots de passe ne correspondent pas.';
        } elseif (strlen($nouveau_mdp) < 6) {
            $erreur = 'Le nouveau mot de passe doit contenir au moins 6 caractères.';
        } else {
            if (update_utilisateur_password($utilisateur['id'], $nouveau_mdp)) {
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

        if ($note < 1 || $note > 5 || empty($texte)) {
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

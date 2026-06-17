<?php
require_once('model/utilisateur.php');
require_once('model/mailer.php');

function inscription() {
    $erreur = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom = $_POST['nom'] ?? '';
        $prenom = $_POST['prenom'] ?? '';
        $pseudo = $_POST['pseudo'] ?? '';
        $email = $_POST['email'] ?? '';
        $telephone = $_POST['telephone'] ?? '';
        $password = $_POST['mot_de_passe'] ?? '';

        if (!csrf_verifie()) {
            $erreur = "Session expirée, veuillez renvoyer le formulaire.";
        } elseif (!empty($nom) && !empty($prenom) && !empty($pseudo) && !empty($email) && !empty($password)) {
            if (!get_utilisateur_by_email($email)) {
                creer_utilisateur($nom, $prenom, $pseudo, $email, $telephone, $password);
                header('Location: ' . BASE_URL . '/compte/connexion');
                exit;
            } else {
                $erreur = "Cet email est déjà utilisé.";
            }
        } else {
            $erreur = "Veuillez remplir tous les champs obligatoires.";
        }
    }

    $titrePage = 'Inscription';
    require_once('view/inc/header.php');
    require_once('view/compte/inscription.php');
    require_once('view/inc/footer.php');
}

function connexion() {
    $erreur = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'] ?? '';
        $password = $_POST['mot_de_passe'] ?? '';

        // --- Anti-bruteforce : après 5 échecs, on impose 60 s d'attente. ---
        $essais  = $_SESSION['login_essais'] ?? 0;
        $dernier = $_SESSION['login_dernier'] ?? 0;
        if ($essais >= 5 && (time() - $dernier) < 60) {
            $erreur = "Trop de tentatives. Réessayez dans une minute.";
        } elseif (!csrf_verifie()) {
            $erreur = "Session expirée, veuillez renvoyer le formulaire.";
        } elseif (!empty($email) && !empty($password)) {
            if ($essais >= 5) { // la minute est passée, on remet le compteur à zéro
                $_SESSION['login_essais'] = 0;
            }
            $user = get_utilisateur_by_email($email);
            if ($user && password_verify($password, $user['mot_de_passe'])) {
                session_regenerate_id(true); // évite la fixation de session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                unset($_SESSION['login_essais'], $_SESSION['login_dernier']);
                header('Location: ' . BASE_URL . '/profil');
                exit;
            } else {
                $_SESSION['login_essais']  = ($_SESSION['login_essais'] ?? 0) + 1;
                $_SESSION['login_dernier'] = time();
                $erreur = "Identifiants incorrects.";
            }
        } else {
            $erreur = "Veuillez remplir tous les champs.";
        }
    }

    $titrePage = 'Connexion';
    require_once('view/inc/header.php');
    require_once('view/compte/connexion.php');
    require_once('view/inc/footer.php');
}

function deconnexion() {
    session_destroy();
    header('Location: ' . BASE_URL . '/');
    exit;
}

// --- Mot de passe oublié : demande d'un lien de réinitialisation par email ---
function mdp_oublie() {
    $erreur = '';
    $succes = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email'] ?? '');
        if (!csrf_verifie()) {
            $erreur = "Session expirée, veuillez renvoyer le formulaire.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreur = "Adresse email invalide.";
        } else {
            $user = get_utilisateur_by_email($email);
            if ($user) {
                // Jeton aléatoire, valable 1 h (stocké en BDD).
                $token = bin2hex(random_bytes(32));
                set_reset_token($user['id'], $token);
                $lien = 'https://' . $_SERVER['HTTP_HOST'] . BASE_URL . '/compte/reinitialiser/' . $token;
                $corps = "Bonjour " . $user['prenom'] . ",\n\n"
                       . "Pour choisir un nouveau mot de passe, cliquez sur ce lien (valable 1 heure) :\n"
                       . $lien . "\n\n"
                       . "Si vous n'êtes pas à l'origine de cette demande, ignorez ce message.\n\n"
                       . "L'équipe BACKROOMS";
                envoyer_mail($email, 'BACKROOMS — Réinitialisation du mot de passe', $corps);
            }
            // Même message que le compte existe ou non (on ne révèle rien).
            $succes = "Si un compte existe avec cette adresse, un email de réinitialisation vient d'être envoyé.";
        }
    }
    $titrePage = 'Mot de passe oublié';
    require_once('view/inc/header.php');
    require_once('view/compte/mdp_oublie.php');
    require_once('view/inc/footer.php');
}

// --- Choix du nouveau mot de passe via le lien reçu par email ---
function reinitialiser($token = '') {
    $erreur = '';
    $succes = '';
    $user = preg_match('/^[a-f0-9]{64}$/', $token) ? get_utilisateur_by_reset_token($token) : false;

    if (!$user) {
        $erreur = "Lien invalide ou expiré. Refaites une demande de réinitialisation.";
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $mdp     = $_POST['mot_de_passe'] ?? '';
        $confirm = $_POST['confirmation'] ?? '';
        if (!csrf_verifie()) {
            $erreur = "Session expirée, veuillez renvoyer le formulaire.";
        } elseif (strlen($mdp) < 6) {
            $erreur = "Le mot de passe doit contenir au moins 6 caractères.";
        } elseif ($mdp !== $confirm) {
            $erreur = "Les deux mots de passe ne correspondent pas.";
        } else {
            update_utilisateur_password($user['id'], $mdp);
            clear_reset_token($user['id']); // le lien ne doit servir qu'une fois
            $succes = "Mot de passe modifié ! Vous pouvez maintenant vous connecter.";
        }
    }
    $titrePage = 'Nouveau mot de passe';
    require_once('view/inc/header.php');
    require_once('view/compte/reinitialiser.php');
    require_once('view/inc/footer.php');
}

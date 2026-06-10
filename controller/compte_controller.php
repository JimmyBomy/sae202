<?php
require_once('model/utilisateur.php');

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

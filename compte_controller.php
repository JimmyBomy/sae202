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

        if (!empty($nom) && !empty($prenom) && !empty($pseudo) && !empty($email) && !empty($password)) {
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

        if (!empty($email) && !empty($password)) {
            $user = get_utilisateur_by_email($email);
            if ($user && password_verify($password, $user['mot_de_passe'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                header('Location: ' . BASE_URL . '/profil');
                exit;
            } else {
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

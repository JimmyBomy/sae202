<?php
/**
 * AUTHENTIFICATION DU BACK-OFFICE — vrai compte administrateur.
 * ------------------------------------------------------------------
 * Le dossier admin/ est déjà protégé au niveau Apache (htpasswd, voir
 * admin/.htaccess). MAIS le cahier des charges exige EN PLUS une
 * « connexion compte administrateur » : « une simple protection du
 * dossier admin ne suffit pas ».
 *
 * Ce module impose donc une 2e barrière applicative : se connecter avec
 * un compte de la base ayant le rôle 'admin' (mot de passe vérifié en
 * bcrypt). Tant que ce n'est pas le cas, rien d'autre ne s'exécute.
 *
 * À inclure en TOUT DÉBUT de admin/index.php (après conf + modèles).
 */

// --- Déconnexion ---
if (isset($_GET['logout'])) {
    unset($_SESSION['admin_id'], $_SESSION['admin_pseudo']);
    header('Location: ' . BASE_URL . '/gestion');
    exit;
}

$err = '';

// --- Traitement du formulaire de connexion ---
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['admin_login'])) {
    if (!csrf_verifie()) {
        $err = "Session expirée, merci de renvoyer le formulaire.";
    } else {
        $email = trim($_POST['email'] ?? '');
        $mdp   = $_POST['mot_de_passe'] ?? '';
        $u = get_utilisateur_by_email($email);
        if ($u && $u['role'] === 'admin' && password_verify($mdp, $u['mot_de_passe'])) {
            session_regenerate_id(true);          // anti fixation de session
            $_SESSION['admin_id']     = $u['id'];
            $_SESSION['admin_pseudo'] = $u['pseudo'];
            header('Location: ' . BASE_URL . '/gestion');
            exit;
        }
        usleep(500000);                            // léger délai anti-bruteforce
        $err = "Identifiants incorrects, ou ce compte n'est pas administrateur.";
    }
}

// --- Non connecté → page de connexion, et on stoppe tout le reste ---
if (empty($_SESSION['admin_id'])) {
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion administrateur — <?= NOM_SITE ?></title>
    <link rel="icon" type="image/png" href="/view/img/favicon.png">
    <style>
        @font-face { font-family: 'VT323'; src: url('/view/fonts/vt323-400.woff2') format('woff2'); font-display: swap; }
        @font-face { font-family: 'Montserrat'; font-weight: 600; src: url('/view/fonts/montserrat-600.woff2') format('woff2'); font-display: swap; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Montserrat', Arial, sans-serif; color: #f5f5f5;
            min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px;
            background-color: #111;
            background-image: linear-gradient(rgba(13,12,9,.80), rgba(13,12,9,.90)), url('/view/img/fond.jpg');
            background-size: cover; background-position: center; background-attachment: fixed;
        }
        .login {
            width: 100%; max-width: 380px; background: rgba(10,10,8,.78);
            border: 2px solid #e6e0c8; border-radius: 6px; padding: 34px 30px 30px; text-align: center;
        }
        .login h1 { font-family: 'VT323', monospace; font-size: 2.8rem; letter-spacing: 2px; line-height: 1; }
        .login .sub { color: #d1b023; font-size: .7rem; letter-spacing: 2px; text-transform: uppercase; margin: 6px 0 22px; }
        .login label { display: block; text-align: left; font-size: .72rem; letter-spacing: 1px; text-transform: uppercase; color: #d1b023; margin: 14px 0 5px; }
        .login input[type=email], .login input[type=password] {
            width: 100%; padding: 11px 12px; border: 1px solid #555047; border-radius: 4px;
            background: #1d1c15; color: #f5f5f5; font-size: .95rem; font-family: inherit;
        }
        .login input:-webkit-autofill { -webkit-box-shadow: 0 0 0 1000px #1d1c15 inset; -webkit-text-fill-color: #f5f5f5; }
        .login button {
            width: 100%; margin-top: 22px; padding: 12px; border: none; border-radius: 4px;
            background: #d1b023; color: #14130d; font-weight: 600; font-size: .95rem; letter-spacing: 1px;
            text-transform: uppercase; cursor: pointer;
        }
        .login button:hover { background: #e3c63a; }
        .erreur { background: rgba(150,30,30,.35); border: 1px solid #c0504d; color: #ffd9d6;
            border-radius: 4px; padding: 9px 11px; font-size: .82rem; margin-bottom: 6px; }
        .retour { display: inline-block; margin-top: 18px; color: #9a9a8a; font-size: .78rem; text-decoration: none; }
        .retour:hover { color: #d1b023; }
    </style>
</head>
<body>
    <form class="login" method="post" action="<?= BASE_URL ?>/gestion">
        <h1><?= NOM_SITE ?></h1>
        <p class="sub">Back-office · Administration</p>
        <?php if ($err): ?><p class="erreur"><?= htmlspecialchars($err) ?></p><?php endif; ?>
        <?= csrf_input() ?>
        <input type="hidden" name="admin_login" value="1">
        <label for="email">Adresse email</label>
        <input type="email" id="email" name="email" required autofocus autocomplete="username">
        <label for="mot_de_passe">Mot de passe</label>
        <input type="password" id="mot_de_passe" name="mot_de_passe" required autocomplete="current-password">
        <button type="submit">Se connecter</button>
        <a class="retour" href="<?= BASE_URL ?>/">← Retour au site</a>
    </form>
</body>
</html>
<?php
    exit;
}

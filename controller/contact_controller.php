<?php
require_once('model/message.php');
require_once('model/mailer.php');

function index() {
    $erreur = '';
    $succes = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Valeurs brutes : stockées via requête préparée (anti-injection) et ré-échappées à l'affichage.
        $nom     = trim($_POST['nom'] ?? '');
        $email   = trim($_POST['email'] ?? '');
        $sujet   = trim($_POST['sujet'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if (!csrf_verifie()) {
            $erreur = t('ct_err_csrf');
        } elseif (empty($nom) || empty($email) || empty($sujet) || empty($message)) {
            $erreur = t('ct_err_empty');
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreur = t('ct_err_email');
        } else {
            // 1) Canal FIABLE : on enregistre le message en base -> lisible dans le back-office (/gestion).
            ajouter_message($nom, $email, $sujet, $message);

            // 2) Notification à l'administrateur (SMTP sécurisé ; Reply-To = visiteur).
            envoyer_mail('terrabordas@gmail.com', 'Nouveau message de contact : ' . $sujet,
                         "Nom: $nom\nEmail: $email\n\nMessage:\n$message", $email);

            // 3) Accusé de réception envoyé au visiteur, dans SA langue (FR/EN/ES).
            envoyer_mail($email, t('ct_ar_subj'), sprintf(t('ct_ar_body'), $nom, $message));

            $succes = t('ct_ok');
        }
    }

    $titrePage = 'Contact';
    require('view/inc/header.php');
    require('view/contact/index.php');
    require('view/inc/footer.php');
}

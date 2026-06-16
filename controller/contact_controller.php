<?php
require_once('model/message.php');

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

            $from = "From: BACKROOMS <no-reply@sae202.mmi25c02.mmi-troyes.fr>\r\n";
            $ctype = "Content-type: text/plain; charset=utf-8\r\n";

            // 2) Notification à l'administrateur (best effort : dépend du serveur mail du VPS).
            @mail('terrabordas@gmail.com', 'Nouveau message de contact : ' . $sujet,
                  "Nom: $nom\nEmail: $email\n\nMessage:\n$message",
                  $from . "Reply-To: " . $email . "\r\n" . $ctype);

            // 3) Accusé de réception envoyé au visiteur, dans SA langue (FR/EN/ES).
            @mail($email, t('ct_ar_subj'), sprintf(t('ct_ar_body'), $nom, $message), $from . $ctype);

            $succes = t('ct_ok');
        }
    }

    $titrePage = 'Contact';
    require('view/inc/header.php');
    require('view/contact/index.php');
    require('view/inc/footer.php');
}

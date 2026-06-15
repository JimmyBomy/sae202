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
            $erreur = 'Session expirée, veuillez renvoyer le formulaire.';
        } elseif (empty($nom) || empty($email) || empty($sujet) || empty($message)) {
            $erreur = 'Tous les champs sont obligatoires.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreur = 'L\'adresse email n\'est pas valide.';
        } else {
            // 1) Canal FIABLE : on enregistre le message en base -> lisible dans le back-office (/gestion).
            ajouter_message($nom, $email, $sujet, $message);

            // 2) Best effort : on tente aussi un mail (souvent indisponible sur le VPS, donc non bloquant).
            $to = 'terrabordas@gmail.com';
            $subject = 'Nouveau message de contact : ' . $sujet;
            $headers  = "From: BACKROOMS <no-reply@sae202.mmi25c02.mmi-troyes.fr>\r\n";
            $headers .= "Reply-To: " . $email . "\r\n";
            $headers .= "Content-type: text/plain; charset=utf-8\r\n";
            $body = "Nom: $nom\nEmail: $email\n\nMessage:\n$message";
            @mail($to, $subject, $body, $headers);

            $succes = 'Votre message a bien été envoyé ! Nous vous répondrons rapidement.';
        }
    }

    $titrePage = 'Contact';
    require('view/inc/header.php');
    require('view/contact/index.php');
    require('view/inc/footer.php');
}

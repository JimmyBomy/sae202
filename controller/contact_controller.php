<?php

function index() {
    $erreur = '';
    $succes = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom = htmlspecialchars(trim($_POST['nom'] ?? ''));
        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $sujet = htmlspecialchars(trim($_POST['sujet'] ?? ''));
        $message = htmlspecialchars(trim($_POST['message'] ?? ''));

        if (empty($nom) || empty($email) || empty($sujet) || empty($message)) {
            $erreur = 'Tous les champs sont obligatoires.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreur = 'L\'adresse email n\'est pas valide.';
        } else {
            // Envoi du mail
            $to = 'prof@mmi-troyes.fr'; // Destinataire administrateur
            $subject = 'Nouveau message de contact : ' . $sujet;
            $headers = "From: " . $email . "\r\n";
            $headers .= "Reply-To: " . $email . "\r\n";
            $headers .= "Content-type: text/plain; charset=utf-8\r\n";
            
            $body = "Nom: $nom\nEmail: $email\n\nMessage:\n$message";

            if (mail($to, $subject, $body, $headers)) {
                $succes = 'Votre message a bien été envoyé à l\'administrateur.';
            } else {
                $erreur = 'Une erreur est survenue lors de l\'envoi du message.';
            }
        }
    }

    $titrePage = 'Contact';
    require('view/inc/header.php');
    require('view/contact/index.php');
    require('view/inc/footer.php');
}

<?php
/**
 * Envoi d'emails via SMTP authentifié + TLS (PHPMailer).
 * --------------------------------------------------------
 * La configuration SMTP (hôte, identifiants…) est lue dans conf/secrets.local.php,
 * fichier NON versionné (les identifiants ne sont jamais dans le dépôt Git).
 *
 * Si le SMTP n'est pas configuré (constantes absentes), la fonction ne fait rien
 * et renvoie false : le site continue de fonctionner, la messagerie du back-office
 * restant le canal fiable. On peut donc déployer sans risque avant d'avoir les accès.
 */

require_once __DIR__ . '/../lib/PHPMailer/Exception.php';
require_once __DIR__ . '/../lib/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../lib/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;

// Le SMTP est-il configuré ? (au moins l'hôte renseigné dans secrets.local.php)
function smtp_actif(): bool {
    return defined('SMTP_HOST') && SMTP_HOST !== '';
}

/**
 * Envoie un email. Renvoie true si parti, false sinon (jamais d'exception remontée).
 * @param string      $to      destinataire
 * @param string      $sujet   objet
 * @param string      $corps   corps (texte brut)
 * @param string|null $replyTo adresse de réponse éventuelle
 */
function envoyer_mail(string $to, string $sujet, string $corps, ?string $replyTo = null): bool {
    if (!smtp_actif()) {
        return false; // SMTP non configuré : on n'envoie rien (pas d'erreur).
    }
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->Port       = defined('SMTP_PORT') ? (int) SMTP_PORT : 587;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;
        // Chiffrement : STARTTLS (587) par défaut, ou SSL (465) si SMTP_SECURE = 'ssl'.
        $mail->SMTPSecure = (defined('SMTP_SECURE') && SMTP_SECURE === 'ssl')
            ? PHPMailer::ENCRYPTION_SMTPS
            : PHPMailer::ENCRYPTION_STARTTLS;
        $mail->CharSet    = 'UTF-8';

        $from     = defined('SMTP_FROM') && SMTP_FROM !== '' ? SMTP_FROM : SMTP_USER;
        $fromName = defined('SMTP_FROM_NAME') ? SMTP_FROM_NAME : 'BACKROOMS';
        $mail->setFrom($from, $fromName);
        $mail->addAddress($to);
        if ($replyTo) {
            $mail->addReplyTo($replyTo);
        }
        $mail->Subject = $sujet;
        $mail->Body    = $corps;

        $mail->send();
        return true;
    } catch (\Throwable $e) {
        // En production on ne montre rien à l'utilisateur ; on journalise pour le débogage.
        error_log('Envoi mail SMTP échoué : ' . $e->getMessage());
        return false;
    }
}

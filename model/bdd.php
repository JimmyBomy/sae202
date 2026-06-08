<?php
/**
 * Connexion à la base de données via PDO.
 * On utilise PDO + requêtes préparées partout (protection contre les injections SQL).
 */

function getBdd(): PDO
{
    // On garde une seule connexion pour toute la requête (pattern singleton simple).
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,   // les erreurs lèvent une exception
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,         // résultats en tableaux associatifs
        PDO::ATTR_EMULATE_PREPARES   => false,                   // vraies requêtes préparées
    ];

    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        // En production on ne montre PAS l'erreur SQL (sécurité), on log et on affiche un message neutre.
        error_log('Erreur BDD : ' . $e->getMessage());
        http_response_code(500);
        exit('Service momentanément indisponible.');
    }

    return $pdo;
}

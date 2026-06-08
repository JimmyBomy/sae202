<?php
require_once('model/commentaire.php');

function index() {
    // On récupère les avis APPROUVÉS pour les afficher publiquement.
    $avis = get_commentaires_approuves();

    $titrePage = 'Le Concept';
    require_once('view/inc/header.php');
    require_once('view/concept/index.php');
    require_once('view/inc/footer.php');
}

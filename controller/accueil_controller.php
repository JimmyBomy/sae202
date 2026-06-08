<?php
function index() {
    $titrePage = 'Accueil';
    // IMPORTANT : défini AVANT le header, car c'est lui qui écrit <body class="...">
    // (sinon le fond liminal de l'accueil ne s'applique pas).
    $page_class = 'page-accueil';
    require_once('view/inc/header.php');
    require_once('view/accueil/index.php');
    require_once('view/inc/footer.php');
}
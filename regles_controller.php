<?php
// Contrôleur de la page "Règles du jeu".
function index() {
    $titrePage = 'Règles du jeu';
    require_once('view/inc/header.php');
    require_once('view/regles/index.php');
    require_once('view/inc/footer.php');
}

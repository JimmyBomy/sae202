<?php
// Page "Infos pratiques" : localisation, contact, tarifs, interdictions.
function index() {
    $titrePage = 'Infos pratiques';
    // Réutilise le fond liminal sombre (défini avant le header).
    $page_class = 'page-concept';
    require_once('view/inc/header.php');
    require_once('view/infospratiques/index.php');
    require_once('view/inc/footer.php');
}

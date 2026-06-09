<?php
function erreur_404() {
    $titrePage = 'Erreur 404';
    require_once('view/inc/header.php');
    echo '<main class="container text-center" style="padding: 100px 20px;">';
    echo '<h1>Erreur 404 - Page non trouvée</h1>';
    echo '<p>Désolé, la page que vous recherchez n\'existe pas.</p>';
    echo '<p><a href="'.BASE_URL.'/" class="btn">Retour à l\'accueil</a></p>';
    echo '</main>';
    require_once('view/inc/footer.php');
}
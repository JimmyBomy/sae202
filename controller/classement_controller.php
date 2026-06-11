<?php
// Classement public des équipes (exploite la table scores).
require_once('model/score.php');

function index() {
    $classement = get_classement();

    $titrePage = 'Classement des équipes';
    $page_class = 'page-concept';
    require_once('view/inc/header.php');
    require_once('view/classement/index.php');
    require_once('view/inc/footer.php');
}

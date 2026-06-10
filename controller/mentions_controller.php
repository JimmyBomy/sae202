<?php
function index() {
    $titrePage = 'Mentions légales';
    $page_class = 'page-concept'; // fond liminal comme les autres pages
    require_once('view/inc/header.php');
    require_once('view/mentions/index.php');
    require_once('view/inc/footer.php');
}

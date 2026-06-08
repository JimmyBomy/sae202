<?php
/* En-tête HTML commun à toutes les pages publiques */
// Segment de contrôleur courant (pour surligner le menu actif).
$__seg = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'))[0] ?? '';
$actif = fn($c) => ($__seg === $c || ($c === '' && $__seg === '')) ? 'class="active"' : '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($titrePage ?? NOM_SITE) ?> — <?= NOM_SITE ?></title>
  <meta name="description" content="<?= htmlspecialchars($metaDesc ?? SLOGAN) ?>">
  <link rel="icon" type="image/png" href="<?= BASE_URL ?>/view/img/favicon.png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Oswald:wght@400;500&family=VT323&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= BASE_URL ?>/view/css/style.css?v=4">
</head>
<body class="<?= isset($page_class) ? $page_class : '' ?>">
  <header class="site-header">
    <div class="header-container">
      <div class="logo">
        <a href="<?= BASE_URL ?>/">
          <img src="<?= BASE_URL ?>/view/img/logo.png" alt="BACKROOMS — Escape game nocturne" class="logo-img">
        </a>
      </div>
      <nav class="main-nav">
        <ul>
          <li><a href="<?= BASE_URL ?>/" <?= $actif('') ?>>ACCUEIL</a></li>
          <li><a href="<?= BASE_URL ?>/concept" <?= $actif('concept') ?>>À PROPOS</a></li>
          <li><a href="<?= BASE_URL ?>/infos" <?= $actif('infos') ?>>LES SALLES</a></li>
          <li><a href="<?= BASE_URL ?>/regles" <?= $actif('regles') ?>>RÈGLES</a></li>
          <li><a href="<?= BASE_URL ?>/contact" <?= $actif('contact') ?>>CONTACT</a></li>
        </ul>
      </nav>
      <div class="header-actions">
        <?php if (isset($_SESSION['user_id'])): ?>
          <a href="<?= BASE_URL ?>/profil" class="btn-link">MON ESPACE</a>
          <a href="<?= BASE_URL ?>/compte/deconnexion" class="btn-link">DÉCONNEXION</a>
        <?php else: ?>
          <a href="<?= BASE_URL ?>/compte/connexion" class="btn-link">CONNEXION</a>
        <?php endif; ?>
        <a href="<?= BASE_URL ?>/reservation" class="btn btn-outline">RÉSERVER</a>
      </div>
    </div>
  </header>

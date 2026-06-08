<?php /* En-tête HTML commun à toutes les pages publiques */ ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($titrePage ?? NOM_SITE) ?> — <?= NOM_SITE ?></title>
  <meta name="description" content="<?= htmlspecialchars($metaDesc ?? SLOGAN) ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Oswald:wght@400;500&family=VT323&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= BASE_URL ?>/view/css/style.css?v=2">
</head>
<body class="<?= isset($page_class) ? $page_class : '' ?>">
  <header class="site-header">
    <div class="header-container">
      <div class="logo">
        <a href="<?= BASE_URL ?>/">
          <!-- Le logo sera fourni par l'utilisateur plus tard -->
          <img src="<?= BASE_URL ?>/view/css/logo_placeholder.png" alt="BACKROOMS" style="height:40px; display:none;" id="real-logo">
          <span id="text-logo" style="font-family: 'VT323', monospace; font-size: 32px; color: var(--primary-color, #e0ad0f); letter-spacing: 2px;">BACKROOMS</span>
        </a>
      </div>
      <nav class="main-nav">
        <ul>
          <li><a href="<?= BASE_URL ?>/" <?= (!isset($_GET['action']) || $_GET['action'] == 'accueil') ? 'class="active"' : '' ?>>ACCUEIL</a></li>
          <li><a href="<?= BASE_URL ?>/concept" <?= (isset($_GET['action']) && $_GET['action'] == 'concept') ? 'class="active"' : '' ?>>À PROPOS</a></li>
          <li><a href="<?= BASE_URL ?>/infos" <?= (isset($_GET['action']) && $_GET['action'] == 'infos') ? 'class="active"' : '' ?>>LES SALLES</a></li>
          <li><a href="<?= BASE_URL ?>/regles" <?= (isset($_GET['action']) && $_GET['action'] == 'regles') ? 'class="active"' : '' ?>>RÈGLES</a></li>
          <li><a href="<?= BASE_URL ?>/contact" <?= (isset($_GET['action']) && $_GET['action'] == 'contact') ? 'class="active"' : '' ?>>CONTACT</a></li>
        </ul>
      </nav>
      <div class="header-actions">
        <a href="<?= BASE_URL ?>/compte/inscription" class="btn btn-outline">RÉSERVER</a>
      </div>
    </div>
  </header>
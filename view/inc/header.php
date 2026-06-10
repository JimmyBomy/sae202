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
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Montserrat:wght@500;600;700&family=VT323&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= BASE_URL ?>/view/css/style.css?v=22">
</head>
<body class="<?= isset($page_class) ? $page_class : '' ?>">
  <header class="site-header">
    <div class="header-container">
      <div class="logo">
        <a href="<?= BASE_URL ?>/">
          <img src="<?= BASE_URL ?>/view/img/logo.png?v=2" alt="BACKROOMS — Escape game nocturne" class="logo-img">
        </a>
      </div>

      <!-- Menu hamburger (CSS pur, sans JS) pour le mobile -->
      <input type="checkbox" id="menu-toggle" class="menu-toggle">
      <label for="menu-toggle" class="burger" aria-label="Ouvrir le menu"><span></span><span></span><span></span></label>

      <nav class="main-nav">
        <ul>
          <li><a href="<?= BASE_URL ?>/" <?= $actif('') ?>>ACCUEIL</a></li>
          <li><a href="<?= BASE_URL ?>/concept" <?= $actif('concept') ?>>À PROPOS</a></li>
          <li><a href="<?= BASE_URL ?>/infos" <?= $actif('infos') ?>>LES SALLES</a></li>
          <li><a href="<?= BASE_URL ?>/infospratiques" <?= $actif('infospratiques') ?>>INFOS PRATIQUES</a></li>
          <li><a href="<?= BASE_URL ?>/regles" <?= $actif('regles') ?>>RÈGLES</a></li>
          <li><a href="<?= BASE_URL ?>/contact" <?= $actif('contact') ?>>CONTACT</a></li>
        </ul>
      </nav>

      <div class="header-actions">
        <?php if (isset($_SESSION['user_id'])): ?>
          <!-- Connecté : accès à l'espace privé (profil, équipe, déconnexion) -->
          <a href="<?= BASE_URL ?>/profil" class="btn btn-outline">MON ESPACE</a>
        <?php else: ?>
          <!-- Un seul bouton : RÉSERVER mène à l'inscription / connexion -->
          <a href="<?= BASE_URL ?>/reservation" class="btn btn-outline">RÉSERVER</a>
        <?php endif; ?>
      </div>
    </div>
  </header>

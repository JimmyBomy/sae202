<?php
/* En-tête HTML commun à toutes les pages publiques */
// Segment de contrôleur courant (pour surligner le menu actif).
$__seg = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'))[0] ?? '';
$actif = fn($c) => ($__seg === $c || ($c === '' && $__seg === '')) ? 'class="active"' : '';
?>
<!DOCTYPE html>
<html lang="<?= lang_courante() ?>">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($titrePage ?? NOM_SITE) ?> — <?= NOM_SITE ?></title>
  <meta name="description" content="<?= htmlspecialchars($metaDesc ?? SLOGAN) ?>">
  <!-- Open Graph : aperçu riche quand le lien est partagé (réseaux sociaux, messageries) -->
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="<?= NOM_SITE ?>">
  <meta property="og:title" content="<?= htmlspecialchars($titrePage ?? NOM_SITE) ?> — <?= NOM_SITE ?>">
  <meta property="og:description" content="<?= htmlspecialchars($metaDesc ?? SLOGAN) ?>">
  <meta property="og:image" content="https://<?= htmlspecialchars($_SERVER['HTTP_HOST'] ?? 'sae202.mmi25c02.mmi-troyes.fr') ?><?= BASE_URL ?>/view/img/og.jpg">
  <meta property="og:url" content="https://<?= htmlspecialchars($_SERVER['HTTP_HOST'] ?? 'sae202.mmi25c02.mmi-troyes.fr') ?><?= htmlspecialchars(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH)) ?>">
  <meta name="twitter:card" content="summary_large_image">
  <link rel="icon" type="image/png" href="<?= BASE_URL ?>/view/img/favicon.png">
  <!-- Polices auto-hébergées (view/fonts/) : aucune requête externe (éco + RGPD) -->
  <link rel="stylesheet" href="<?= BASE_URL ?>/view/css/style.css?v=46">
</head>
<body class="<?= isset($page_class) ? $page_class : '' ?>">
  <a href="#contenu" class="skip-link">Aller au contenu</a>
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
          <li><a href="<?= BASE_URL ?>/" <?= $actif('') ?>><?= t('nav_accueil') ?></a></li>
          <li><a href="<?= BASE_URL ?>/concept" <?= $actif('concept') ?>><?= t('nav_concept') ?></a></li>
          <li><a href="<?= BASE_URL ?>/infos" <?= $actif('infos') ?>><?= t('nav_salles') ?></a></li>
          <li><a href="<?= BASE_URL ?>/infospratiques" <?= $actif('infospratiques') ?>><?= t('nav_infos') ?></a></li>
          <li><a href="<?= BASE_URL ?>/regles" <?= $actif('regles') ?>><?= t('nav_regles') ?></a></li>
          <li><a href="<?= BASE_URL ?>/contact" <?= $actif('contact') ?>><?= t('nav_contact') ?></a></li>
        </ul>
      </nav>

      <div class="header-actions">
        <!-- Sélecteur de langue (FR / EN / ES) -->
        <div class="lang-switch" aria-label="Langue / Language">
          <a href="?lang=fr"<?= lang_courante()==='fr' ? ' class="on"' : '' ?>>FR</a>
          <a href="?lang=en"<?= lang_courante()==='en' ? ' class="on"' : '' ?>>EN</a>
          <a href="?lang=es"<?= lang_courante()==='es' ? ' class="on"' : '' ?>>ES</a>
        </div>
        <?php if (isset($_SESSION['user_id'])): ?>
          <a href="<?= BASE_URL ?>/profil" class="btn btn-outline"><?= t('btn_espace') ?></a>
        <?php else: ?>
          <a href="<?= BASE_URL ?>/reservation" class="btn btn-outline"><?= t('btn_reserver') ?></a>
        <?php endif; ?>
      </div>
    </div>
  </header>
  <span id="contenu" tabindex="-1"></span>

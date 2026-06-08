<?php /* En-tête HTML commun à toutes les pages publiques */ ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($titrePage ?? NOM_SITE) ?> — <?= NOM_SITE ?></title>
  <meta name="description" content="<?= htmlspecialchars($metaDesc ?? SLOGAN) ?>">
  <link rel="stylesheet" href="<?= BASE_URL ?>/view/css/style.css?v=1">
</head>
<body>
  <header>
    <div class="navbar">
      <div class="logo">
        <a href="<?= BASE_URL ?>/"><?= NOM_SITE ?></a>
      </div>
      <nav>
        <ul>
          <li><a href="<?= BASE_URL ?>/">Accueil</a></li>
          <li><a href="<?= BASE_URL ?>/concept">Le Concept</a></li>
          <li><a href="<?= BASE_URL ?>/infos">Infos pratiques</a></li>
          <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="<?= BASE_URL ?>/profil">Mon Profil</a></li>
            <li><a href="<?= BASE_URL ?>/compte/deconnexion">Déconnexion</a></li>
          <?php else: ?>
            <li><a href="<?= BASE_URL ?>/compte/inscription">S'inscrire</a></li>
            <li><a href="<?= BASE_URL ?>/compte/connexion">Se connecter</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
  </header>

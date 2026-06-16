<main class="container form-page">
  <h1><?= t('lg_fg_title') ?></h1>
  <p><?= t('lg_fg_txt') ?></p>
  <?php if (!empty($erreur)): ?><div class="alert alert-error"><?= htmlspecialchars($erreur) ?></div><?php endif; ?>
  <?php if (!empty($succes)): ?><div class="alert alert-success"><?= htmlspecialchars($succes) ?></div><?php endif; ?>
  <form action="<?= BASE_URL ?>/compte/mdp_oublie" method="post">
    <?= csrf_input() ?>
    <div class="form-group">
      <label for="email"><?= t('lg_email') ?></label>
      <input type="email" name="email" id="email" required>
    </div>
    <button type="submit" class="btn btn-primary"><?= t('lg_fg_btn') ?></button>
  </form>
  <p class="form-switch"><a href="<?= BASE_URL ?>/compte/connexion"><?= t('lg_back_login') ?></a></p>
</main>

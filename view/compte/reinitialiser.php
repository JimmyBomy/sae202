<main class="container form-page">
  <h1><?= t('lg_rs_title') ?></h1>
  <?php if (!empty($erreur)): ?>
    <div class="alert alert-error"><?= htmlspecialchars($erreur) ?></div>
    <p class="form-switch"><a href="<?= BASE_URL ?>/compte/mdp_oublie"><?= t('lg_redo') ?></a></p>
  <?php elseif (!empty($succes)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($succes) ?></div>
    <p class="form-switch"><a href="<?= BASE_URL ?>/compte/connexion" class="btn btn-primary"><?= t('lg_rs_login') ?></a></p>
  <?php else: ?>
    <form action="" method="post">
      <?= csrf_input() ?>
      <div class="form-group">
        <label for="mot_de_passe"><?= t('lg_rs_new') ?></label>
        <input type="password" name="mot_de_passe" id="mot_de_passe" minlength="6" required>
      </div>
      <div class="form-group">
        <label for="confirmation"><?= t('lg_rs_confirm') ?></label>
        <input type="password" name="confirmation" id="confirmation" minlength="6" required>
      </div>
      <button type="submit" class="btn btn-primary"><?= t('lg_rs_btn') ?></button>
    </form>
  <?php endif; ?>
</main>

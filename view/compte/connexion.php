<main class="container form-page">
  <h1><?= t('lg_co_title') ?></h1>
  <?php if (!empty($erreur)): ?>
    <div class="alert alert-error"><?= htmlspecialchars($erreur) ?></div>
  <?php endif; ?>
  <form action="<?= BASE_URL ?>/compte/connexion" method="post">
    <?= csrf_input() ?>
    <div class="form-group">
      <label for="email"><?= t('lg_email') ?></label>
      <input type="email" name="email" id="email" required>
    </div>
    <div class="form-group">
      <label for="mot_de_passe"><?= t('lg_pass') ?></label>
      <input type="password" name="mot_de_passe" id="mot_de_passe" required>
    </div>
    <button type="submit" class="btn btn-primary"><?= t('lg_co_btn') ?></button>
  </form>
  <p class="form-switch"><?= t('lg_no_acct') ?>
    <a href="<?= BASE_URL ?>/compte/inscription"><?= t('lg_create') ?></a>
    · <a href="<?= BASE_URL ?>/compte/mdp_oublie"><?= t('lg_forgot') ?></a>
  </p>
</main>

<main class="container form-page">
  <h1><?= t('lg_in_title') ?></h1>
  <?php if (!empty($erreur)): ?>
    <div class="alert alert-error"><?= htmlspecialchars($erreur) ?></div>
  <?php endif; ?>
  <form action="<?= BASE_URL ?>/compte/inscription" method="post">
    <?= csrf_input() ?>
    <div class="form-group">
      <label for="nom"><?= t('lg_nom') ?></label>
      <input type="text" name="nom" id="nom" required>
    </div>
    <div class="form-group">
      <label for="prenom"><?= t('lg_prenom') ?></label>
      <input type="text" name="prenom" id="prenom" required>
    </div>
    <div class="form-group">
      <label for="pseudo"><?= t('lg_pseudo') ?></label>
      <input type="text" name="pseudo" id="pseudo" required>
    </div>
    <div class="form-group">
      <label for="email"><?= t('lg_email_r') ?></label>
      <input type="email" name="email" id="email" required>
    </div>
    <div class="form-group">
      <label for="telephone"><?= t('lg_tel') ?></label>
      <input type="text" name="telephone" id="telephone">
    </div>
    <div class="form-group">
      <label for="mot_de_passe"><?= t('lg_pass_r') ?></label>
      <input type="password" name="mot_de_passe" id="mot_de_passe" required>
    </div>
    <button type="submit" class="btn btn-primary"><?= t('lg_in_btn') ?></button>
  </form>
  <p class="form-switch"><?= t('lg_already') ?>
    <a href="<?= BASE_URL ?>/compte/connexion"><?= t('lg_login_link') ?></a>
  </p>
</main>

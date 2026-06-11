<main class="container form-page" style="padding-top: 50px;">
  <h1>Nouveau mot de passe</h1>
  <?php if (!empty($erreur)): ?>
    <div class="alert alert-error"><?= htmlspecialchars($erreur) ?></div>
    <p class="form-switch"><a href="<?= BASE_URL ?>/compte/mdp_oublie">Refaire une demande</a></p>
  <?php elseif (!empty($succes)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($succes) ?></div>
    <p class="form-switch"><a href="<?= BASE_URL ?>/compte/connexion" class="btn btn-primary">Se connecter</a></p>
  <?php else: ?>
    <form action="" method="post">
      <?= csrf_input() ?>
      <div class="form-group">
        <label for="mot_de_passe">Nouveau mot de passe (6 caractères min.)</label>
        <input type="password" name="mot_de_passe" id="mot_de_passe" minlength="6" required>
      </div>
      <div class="form-group">
        <label for="confirmation">Confirmez le mot de passe</label>
        <input type="password" name="confirmation" id="confirmation" minlength="6" required>
      </div>
      <button type="submit" class="btn btn-primary">Changer le mot de passe</button>
    </form>
  <?php endif; ?>
</main>

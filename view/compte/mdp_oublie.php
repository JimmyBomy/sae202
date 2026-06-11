<main class="container form-page" style="padding-top: 50px;">
  <h1>Mot de passe oublié</h1>
  <p>Saisissez votre adresse email : nous vous enverrons un lien pour choisir un nouveau mot de passe.</p>
  <?php if (!empty($erreur)): ?><div class="alert alert-error"><?= htmlspecialchars($erreur) ?></div><?php endif; ?>
  <?php if (!empty($succes)): ?><div class="alert alert-success"><?= htmlspecialchars($succes) ?></div><?php endif; ?>
  <form action="<?= BASE_URL ?>/compte/mdp_oublie" method="post">
    <?= csrf_input() ?>
    <div class="form-group">
      <label for="email">Email</label>
      <input type="email" name="email" id="email" required>
    </div>
    <button type="submit" class="btn btn-primary">Envoyer le lien</button>
  </form>
  <p class="form-switch"><a href="<?= BASE_URL ?>/compte/connexion">‹ Retour à la connexion</a></p>
</main>

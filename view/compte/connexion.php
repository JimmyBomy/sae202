<main class="container">
  <h1>Connexion</h1>
  <?php if (!empty($erreur)): ?>
    <div class="alert alert-error"><?= htmlspecialchars($erreur) ?></div>
  <?php endif; ?>
  <form action="<?= BASE_URL ?>/compte/connexion" method="post">
    <div class="form-group">
      <label for="email">Email</label>
      <input type="email" name="email" id="email" required>
    </div>
    <div class="form-group">
      <label for="mot_de_passe">Mot de passe</label>
      <input type="password" name="mot_de_passe" id="mot_de_passe" required>
    </div>
    <button type="submit" class="btn">Se connecter</button>
  </form>
</main>
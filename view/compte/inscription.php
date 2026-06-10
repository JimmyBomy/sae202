<main class="container form-page" style="padding-top: 50px;">
  <h1>Inscription</h1>
  <?php if (!empty($erreur)): ?>
    <div class="alert alert-error"><?= htmlspecialchars($erreur) ?></div>
  <?php endif; ?>
  <form action="<?= BASE_URL ?>/compte/inscription" method="post">
    <?= csrf_input() ?>
    <div class="form-group">
      <label for="nom">Nom *</label>
      <input type="text" name="nom" id="nom" required>
    </div>
    <div class="form-group">
      <label for="prenom">Prénom *</label>
      <input type="text" name="prenom" id="prenom" required>
    </div>
    <div class="form-group">
      <label for="pseudo">Pseudo *</label>
      <input type="text" name="pseudo" id="pseudo" required>
    </div>
    <div class="form-group">
      <label for="email">Email *</label>
      <input type="email" name="email" id="email" required>
    </div>
    <div class="form-group">
      <label for="telephone">Téléphone</label>
      <input type="text" name="telephone" id="telephone">
    </div>
    <div class="form-group">
      <label for="mot_de_passe">Mot de passe *</label>
      <input type="password" name="mot_de_passe" id="mot_de_passe" required>
    </div>
    <button type="submit" class="btn btn-primary">S'inscrire</button>
  </form>
  <p class="form-switch">Déjà inscrit&nbsp;?
    <a href="<?= BASE_URL ?>/compte/connexion">Connectez-vous</a>
  </p>
</main>
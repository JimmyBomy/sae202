<main class="container resa-page">
  <section class="concept-section">
    <h1 class="sec-title">NOUS CONTACTER</h1>
    <p class="sec-sub">Une question sur l'expérience ? Écrivez-nous, on vous répond rapidement.</p>

    <?php if (!empty($erreur)): ?>
      <div class="alert alert-error"><?= htmlspecialchars($erreur) ?></div>
    <?php endif; ?>
    <?php if (!empty($succes)): ?>
      <div class="alert alert-success"><?= htmlspecialchars($succes) ?></div>
    <?php endif; ?>

    <div class="contact-grid">
      <form class="contact-form" action="" method="post">
        <?= csrf_input() ?>
        <div class="form-group">
          <label for="nom">Votre nom *</label>
          <input type="text" id="nom" name="nom" required value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label for="email">Votre email *</label>
          <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label for="sujet">Sujet *</label>
          <input type="text" id="sujet" name="sujet" required value="<?= htmlspecialchars($_POST['sujet'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label for="message">Message *</label>
          <textarea id="message" name="message" rows="6" required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Envoyer le message</button>
      </form>

      <aside class="contact-infos">
        <div class="info-field"><span class="info-label">Adresse</span><p>Cours Émile Zola<br>69100 Villeurbanne</p></div>
        <div class="info-field"><span class="info-label">Email</span><p>backroomsescapegame@gmail.com</p></div>
        <div class="info-field"><span class="info-label">Téléphone</span><p>07 07 07 07 07</p></div>
        <div class="info-field"><span class="info-label">Horaires</span><p>Vendredi soir, samedi soir, jours fériés et vacances scolaires (sauf le lundi)</p></div>
        <div class="info-field"><span class="info-label">Réseaux sociaux</span><p>@backroomsescapegame (Instagram)<br>Backrooms Escape Game (Facebook)</p></div>
      </aside>
    </div>
  </section>
</main>

<main class="container resa-page">
  <section class="concept-section">
    <h1 class="sec-title"><?= t('ct_title') ?></h1>
    <p class="sec-sub"><?= t('ct_sub') ?></p>

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
          <label for="nom"><?= t('ct_nom') ?></label>
          <input type="text" id="nom" name="nom" required value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label for="email"><?= t('ct_email') ?></label>
          <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label for="sujet"><?= t('ct_sujet') ?></label>
          <input type="text" id="sujet" name="sujet" required value="<?= htmlspecialchars($_POST['sujet'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label for="message"><?= t('ct_msg') ?></label>
          <textarea id="message" name="message" rows="6" required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary"><?= t('ct_send') ?></button>
      </form>

      <aside class="contact-infos">
        <div class="info-field"><span class="info-label"><?= t('ct_iadr') ?></span><p>Cours Émile Zola<br>69100 Villeurbanne</p></div>
        <div class="info-field"><span class="info-label"><?= t('ct_email') ?></span><p>backroomsescapegame@gmail.com</p></div>
        <div class="info-field"><span class="info-label"><?= t('ct_itel') ?></span><p>07 07 07 07 07</p></div>
        <div class="info-field"><span class="info-label"><?= t('ct_ihor') ?></span><p><?= t('foot_jours') ?></p></div>
        <div class="info-field"><span class="info-label"><?= t('ct_ires') ?></span><p>@backroomsescapegame (Instagram)<br>Backrooms Escape Game (Facebook)</p></div>
      </aside>
    </div>
  </section>
</main>

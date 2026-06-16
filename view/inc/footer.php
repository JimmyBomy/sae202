  <footer class="site-footer">
    <div class="footer-grid">

      <div class="footer-col footer-brand">
        <img src="<?= BASE_URL ?>/view/img/logo.png?v=2" alt="BACKROOMS" class="footer-logo">
        <p><?= t('foot_tagline') ?><br><span class="citation"><?= t('foot_quote') ?></span></p>
      </div>

      <div class="footer-col">
        <h4><?= t('foot_h_nav') ?></h4>
        <ul>
          <li><a href="<?= BASE_URL ?>/"><?= t('nav_accueil') ?></a></li>
          <li><a href="<?= BASE_URL ?>/concept"><?= t('nav_concept') ?></a></li>
          <li><a href="<?= BASE_URL ?>/infos"><?= t('nav_salles') ?></a></li>
          <li><a href="<?= BASE_URL ?>/regles"><?= t('nav_regles') ?></a></li>
          <li><a href="<?= BASE_URL ?>/classement"><?= t('nav_classement') ?></a></li>
          <li><a href="<?= BASE_URL ?>/contact"><?= t('nav_contact') ?></a></li>
          <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="<?= BASE_URL ?>/profil"><?= t('btn_espace') ?></a></li>
          <?php else: ?>
            <li><a href="<?= BASE_URL ?>/compte/connexion"><?= t('btn_connexion') ?></a></li>
          <?php endif; ?>
        </ul>
      </div>

      <div class="footer-col">
        <h4><?= t('foot_h_infos') ?></h4>
        <ul>
          <li>Cours Émile Zola, 69100 Villeurbanne</li>
          <li><?= t('foot_jours') ?></li>
          <li><a href="<?= BASE_URL ?>/contact"><?= t('foot_contact') ?></a></li>
        </ul>
      </div>

      <div class="footer-col">
        <h4><?= t('foot_h_pret') ?></h4>
        <p class="foot-cta-txt"><?= t('foot_resa_txt') ?></p>
        <a href="<?= BASE_URL ?>/reservation" class="btn btn-primary"><?= t('btn_reserver') ?></a>
      </div>

    </div>

    <div class="footer-bottom">
      <p>
        &copy; <?= date('Y') ?> <?= NOM_SITE ?> — <?= t('foot_rights') ?>
        · <a href="<?= BASE_URL ?>/mentions">Mentions légales</a>
        · <?= t('foot_by') ?> <strong>Lumina Studio</strong>
      </p>
    </div>
  </footer>
</body>
</html>

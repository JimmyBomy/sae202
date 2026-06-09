  <footer class="site-footer">
    <div class="footer-grid">

      <div class="footer-col footer-brand">
        <img src="<?= BASE_URL ?>/view/img/logo.png?v=2" alt="BACKROOMS" class="footer-logo">
        <p>Escape game nocturne immersif.<br>« Vous n'auriez jamais dû trouver cet endroit. »</p>
      </div>

      <div class="footer-col">
        <h4>Navigation</h4>
        <ul>
          <li><a href="<?= BASE_URL ?>/">Accueil</a></li>
          <li><a href="<?= BASE_URL ?>/concept">À propos</a></li>
          <li><a href="<?= BASE_URL ?>/infos">Les salles</a></li>
          <li><a href="<?= BASE_URL ?>/regles">Règles</a></li>
          <li><a href="<?= BASE_URL ?>/contact">Contact</a></li>
        </ul>
      </div>

      <div class="footer-col">
        <h4>Infos pratiques</h4>
        <ul>
          <li>12 rue des Liminaux, 10000 Troyes</li>
          <li>Jeudi → dimanche, sessions nocturnes</li>
          <li><a href="<?= BASE_URL ?>/contact">Nous contacter</a></li>
        </ul>
      </div>

      <div class="footer-col">
        <h4>Prêt·e&nbsp;?</h4>
        <p style="margin-bottom:14px; color:#8f8f88;">Réservez votre nuit dans les Backrooms.</p>
        <a href="<?= BASE_URL ?>/reservation" class="btn btn-primary">RÉSERVER</a>
      </div>

    </div>

    <div class="footer-bottom">
      <p>
        &copy; <?= date('Y') ?> <?= NOM_SITE ?> — Tous droits réservés.
        · <a href="<?= BASE_URL ?>/mentions">Mentions légales</a>
        · Réalisé par <strong>Lumina Studio</strong>
      </p>
    </div>
  </footer>
</body>
</html>

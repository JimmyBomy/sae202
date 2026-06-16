<main class="container resa-page">
  <section class="concept-section">
    <h1 class="sec-title"><?= t('rg_title') ?></h1>
    <p class="sec-sub"><?= t('rg_sub') ?></p>

    <div class="regle-alerte" role="note">
      <span class="regle-alerte-ic" aria-hidden="true">&#9888;</span>
      <p><?= t('rg_alerte') ?></p>
    </div>

    <div class="regles-grid">
      <article class="regle-card">
        <h2><span class="regle-num">1</span> <?= t('rg_c1t') ?></h2>
        <ul>
          <li><?= t('rg_c1a') ?></li>
          <li><?= t('rg_c1b') ?></li>
          <li><?= t('rg_c1c') ?></li>
          <li><?= t('rg_c1d') ?></li>
        </ul>
      </article>

      <article class="regle-card">
        <h2><span class="regle-num">2</span> <?= t('rg_c2t') ?></h2>
        <ul>
          <li><?= t('rg_c2a') ?></li>
          <li><?= t('rg_c2b') ?></li>
          <li><?= t('rg_c2c') ?></li>
          <li><?= t('rg_c2d') ?></li>
        </ul>
      </article>

      <article class="regle-card">
        <h2><span class="regle-num">3</span> <?= t('rg_c3t') ?></h2>
        <ul>
          <li><?= t('rg_c3a') ?></li>
          <li><?= t('rg_c3b') ?></li>
          <li><?= t('rg_c3c') ?></li>
          <li><?= t('rg_c3d') ?></li>
        </ul>
      </article>

      <article class="regle-card">
        <h2><span class="regle-num">4</span> <?= t('rg_c4t') ?></h2>
        <ul>
          <li><?= t('rg_c4a') ?></li>
          <li><?= t('rg_c4b') ?></li>
          <li><?= t('rg_c4c') ?></li>
          <li><?= t('rg_c4d') ?></li>
        </ul>
      </article>
    </div>

    <div class="cta-bloc">
      <p><?= t('rg_cta') ?></p>
      <a href="<?= BASE_URL ?>/reservation" class="btn btn-primary"><?= t('home_btn_reserver') ?></a>
    </div>
  </section>
</main>

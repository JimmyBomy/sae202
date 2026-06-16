<main class="container resa-page">
  <section class="concept-section">
    <h1 class="sec-title"><?= t('nav_salles') ?></h1>
    <p class="sec-sub"><?= t('sl_sub') ?></p>

    <div class="salles-grid">
      <article class="salle-card2">
        <div class="salle-thumb2 st-facile">
          <span class="salle-tag"><?= t('s1_tag') ?></span>
        </div>
        <div class="salle-body">
          <h2><?= t('s1_nom') ?></h2>
          <p><?= t('s1_desc') ?></p>
          <ul class="salle-points">
            <li><?= t('s1_a') ?></li>
            <li><?= t('s1_b') ?></li>
            <li><?= t('s1_c') ?></li>
            <li><?= t('s1_d') ?></li>
          </ul>
          <p class="salle-prix"><?= t('s1_prix') ?></p>
        </div>
      </article>

      <article class="salle-card2">
        <div class="salle-thumb2 st-standard">
          <span class="salle-tag"><?= t('s2_tag') ?></span>
        </div>
        <div class="salle-body">
          <h2><?= t('s2_nom') ?></h2>
          <p><?= t('s2_desc') ?></p>
          <ul class="salle-points">
            <li><?= t('s2_a') ?></li>
            <li><?= t('s2_b') ?></li>
            <li><?= t('s2_c') ?></li>
            <li><?= t('s2_d') ?></li>
          </ul>
          <p class="salle-prix"><?= t('s2_prix') ?></p>
        </div>
      </article>

      <article class="salle-card2">
        <div class="salle-thumb2 st-hardcore">
          <span class="salle-tag salle-tag--hard"><?= t('s3_tag') ?></span>
        </div>
        <div class="salle-body">
          <h2><?= t('s3_nom') ?></h2>
          <p><?= t('s3_desc') ?></p>
          <ul class="salle-points">
            <li><?= t('s3_a') ?></li>
            <li><?= t('s3_b') ?></li>
            <li><?= t('s3_c') ?></li>
            <li><?= t('s3_d') ?></li>
          </ul>
          <p class="salle-prix"><?= t('s3_prix') ?></p>
        </div>
      </article>
    </div>

    <p class="salle-note"><?= t('sl_note') ?></p>

    <div class="cta-bloc">
      <a href="<?= BASE_URL ?>/reservation" class="btn btn-primary"><?= t('home_btn_reserver') ?></a>
    </div>
  </section>
</main>

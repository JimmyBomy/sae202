<main class="concept-page">

  <section class="concept-section" style="padding-bottom:0;">
    <h1 class="sec-title"><?= t('nav_infos') ?></h1>
    <p class="sec-sub"><?= t('ip_sub') ?></p>

    <div class="quick-links">
      <a href="#localisation" class="quick-link">
        <svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
        <span><?= t('ql_loc') ?> &rsaquo;</span>
      </a>
      <a href="#contact" class="quick-link">
        <svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
        <span><?= t('ql_contact') ?> &rsaquo;</span>
      </a>
      <a href="#tarifs" class="quick-link">
        <svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7H12m5 7H9.5a3.5 3.5 0 0 1 0-7H12M7 8h10M7 16h10"/></svg>
        <span><?= t('ql_tarifs') ?> &rsaquo;</span>
      </a>
      <a href="#hebergement" class="quick-link">
        <svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M2 18v-6a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v6M2 14h20M6 10V8a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2M2 18v2M22 18v2"/></svg>
        <span><?= t('ql_heb') ?> &rsaquo;</span>
      </a>
      <a href="#interdictions" class="quick-link">
        <svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
        <span><?= t('ql_int') ?> &rsaquo;</span>
      </a>
    </div>
  </section>

  <!-- ============ LOCALISATION ============ -->
  <section id="localisation" class="concept-section">
    <h2 class="sec-title"><?= t('ip_loc_t') ?></h2>
    <p class="sec-sub"><?= t('ip_loc_sub') ?></p>
    <div class="loc-grid">
      <div class="loc-info">
        <div class="info-field"><span class="info-label"><?= t('lbl_adresse') ?></span><p>Cours Émile Zola</p></div>
        <div class="info-field"><span class="info-label"><?= t('lbl_ville') ?></span><p>Villeurbanne</p></div>
        <div class="info-field"><span class="info-label"><?= t('lbl_cp') ?></span><p>69100</p></div>
      </div>
      <div class="loc-map">
        <iframe
          src="https://www.openstreetmap.org/export/embed.html?bbox=4.8550%2C45.7550%2C4.9100%2C45.7850&layer=mapnik&marker=45.7672%2C4.8794"
          title="Carte de localisation à Villeurbanne" loading="lazy"></iframe>
      </div>
    </div>
  </section>

  <!-- ============ CONTACT ============ -->
  <section id="contact" class="concept-section">
    <h2 class="sec-title"><?= t('ip_ct_t') ?></h2>
    <p class="sec-sub"><?= t('ip_ct_sub') ?></p>
    <div class="loc-grid">
      <div class="loc-info">
        <div class="info-field"><span class="info-label"><?= t('lbl_mail') ?></span><p>backroomsescapegame@gmail.com</p></div>
        <div class="info-field"><span class="info-label"><?= t('lbl_tel') ?></span><p>07 07 07 07 07</p></div>
        <div class="info-field"><span class="info-label"><?= t('lbl_res') ?></span><p>@backroomsescapegame (Instagram)<br>Backrooms Escape Game (Facebook)</p></div>
      </div>
      <form class="loc-form" action="<?= BASE_URL ?>/contact" method="post">
        <?= csrf_input() ?>
        <input type="hidden" name="sujet" value="Message depuis la page Infos pratiques">
        <div class="form-group"><input type="text" name="nom" placeholder="<?= t('ph_nom') ?>" required></div>
        <div class="form-group"><input type="email" name="email" placeholder="<?= t('ph_email') ?>" required></div>
        <div class="form-group"><input type="text" name="telephone" placeholder="<?= t('ph_tel') ?>"></div>
        <div class="form-group"><textarea name="message" rows="4" placeholder="<?= t('ph_msg') ?>" required></textarea></div>
        <button type="submit" class="btn btn-primary"><?= t('btn_envoyer') ?></button>
      </form>
    </div>
  </section>

  <!-- ============ TARIFS ============ -->
  <section id="tarifs" class="concept-section">
    <h2 class="sec-title"><?= t('ip_tar_t') ?></h2>
    <p class="sec-sub"><?= t('ip_tar_sub') ?></p>
    <div class="loc-grid">
      <div>
        <table class="tableau tarif-grille">
          <thead><tr><th><?= t('th_part') ?></th><th><?= t('th_prix') ?></th></tr></thead>
          <tbody>
            <tr><td><?= t('tar_r1') ?></td><td>170 €</td></tr>
            <tr><td><?= t('tar_r2') ?></td><td>165 €</td></tr>
            <tr><td><?= t('tar_r3') ?></td><td>160 €</td></tr>
            <tr><td><?= t('tar_r4') ?></td><td>155 €</td></tr>
            <tr><td><?= t('tar_r5') ?></td><td>150 €</td></tr>
            <tr><td><?= t('tar_r6') ?></td><td>145 €</td></tr>
          </tbody>
        </table>
        <p class="tarif-note"><?= t('tar_note') ?></p>
      </div>

      <div class="calc-tarif">
        <h3 class="pay-titre"><?= t('calc_titre') ?></h3>
        <div class="form-group">
          <label for="calc-salle"><?= t('calc_salle') ?></label>
          <select id="calc-salle" class="resa-field">
            <option value="facile"><?= t('calc_o1') ?></option>
            <option value="standard"><?= t('calc_o2') ?></option>
            <option value="hardcore"><?= t('calc_o3') ?></option>
          </select>
        </div>
        <div class="form-group">
          <label for="calc-nb"><?= t('calc_nb') ?></label>
          <input id="calc-nb" class="resa-field" type="number" min="2" max="10" value="4">
        </div>
        <div class="calc-resultat">
          <span class="calc-pp" id="calc-pp"></span>
          <span class="calc-total" id="calc-total"></span>
        </div>
        <a href="<?= BASE_URL ?>/reservation" class="btn btn-primary" style="margin-top:14px;"><?= t('btn_reserver') ?></a>
      </div>
    </div>
    <script>
      (function () {
        const grille = { 2:170, 3:165, 4:160, 5:155, 6:150 };
        const tarif  = n => (n >= 7 ? 145 : (grille[n] || 170));
        const salle = document.getElementById('calc-salle');
        const nb    = document.getElementById('calc-nb');
        const pp    = document.getElementById('calc-pp');
        const total = document.getElementById('calc-total');
        function maj() {
          const n = parseInt(nb.value, 10);
          if (isNaN(n) || n < 2 || n > 10) { pp.textContent = ''; total.textContent = <?= json_encode(t('calc_err')) ?>; return; }
          const parPers = tarif(n) + (salle.value === 'hardcore' ? 10 : 0);
          pp.textContent = parPers + <?= json_encode(t('calc_unit')) ?>;
          total.textContent = <?= json_encode(t('calc_total')) ?> + (parPers * n) + ' €';
        }
        salle.addEventListener('change', maj);
        nb.addEventListener('input', maj);
        maj();
      })();
    </script>
  </section>

  <!-- ============ HÉBERGEMENT & RESTAURATION ============ -->
  <section id="hebergement" class="concept-section">
    <h2 class="sec-title"><?= t('ip_heb_t') ?></h2>
    <p class="sec-sub"><?= t('ip_heb_sub') ?></p>
    <div class="cartes cartes-3">
      <div class="carte">
        <h2><?= t('heb1_t') ?></h2>
        <p><?= t('heb1_d') ?></p>
      </div>
      <div class="carte">
        <h2><?= t('heb2_t') ?></h2>
        <p><?= t('heb2_d') ?></p>
      </div>
      <div class="carte">
        <h2><?= t('heb3_t') ?></h2>
        <p><?= t('heb3_d') ?></p>
      </div>
    </div>
  </section>

  <!-- ============ INTERDICTIONS ============ -->
  <section id="interdictions" class="concept-section">
    <h2 class="sec-title"><?= t('ip_int_t') ?></h2>
    <p class="sec-sub"><?= t('ip_int_sub') ?></p>
    <div class="interdits-grid">
      <div class="interdit"><img class="interdit-ic" src="<?= BASE_URL ?>/view/img/interdit-telephone.webp" alt="" loading="lazy"><span><?= t('int1') ?></span></div>
      <div class="interdit"><img class="interdit-ic" src="<?= BASE_URL ?>/view/img/interdit-nourriture.webp" alt="" loading="lazy"><span><?= t('int2') ?></span></div>
      <div class="interdit"><img class="interdit-ic" src="<?= BASE_URL ?>/view/img/interdit-arme-feu.webp" alt="" loading="lazy"><span><?= t('int3') ?></span></div>
      <div class="interdit"><img class="interdit-ic" src="<?= BASE_URL ?>/view/img/interdit-cigarette.webp" alt="" loading="lazy"><span><?= t('int4') ?></span></div>
      <div class="interdit"><img class="interdit-ic" src="<?= BASE_URL ?>/view/img/interdit-bombe.webp" alt="" loading="lazy"><span><?= t('int5') ?></span></div>
      <div class="interdit"><img class="interdit-ic" src="<?= BASE_URL ?>/view/img/interdit-arme-blanche.webp" alt="" loading="lazy"><span><?= t('int6') ?></span></div>
      <div class="interdit"><img class="interdit-ic" src="<?= BASE_URL ?>/view/img/interdit-animaux.webp" alt="" loading="lazy"><span><?= t('int7') ?></span></div>
      <div class="interdit"><img class="interdit-ic" src="<?= BASE_URL ?>/view/img/interdit-parapluie.webp" alt="" loading="lazy"><span><?= t('int8') ?></span></div>
    </div>
  </section>

  <div class="cta-bloc">
    <a href="<?= BASE_URL ?>/reservation" class="btn btn-primary"><?= t('home_btn_reserver') ?></a>
  </div>
</main>

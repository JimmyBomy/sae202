<?php
// --- Préparation du calendrier (mois affiché, transmis par le contrôleur) ---
$moisFr = [1=>'janvier',2=>'février',3=>'mars',4=>'avril',5=>'mai',6=>'juin',
           7=>'juillet',8=>'août',9=>'septembre',10=>'octobre',11=>'novembre',12=>'décembre'];
$premier   = DateTime::createFromFormat('Y-m-d', $mois . '-01');
$nbJours   = (int) $premier->format('t');
$decalage  = ((int) $premier->format('N')) - 1;          // 0 = lundi
$aujour    = new DateTime('today');
$suivant   = (clone $premier)->modify('+1 month')->format('Y-m');
$precedent = (clone $premier)->modify('-1 month')->format('Y-m');
$nomMois   = $moisFr[(int) $premier->format('n')] . ' ' . $premier->format('Y');
$moisMin   = max((new DateTime('today'))->format('Y-m'), '2026-06');
$champ = fn(string $k, string $defaut = '') => htmlspecialchars($_POST[$k] ?? $defaut);
?>
<main class="concept-page resa-page">

  <?php if (!empty($erreur)): ?>
    <div class="alert alert-error"><?= htmlspecialchars($erreur) ?></div>
  <?php endif; ?>
  <?php if (!empty($succes)): ?>
    <div class="alert alert-success"><?= $succes ?></div>
  <?php endif; ?>

  <form method="post" action="<?= BASE_URL ?>/reservation" class="resa-form">
    <?= csrf_input() ?>

    <!-- ============ INSCRIPTION ============ -->
    <section class="concept-section pt30">
      <h1 class="sec-title"><?= t('rv_ins_t') ?></h1>
      <p class="sec-sub"><?= t('rv_ins_sub') ?></p>
      <?php if (!$estConnecte): ?>
        <p class="form-switch" style="margin:0 0 18px;"><?= t('rv_already') ?> <a href="<?= BASE_URL ?>/compte/connexion"><?= t('rv_login') ?></a></p>
      <?php endif; ?>

      <div class="resa-grid">
        <div class="resa-col">
          <input class="resa-field" type="text"  name="nom"       placeholder="<?= t('ph_NOM') ?>" aria-label="<?= t('ph_NOM') ?>" value="<?= $champ('nom', $utilisateur['nom'] ?? '') ?>" required>
          <input class="resa-field" type="text"  name="prenom"    placeholder="<?= t('ph_PRENOM') ?>" aria-label="<?= t('ph_PRENOM') ?>" value="<?= $champ('prenom', $utilisateur['prenom'] ?? '') ?>" required>
          <input class="resa-field" type="email" name="email"     placeholder="<?= t('ph_EMAIL') ?>" aria-label="<?= t('ph_EMAIL') ?>" value="<?= $champ('email', $utilisateur['email'] ?? '') ?>" required>
          <input class="resa-field" type="text"  name="telephone" placeholder="<?= t('ph_TEL') ?>" aria-label="<?= t('ph_TEL') ?>" value="<?= $champ('telephone', $utilisateur['telephone'] ?? '') ?>">
        </div>
        <div class="resa-col">
          <input class="resa-field" type="text" name="nom_equipe" placeholder="<?= t('ph_EQUIPE') ?>" aria-label="<?= t('ph_EQUIPE') ?>"
                 value="<?= $equipe ? htmlspecialchars($equipe['nom']) : $champ('nom_equipe') ?>" <?= $equipe ? 'readonly' : '' ?>>
          <?php if (!$equipe): ?>
            <input class="resa-field" type="text" name="code_invite" maxlength="6"
                   placeholder="<?= t('ph_CODE') ?>" aria-label="<?= t('ph_CODE') ?>"
                   value="<?= $champ('code_invite') ?>" style="text-transform:uppercase;">
          <?php endif; ?>
          <input class="resa-field" type="number" name="nb_joueurs" placeholder="<?= t('ph_NB') ?>" aria-label="<?= t('ph_NB') ?>" min="2" max="10" value="<?= $champ('nb_joueurs') ?>" required>
          <select class="resa-field" name="salle" required aria-label="<?= t('calc_salle') ?>">
            <option value=""><?= t('rv_salle_ph') ?></option>
            <option value="facile"   <?= ($_POST['salle'] ?? '') === 'facile'   ? 'selected' : '' ?>><?= t('rv_s1') ?></option>
            <option value="standard" <?= ($_POST['salle'] ?? '') === 'standard' ? 'selected' : '' ?>><?= t('rv_s2') ?></option>
            <option value="hardcore" <?= ($_POST['salle'] ?? '') === 'hardcore' ? 'selected' : '' ?>><?= t('rv_s3') ?></option>
          </select>
          <p class="resa-prix" id="resa-prix" aria-live="polite"></p>
          <label class="resa-dob-label"><?= t('rv_dob') ?></label>
          <?php
            $dn  = !empty($utilisateur['date_naissance']) ? explode('-', $utilisateur['date_naissance']) : null;
            $dnJ = $_POST['naiss_jour']  ?? ($dn ? (int) $dn[2] : '');
            $dnM = $_POST['naiss_mois']  ?? ($dn ? (int) $dn[1] : '');
            $dnA = $_POST['naiss_annee'] ?? ($dn ? (int) $dn[0] : '');
          ?>
          <div class="resa-dob">
            <select class="resa-field" name="naiss_jour" aria-label="<?= t('rv_jour') ?>">
              <option value=""><?= t('rv_jour') ?></option>
              <?php for ($j = 1; $j <= 31; $j++): ?><option <?= $dnJ == $j ? 'selected' : '' ?>><?= $j ?></option><?php endfor; ?>
            </select>
            <select class="resa-field" name="naiss_mois" aria-label="<?= t('rv_mois') ?>">
              <option value=""><?= t('rv_mois') ?></option>
              <?php foreach ($moisFr as $n => $m): ?><option value="<?= $n ?>" <?= $dnM == $n ? 'selected' : '' ?>><?= ucfirst($m) ?></option><?php endforeach; ?>
            </select>
            <select class="resa-field" name="naiss_annee" aria-label="<?= t('rv_annee') ?>">
              <option value=""><?= t('rv_annee') ?></option>
              <?php for ($a = 2010; $a >= 1940; $a--): ?><option <?= $dnA == $a ? 'selected' : '' ?>><?= $a ?></option><?php endfor; ?>
            </select>
          </div>
        </div>
      </div>
    </section>

    <!-- ============ NOS DISPONIBILITÉS (calendrier) ============ -->
    <section class="concept-section" id="disponibilites">
      <h2 class="sec-title"><?= t('rv_dispo_t') ?></h2>
      <p class="sec-sub"><?= t('rv_come') . htmlspecialchars($nomMois) ?> !</p>

      <div class="calendrier">
        <div class="cal-grille">
          <span class="cal-jour">Lundi</span><span class="cal-jour">Mardi</span><span class="cal-jour">Mercredi</span>
          <span class="cal-jour">Jeudi</span><span class="cal-jour">Vendredi</span><span class="cal-jour">Samedi</span><span class="cal-jour">Dimanche</span>
          <?php for ($i = 0; $i < $decalage; $i++): ?><span class="cal-vide"></span><?php endfor; ?>
          <?php for ($jour = 1; $jour <= $nbJours; $jour++):
              $dateJour = $premier->format('Y-m') . '-' . str_pad($jour, 2, '0', STR_PAD_LEFT);
              $passe = ($dateJour < $aujour->format('Y-m-d'));
          ?>
            <?php if ($passe): ?>
              <span class="cal-case cal-passe" title="<?= t('cal_ferme') ?>"><span class="cal-num"><?= $jour ?></span></span>
            <?php elseif (in_array($dateJour, $joursComplets ?? [], true)): ?>
              <span class="cal-case cal-complet" title="<?= t('cal_complet') ?>"><span class="cal-num"><?= $jour ?></span></span>
            <?php elseif (!creneau_ouvert($dateJour)): ?>
              <span class="cal-case cal-ferme" title="<?= t('cal_ferme') ?>"><span class="cal-num"><?= $jour ?></span></span>
            <?php else: ?>
              <input type="radio" name="date_session" id="cal<?= $dateJour ?>" value="<?= $dateJour ?>" class="cal-radio"
                     <?= ($_POST['date_session'] ?? '') === $dateJour ? 'checked' : '' ?>>
              <label for="cal<?= $dateJour ?>" class="cal-case" title="<?= t('cal_dispo') ?>"><span class="cal-num"><?= $jour ?></span></label>
            <?php endif; ?>
          <?php endfor; ?>
        </div>
        <ul class="cal-legende">
          <li><span class="lg lg-ouvert"></span> <?= t('cal_dispo') ?></li>
          <li><span class="lg lg-complet"></span> <?= t('cal_complet') ?></li>
          <li><span class="lg lg-ferme"></span> <?= t('cal_ferme') ?></li>
        </ul>
        <div class="cal-pagination">
          <?php if ($mois > $moisMin): ?>
            <button type="submit" name="mois_aff" value="<?= $precedent ?>" class="btn-link cal-btn"
                    formaction="<?= BASE_URL ?>/reservation#disponibilites" formnovalidate><?= t('rv_prev') ?></button>
          <?php else: ?>
            <span></span>
          <?php endif; ?>
          <button type="submit" name="mois_aff" value="<?= $suivant ?>" class="btn-link cal-btn"
                  formaction="<?= BASE_URL ?>/reservation#disponibilites" formnovalidate><?= t('rv_next') ?></button>
        </div>
      </div>
    </section>

    <!-- ============ SANTÉ ET SÉCURITÉ ============ -->
    <section class="concept-section">
      <h2 class="sec-title"><?= t('rv_sante_t') ?></h2>
      <p class="sec-sub"><?= t('rv_sante_sub') ?></p>

      <?php
      $questions = ['sante_cardiaque'=>t('rv_q1'), 'sante_epilepsie'=>t('rv_q2'), 'sante_respiratoire'=>t('rv_q3'), 'sante_claustro'=>t('rv_q4')];
      foreach ($questions as $nomQ => $libelle): ?>
        <div class="sante-q">
          <span class="sante-libelle"><?= $libelle ?></span>
          <div class="ouinon">
            <label><input type="radio" name="<?= $nomQ ?>" value="oui" <?= ($_POST[$nomQ] ?? '') === 'oui' ? 'checked' : '' ?>> <?= t('rv_oui') ?></label>
            <label><input type="radio" name="<?= $nomQ ?>" value="non" <?= ($_POST[$nomQ] ?? '') === 'non' ? 'checked' : '' ?>> <?= t('rv_non') ?></label>
          </div>
        </div>
      <?php endforeach; ?>

      <div class="sante-q">
        <span class="sante-libelle"><?= t('rv_regime_q') ?></span>
        <select class="resa-field" name="regime" style="max-width:240px;" aria-label="<?= t('rv_regime_q') ?>">
          <?php
            $regimes = ['aucun'=>t('reg_aucun'),'vegetarien'=>t('reg_vegetarien'),'vegan'=>t('reg_vegan'),'sans_gluten'=>t('reg_sans_gluten'),'halal'=>t('reg_halal'),'autre'=>t('reg_autre')];
            foreach ($regimes as $val => $lib):
          ?>
            <option value="<?= $val ?>" <?= ($_POST['regime'] ?? '') === $val ? 'selected' : '' ?>><?= $lib ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </section>

    <!-- ============ PAIEMENT ============ -->
    <section class="concept-section">
      <h2 class="sec-title"><?= t('rv_pay_t') ?></h2>
      <p class="sec-sub"><?= t('rv_pay_sub') ?></p>

      <div class="paiement-grid">
        <div class="pay-col">
          <h3 class="pay-titre"><?= t('rv_pay_card') ?></h3>
          <input class="resa-field" type="text" name="cb_numero"  placeholder="XXXX XXXX XXXX XXXX" aria-label="XXXX XXXX XXXX XXXX" inputmode="numeric" autocomplete="off" value="<?= $champ('cb_numero') ?>">
          <div class="pay-ligne">
            <input class="resa-field" type="text" name="cb_exp" placeholder="MM / AA" aria-label="MM / AA" autocomplete="off" value="<?= $champ('cb_exp') ?>">
            <input class="resa-field" type="text" name="cb_cvv" placeholder="CVV" aria-label="CVV" autocomplete="off" value="<?= $champ('cb_cvv') ?>">
          </div>
          <input class="resa-field" type="text" name="cb_tel" placeholder="<?= t('ph_TEL') ?>" aria-label="<?= t('ph_TEL') ?>" autocomplete="off" value="<?= $champ('cb_tel') ?>">
          <button type="submit" name="paiement" value="carte" class="btn btn-primary pay-btn"><?= t('rv_pay_btn') ?></button>
        </div>

        <div class="pay-col">
          <h3 class="pay-titre"><?= t('rv_pay_place') ?></h3>
          <p class="pay-info"><?= t('rv_pay_info') ?></p>
          <div class="pay-logos">
            <span>VISA</span><span>Mastercard</span><span>AMEX</span><span>ANCV</span><span> Pay</span>
          </div>
          <button type="submit" name="paiement" value="sur_place" class="btn btn-outline pay-btn"><?= t('rv_pay_finalise') ?></button>
        </div>
      </div>
    </section>

  </form>

  <!-- Récapitulatif des réservations de l'équipe -->
  <?php if (!empty($reservations)): ?>
    <section class="concept-section">
      <h2 class="sec-title"><?= t('rv_myresa_t') ?></h2>
      <table class="tableau">
        <thead><tr><th><?= t('th_salle') ?></th><th><?= t('th_date') ?></th><th><?= t('th_joueurs') ?></th><th><?= t('th_statut') ?></th></tr></thead>
        <tbody>
          <?php foreach ($reservations as $r): ?>
            <tr>
              <td><?= htmlspecialchars(ucfirst($r['salle'])) ?></td>
              <td><?= htmlspecialchars(date('d/m/Y à H\hi', strtotime($r['date_session']))) ?></td>
              <td><?= htmlspecialchars($r['nb_joueurs']) ?></td>
              <td><span class="badge badge-<?= htmlspecialchars($r['statut']) ?>"><?= htmlspecialchars(str_replace('_',' ',$r['statut'])) ?></span></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>
  <?php endif; ?>
</main>

<script>
  (function () {
    const grille = { 2:170, 3:165, 4:160, 5:155, 6:150 };
    const tarif = n => (n >= 7 ? 145 : (grille[n] || 170));
    const salle = document.querySelector('[name=salle]');
    const nb    = document.querySelector('[name=nb_joueurs]');
    const out   = document.getElementById('resa-prix');
    if (!salle || !nb || !out) return;
    function maj() {
      const n = parseInt(nb.value, 10);
      if (!salle.value || isNaN(n) || n < 2 || n > 10) { out.textContent = ''; return; }
      const pp = tarif(n) + (salle.value === 'hardcore' ? 10 : 0);
      out.textContent = <?= json_encode(t('rv_js_pre')) ?> + (pp * n) + ' € (' + pp + <?= json_encode(t('rv_js_suf')) ?>;
    }
    salle.addEventListener('change', maj); nb.addEventListener('input', maj); maj();
  })();
</script>

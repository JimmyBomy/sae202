<main class="concept-page profil-page">

  <?php if (!empty($erreur)): ?><div class="alert alert-error"><?= htmlspecialchars($erreur) ?></div><?php endif; ?>
  <?php if (!empty($succes)): ?><div class="alert alert-success"><?= $succes ?></div><?php endif; ?>
  <?php if (!empty($_SESSION['mdp_auto'])): ?>
    <div class="alert alert-error"><?= t('pf_mdp_auto') ?></div>
  <?php endif; ?>

  <!-- ============ MON PROFIL ============ -->
  <section class="concept-section pt30">
    <h1 class="sec-title"><?= t('pf_title') ?></h1>
    <p class="sec-sub"><?= t('pf_sub') ?></p>

    <form method="post" action="<?= BASE_URL ?>/profil" class="profil-bloc" enctype="multipart/form-data">
      <?= csrf_input() ?>
      <div class="profil-avatar">
        <div class="avatar-cercle">
          <?php if (!empty($utilisateur['photo'])): ?>
            <img class="avatar-img" src="<?= BASE_URL ?>/view/uploads/avatars/<?= htmlspecialchars($utilisateur['photo']) ?>" alt="">
          <?php else: ?>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21v-1a6 6 0 0 1 12 0v1"/></svg>
          <?php endif; ?>
        </div>
        <label for="photo" class="avatar-changer" style="cursor:pointer;"><?= t('pf_photo') ?></label>
        <input type="file" name="photo" id="photo" accept="image/jpeg,image/png,image/webp" class="avatar-input">
      </div>
      <div class="profil-champs">
        <label class="champ">
          <span class="champ-label"><?= t('pf_pseudo') ?></span>
          <input type="text" name="pseudo" value="<?= htmlspecialchars($utilisateur['pseudo']) ?>" required>
        </label>
        <label class="champ">
          <span class="champ-label"><?= t('pf_email') ?></span>
          <input type="email" name="email" value="<?= htmlspecialchars($utilisateur['email']) ?>" required>
        </label>
        <label class="champ">
          <span class="champ-label"><?= t('pf_tel') ?></span>
          <input type="text" name="telephone" value="<?= htmlspecialchars($utilisateur['telephone'] ?? '') ?>">
        </label>
        <label class="champ">
          <span class="champ-label"><?= t('pf_mdp') ?></span>
          <input type="password" name="mot_de_passe" placeholder="<?= t('pf_mdp_ph') ?>">
        </label>
        <button type="submit" class="btn btn-primary"><?= t('pf_save') ?></button>
      </div>
    </form>
  </section>

  <!-- ============ MON ÉQUIPE ============ -->
  <section class="concept-section">
    <h2 class="sec-title"><?= t('pf_eq_t') ?></h2>
    <p class="sec-sub"><?= t('pf_eq_sub') ?></p>

    <?php if (!$equipe): ?>
      <p><?= t('pf_no_eq') ?></p>
      <a href="<?= BASE_URL ?>/reservation" class="btn btn-outline"><?= t('pf_create_eq') ?></a>
    <?php else: ?>
      <div class="profil-bloc">
        <div class="profil-avatar">
          <div class="avatar-cercle">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          </div>
        </div>
        <div class="profil-champs">
          <div class="champ"><span class="champ-label"><?= t('pf_eq_nom') ?></span><span class="champ-val"><?= htmlspecialchars($equipe['nom']) ?></span></div>
          <div class="champ"><span class="champ-label"><?= t('pf_eq_nb') ?></span><span class="champ-val"><?= count($membres) ?></span></div>
          <div class="champ"><span class="champ-label"><?= t('pf_eq_niv') ?></span><span class="champ-val"><?= htmlspecialchars($niveauMax) ?></span></div>
          <div class="champ"><span class="champ-label"><?= t('pf_eq_code') ?></span><span class="champ-val code-invite"><?= htmlspecialchars($equipe['code_invite']) ?></span></div>
          <a href="<?= BASE_URL ?>/reservation" class="btn btn-primary"><?= t('pf_eq_gerer') ?></a>
        </div>
      </div>
    <?php endif; ?>
  </section>

  <!-- ============ MES STATISTIQUES ============ -->
  <section class="concept-section">
    <h2 class="sec-title"><?= t('pf_stats_t') ?></h2>
    <p class="sec-sub"><?= t('pf_stats_sub') ?></p>

    <div class="stats-grid stats-grid--4">
      <div class="stat-item">
        <svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        <span class="stat-num"><?= $tempsMoyen !== null ? floor($tempsMoyen/3600) . 'h' . str_pad(floor(($tempsMoyen%3600)/60), 2, '0', STR_PAD_LEFT) : '—' ?></span>
        <span class="stat-lbl"><?= t('pf_st_temps') ?></span>
      </div>
      <div class="stat-item">
        <svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18M6 21V4a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v17"/><circle cx="15" cy="12" r="1"/></svg>
        <span class="stat-num"><?= (int) $sallesExplorees ?></span>
        <span class="stat-lbl"><?= t('pf_st_salles') ?></span>
      </div>
      <div class="stat-item">
        <svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
        <span class="stat-num"><?= (int) $taux ?>%</span>
        <span class="stat-lbl"><?= t('pf_st_taux') ?></span>
      </div>
      <div class="stat-item">
        <svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="9" r="6"/><path d="M9 14l-1 7 4-2 4 2-1-7"/></svg>
        <span class="stat-num"><?= (int) $points ?></span>
        <span class="stat-lbl"><?= t('pf_st_pts') ?></span>
      </div>
    </div>

    <?php if ($nbParties > 0):
      $partUrl = 'https://' . htmlspecialchars($_SERVER['HTTP_HOST'] ?? 'sae202.mmi-troyes.fr') . BASE_URL . '/classement';
      $partTxt = sprintf(t('pf_part_msg'), ($equipe['nom'] ?? '—'), (int) $points);
      $u = rawurlencode($partUrl); $tt = rawurlencode($partTxt);
    ?>
    <div class="profil-partage">
      <p class="partage-titre"><?= t('pf_part_titre') ?></p>
      <div class="partage-liens">
        <a class="partage-btn" href="https://www.facebook.com/sharer/sharer.php?u=<?= $u ?>" target="_blank" rel="noopener noreferrer" aria-label="Facebook">Facebook</a>
        <a class="partage-btn" href="https://twitter.com/intent/tweet?text=<?= $tt ?>&url=<?= $u ?>" target="_blank" rel="noopener noreferrer" aria-label="X">X&nbsp;/&nbsp;Twitter</a>
        <a class="partage-btn" href="https://wa.me/?text=<?= rawurlencode($partTxt . ' ' . $partUrl) ?>" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">WhatsApp</a>
        <a class="partage-btn" href="https://www.linkedin.com/sharing/share-offsite/?url=<?= $u ?>" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">LinkedIn</a>
      </div>
    </div>
    <?php endif; ?>
  </section>

  <!-- ============ MES PARTIES ============ -->
  <section class="concept-section">
    <h2 class="sec-title"><?= t('pf_parties_t') ?></h2>
    <p class="sec-sub"><?= t('pf_parties_sub') ?></p>

    <?php if (empty($reservations)): ?>
      <p><?= t('pf_no_parties') ?> <a href="<?= BASE_URL ?>/reservation" class="btn-link"><?= t('pf_resa_first') ?></a></p>
    <?php else: ?>
      <table class="tableau">
        <thead><tr><th><?= t('pf_th_session') ?></th><th><?= t('pf_th_date') ?></th><th><?= t('pf_th_heure') ?></th><th><?= t('th_statut') ?></th><th></th></tr></thead>
        <tbody>
          <?php foreach ($reservations as $r):
            $t = strtotime($r['date_session']);
            if ($r['statut'] === 'annulee')      { $lbl = t('pf_st_annulee');  $cls = 'annulee'; $venir = false; }
            elseif ($t < time())                 { $lbl = t('pf_st_terminee'); $cls = 'confirmee'; $venir = false; }
            else                                 { $lbl = t('pf_st_avenir');   $cls = 'en_attente'; $venir = true; }
          ?>
            <tr>
              <td class="session-cell">
                <img class="salle-thumb" src="<?= BASE_URL ?>/view/img/salle-<?= htmlspecialchars($r['salle']) ?>.jpg" alt="">
                <?= htmlspecialchars(ucfirst($r['salle'])) ?>
              </td>
              <td><?= htmlspecialchars(date('d/m/Y', $t)) ?></td>
              <td><?= htmlspecialchars(date('H\hi', $t)) ?></td>
              <td><span class="badge badge-<?= $cls ?>"><?= $lbl ?></span></td>
              <td>
                <?php if ($venir): ?>
                  <form method="post" action="<?= BASE_URL ?>/profil" onsubmit="return confirm('<?= t('pf_confirm_annul') ?>');">
                    <?= csrf_input() ?>
                    <button type="submit" name="annuler_resa" value="<?= $r['id'] ?>" class="btn btn-outline btn-annuler"><?= t('pf_annuler') ?></button>
                  </form>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
    <p class="center-mt"><a href="<?= BASE_URL ?>/profil/commentaire" class="btn-link"><?= t('pf_leave_avis') ?></a></p>
  </section>

  <!-- ============ ZONE SÉCURITÉ ============ -->
  <section class="concept-section">
    <h2 class="sec-title"><?= t('pf_zone_t') ?></h2>
    <p class="sec-sub"><?= t('pf_zone_sub') ?></p>

    <a class="zone-lien" href="<?= BASE_URL ?>/profil/password">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
      <span><strong><?= t('pf_chg_mdp') ?></strong><br><small><?= t('pf_chg_mdp_d') ?></small></span>
      <span class="zone-fleche">&rsaquo;</span>
    </a>
    <a class="zone-lien" href="<?= BASE_URL ?>/compte/deconnexion">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
      <span><strong><?= t('pf_deco') ?></strong><br><small><?= t('pf_deco_d') ?></small></span>
      <span class="zone-fleche">&rsaquo;</span>
    </a>
  </section>
</main>

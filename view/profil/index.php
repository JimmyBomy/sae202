<main class="concept-page profil-page">

  <?php if (!empty($erreur)): ?><div class="alert alert-error"><?= htmlspecialchars($erreur) ?></div><?php endif; ?>
  <?php if (!empty($succes)): ?><div class="alert alert-success"><?= $succes ?></div><?php endif; ?>
  <?php if (!empty($_SESSION['mdp_auto'])): ?>
    <div class="alert alert-error">⚠️ Votre compte a été créé automatiquement lors de votre réservation :
    <strong>définissez un mot de passe ci-dessous</strong> pour pouvoir vous reconnecter plus tard.</div>
  <?php endif; ?>

  <!-- ============ MON PROFIL ============ -->
  <section class="concept-section" style="padding-top:30px;">
    <h1 class="sec-title">MON PROFIL</h1>
    <p class="sec-sub">Gérez vos informations et vos réservations !</p>

    <form method="post" action="<?= BASE_URL ?>/profil" class="profil-bloc" enctype="multipart/form-data">
      <?= csrf_input() ?>
      <div class="profil-avatar">
        <div class="avatar-cercle">
          <?php if (!empty($utilisateur['photo'])): ?>
            <img class="avatar-img" src="<?= BASE_URL ?>/view/uploads/avatars/<?= htmlspecialchars($utilisateur['photo']) ?>" alt="Photo de profil">
          <?php else: ?>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21v-1a6 6 0 0 1 12 0v1"/></svg>
          <?php endif; ?>
        </div>
        <!-- Le label déclenche le champ fichier caché ; l'envoi se fait avec "Modifier mes informations" -->
        <label for="photo" class="avatar-changer" style="cursor:pointer;">Changer la photo</label>
        <input type="file" name="photo" id="photo" accept="image/jpeg,image/png,image/webp" class="avatar-input">
      </div>
      <div class="profil-champs">
        <label class="champ">
          <span class="champ-label">Pseudo</span>
          <input type="text" name="pseudo" value="<?= htmlspecialchars($utilisateur['pseudo']) ?>" required>
        </label>
        <label class="champ">
          <span class="champ-label">Email</span>
          <input type="email" name="email" value="<?= htmlspecialchars($utilisateur['email']) ?>" required>
        </label>
        <label class="champ">
          <span class="champ-label">Téléphone</span>
          <input type="text" name="telephone" value="<?= htmlspecialchars($utilisateur['telephone'] ?? '') ?>">
        </label>
        <label class="champ">
          <span class="champ-label">Mot de passe</span>
          <input type="password" name="mot_de_passe" placeholder="Laisser vide pour ne pas changer">
        </label>
        <button type="submit" class="btn btn-primary">Modifier mes informations</button>
      </div>
    </form>
  </section>

  <!-- ============ MON ÉQUIPE ============ -->
  <section class="concept-section">
    <h2 class="sec-title">MON ÉQUIPE</h2>
    <p class="sec-sub">Gérez votre équipe</p>

    <?php if (!$equipe): ?>
      <p>Vous n'avez pas encore d'équipe.</p>
      <a href="<?= BASE_URL ?>/reservation" class="btn btn-outline">Créer mon équipe</a>
    <?php else: ?>
      <div class="profil-bloc">
        <div class="profil-avatar">
          <div class="avatar-cercle">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          </div>
        </div>
        <div class="profil-champs">
          <div class="champ"><span class="champ-label">Nom de l'équipe</span><span class="champ-val"><?= htmlspecialchars($equipe['nom']) ?></span></div>
          <div class="champ"><span class="champ-label">Nombre de membres</span><span class="champ-val"><?= count($membres) ?></span></div>
          <div class="champ"><span class="champ-label">Niveau max atteint</span><span class="champ-val"><?= htmlspecialchars($niveauMax) ?></span></div>
          <div class="champ"><span class="champ-label">Code d'invitation</span><span class="champ-val code-invite"><?= htmlspecialchars($equipe['code_invite']) ?></span></div>
          <a href="<?= BASE_URL ?>/reservation" class="btn btn-primary">Gérer mon équipe</a>
        </div>
      </div>
    <?php endif; ?>
  </section>

  <!-- ============ MES STATISTIQUES ============ -->
  <section class="concept-section">
    <h2 class="sec-title">MES STATISTIQUES</h2>
    <p class="sec-sub">Voici vos statistiques durant vos parties !</p>

    <div class="stats-grid stats-grid--4">
      <div class="stat-item">
        <svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        <span class="stat-num"><?= $tempsMoyen !== null ? floor($tempsMoyen/3600) . 'h' . str_pad(floor(($tempsMoyen%3600)/60), 2, '0', STR_PAD_LEFT) : '—' ?></span>
        <span class="stat-lbl">Temps moyen pour s'échapper</span>
      </div>
      <div class="stat-item">
        <svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18M6 21V4a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v17"/><circle cx="15" cy="12" r="1"/></svg>
        <span class="stat-num"><?= (int) $sallesExplorees ?></span>
        <span class="stat-lbl">Salles explorées</span>
      </div>
      <div class="stat-item">
        <svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
        <span class="stat-num"><?= (int) $taux ?>%</span>
        <span class="stat-lbl">Taux de réussite</span>
      </div>
      <div class="stat-item">
        <svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="9" r="6"/><path d="M9 14l-1 7 4-2 4 2-1-7"/></svg>
        <span class="stat-num"><?= (int) $points ?></span>
        <span class="stat-lbl">Mes points</span>
      </div>
    </div>

    <?php if ($nbParties > 0):
      // Partage du profil/score sur les réseaux sociaux (cahier des charges : possible une fois l'escape terminé et le score obtenu)
      $partUrl = 'https://' . htmlspecialchars($_SERVER['HTTP_HOST'] ?? 'sae202.mmi-troyes.fr') . BASE_URL . '/classement';
      $partTxt = "J'ai bravé les BACKROOMS avec l'équipe " . ($equipe['nom'] ?? 'mystère') . " : " . (int) $points . " points ! Oserez-vous nous suivre dans les couloirs ?";
      $u = rawurlencode($partUrl); $t = rawurlencode($partTxt);
    ?>
    <div class="profil-partage">
      <p class="partage-titre">Fier·e de votre score ? Partagez votre exploit&nbsp;:</p>
      <div class="partage-liens">
        <a class="partage-btn" href="https://www.facebook.com/sharer/sharer.php?u=<?= $u ?>" target="_blank" rel="noopener noreferrer" aria-label="Partager mon score sur Facebook">Facebook</a>
        <a class="partage-btn" href="https://twitter.com/intent/tweet?text=<?= $t ?>&url=<?= $u ?>" target="_blank" rel="noopener noreferrer" aria-label="Partager mon score sur X">X&nbsp;/&nbsp;Twitter</a>
        <a class="partage-btn" href="https://wa.me/?text=<?= rawurlencode($partTxt . ' ' . $partUrl) ?>" target="_blank" rel="noopener noreferrer" aria-label="Partager mon score sur WhatsApp">WhatsApp</a>
        <a class="partage-btn" href="https://www.linkedin.com/sharing/share-offsite/?url=<?= $u ?>" target="_blank" rel="noopener noreferrer" aria-label="Partager mon score sur LinkedIn">LinkedIn</a>
      </div>
    </div>
    <?php endif; ?>
  </section>

  <!-- ============ MES PARTIES ============ -->
  <section class="concept-section">
    <h2 class="sec-title">MES PARTIES</h2>
    <p class="sec-sub">Rappelez-vous de vos parties avec nous !</p>

    <?php if (empty($reservations)): ?>
      <p>Aucune partie pour le moment. <a href="<?= BASE_URL ?>/reservation" class="btn-link">Réservez votre première session ›</a></p>
    <?php else: ?>
      <table class="tableau">
        <thead><tr><th>Session</th><th>Date</th><th>Heure</th><th>Statut</th><th></th></tr></thead>
        <tbody>
          <?php foreach ($reservations as $r):
            $t = strtotime($r['date_session']);
            if ($r['statut'] === 'annulee')      { $lbl = 'Annulée';      $cls = 'annulee'; }
            elseif ($t < time())                 { $lbl = 'Terminée';     $cls = 'confirmee'; }
            else                                 { $lbl = 'À venir';      $cls = 'en_attente'; }
          ?>
            <tr>
              <td class="session-cell">
                <img class="salle-thumb" src="<?= BASE_URL ?>/view/img/salle-<?= htmlspecialchars($r['salle']) ?>.jpg" alt="">
                Salle <?= htmlspecialchars(ucfirst($r['salle'])) ?>
              </td>
              <td><?= htmlspecialchars(date('d/m/Y', $t)) ?></td>
              <td><?= htmlspecialchars(date('H\hi', $t)) ?></td>
              <td><span class="badge badge-<?= $cls ?>"><?= $lbl ?></span></td>
              <td>
                <?php if ($lbl === 'À venir'): ?>
                  <!-- Annulation par le joueur (POST + CSRF, vérifiée côté serveur) -->
                  <form method="post" action="<?= BASE_URL ?>/profil" onsubmit="return confirm('Annuler cette réservation ?');">
                    <?= csrf_input() ?>
                    <button type="submit" name="annuler_resa" value="<?= $r['id'] ?>" class="btn btn-outline btn-annuler">Annuler</button>
                  </form>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
    <p style="text-align:center; margin-top:18px;"><a href="<?= BASE_URL ?>/profil/commentaire" class="btn-link">Laisser un avis sur votre expérience &rsaquo;</a></p>
  </section>

  <!-- ============ ZONE SÉCURITÉ ============ -->
  <section class="concept-section">
    <h2 class="sec-title">ZONE SÉCURITÉ</h2>
    <p class="sec-sub">La sécurité de vos données est notre priorité.</p>

    <a class="zone-lien" href="<?= BASE_URL ?>/profil/password">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
      <span><strong>Changer le mot de passe</strong><br><small>Sécurisez votre compte en modifiant votre mot de passe.</small></span>
      <span class="zone-fleche">&rsaquo;</span>
    </a>
    <a class="zone-lien" href="<?= BASE_URL ?>/compte/deconnexion">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
      <span><strong>Déconnexion</strong><br><small>Se déconnecter de votre compte.</small></span>
      <span class="zone-fleche">&rsaquo;</span>
    </a>
  </section>
</main>

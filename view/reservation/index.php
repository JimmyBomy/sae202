<main class="container page-contenu" style="padding-top: 40px;">
  <h1>Réserver une session</h1>

  <?php if (!empty($erreur)): ?>
    <div class="alert alert-error"><?= htmlspecialchars($erreur) ?></div>
  <?php endif; ?>
  <?php if (!empty($succes)): ?>
    <div class="alert alert-success"><?= $succes ?></div>
  <?php endif; ?>

  <?php if (!$equipe): ?>
    <!-- L'utilisateur n'a pas encore d'équipe : il en crée une ou en rejoint une. -->
    <p class="page-intro">
      L'aventure se vit en équipe&nbsp;! Créez la vôtre et invitez vos amis grâce à un code,
      ou rejoignez une équipe déjà créée.
    </p>

    <div class="cartes">
      <div class="carte">
        <h2>Créer une équipe</h2>
        <form method="post" class="form-bloc">
          <input type="hidden" name="form_type" value="creer_equipe">
          <div class="form-group">
            <label for="nom_equipe">Nom de l'équipe *</label>
            <input type="text" name="nom_equipe" id="nom_equipe" maxlength="80" required>
          </div>
          <button type="submit" class="btn btn-primary">Créer mon équipe</button>
        </form>
      </div>

      <div class="carte">
        <h2>Rejoindre une équipe</h2>
        <form method="post" class="form-bloc">
          <input type="hidden" name="form_type" value="rejoindre_equipe">
          <div class="form-group">
            <label for="code_invite">Code d'invitation (6 caractères) *</label>
            <input type="text" name="code_invite" id="code_invite" maxlength="6"
                   style="text-transform:uppercase; letter-spacing:3px;" required>
          </div>
          <button type="submit" class="btn btn-outline">Rejoindre</button>
        </form>
      </div>
    </div>

  <?php else: ?>
    <!-- L'utilisateur a une équipe : tableau de bord d'équipe + réservation. -->
    <div class="carte equipe-entete">
      <h2>Équipe «&nbsp;<?= htmlspecialchars($equipe['nom']) ?>&nbsp;»</h2>
      <p>Code d'invitation à partager&nbsp;:
        <span class="code-invite"><?= htmlspecialchars($equipe['code_invite']) ?></span>
      </p>
      <p><strong>Membres (<?= count($membres) ?>)&nbsp;:</strong>
        <?php
          $noms = array_map(fn($m) => htmlspecialchars($m['pseudo']), $membres);
          echo implode(', ', $noms);
        ?>
      </p>
    </div>

    <div class="carte">
      <h2>Nouvelle réservation</h2>
      <form method="post" class="form-bloc">
        <input type="hidden" name="form_type" value="reserver">
        <div class="form-group">
          <label for="salle">Salle *</label>
          <select name="salle" id="salle" required>
            <option value="facile">Facile — « Le Niveau 0 » (débutants)</option>
            <option value="standard" selected>Standard — « Les Couloirs jaunes »</option>
            <option value="hardcore">Hardcore — « Le Niveau ! » (experts)</option>
          </select>
        </div>
        <div class="form-group">
          <label for="date_session">Date et heure de la session *</label>
          <input type="datetime-local" name="date_session" id="date_session" required>
        </div>
        <div class="form-group">
          <label for="nb_joueurs">Nombre de joueurs (2 à 6) *</label>
          <input type="number" name="nb_joueurs" id="nb_joueurs" min="2" max="6" value="<?= count($membres) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Réserver</button>
      </form>
    </div>

    <div class="carte">
      <h2>Mes réservations</h2>
      <?php if (empty($reservations)): ?>
        <p>Aucune réservation pour le moment.</p>
      <?php else: ?>
        <table class="tableau">
          <thead>
            <tr><th>Salle</th><th>Date</th><th>Joueurs</th><th>Statut</th></tr>
          </thead>
          <tbody>
            <?php foreach ($reservations as $r): ?>
              <tr>
                <td><?= htmlspecialchars(ucfirst($r['salle'])) ?></td>
                <td><?= htmlspecialchars(date('d/m/Y à H\hi', strtotime($r['date_session']))) ?></td>
                <td><?= htmlspecialchars($r['nb_joueurs']) ?></td>
                <td><span class="badge badge-<?= htmlspecialchars($r['statut']) ?>"><?= htmlspecialchars(str_replace('_', ' ', $r['statut'])) ?></span></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>

    <div class="carte">
      <h2>Scores de l'équipe</h2>
      <?php if (empty($scores)): ?>
        <p>Vos scores apparaîtront ici après votre partie.</p>
      <?php else: ?>
        <table class="tableau">
          <thead>
            <tr><th>Date</th><th>Points</th><th>Temps</th><th>Résultat</th></tr>
          </thead>
          <tbody>
            <?php foreach ($scores as $s): ?>
              <tr>
                <td><?= htmlspecialchars(date('d/m/Y', strtotime($s['date_partie']))) ?></td>
                <td><?= htmlspecialchars($s['points']) ?> pts</td>
                <td><?= $s['temps_secondes'] !== null ? floor($s['temps_secondes'] / 60) . ' min ' . ($s['temps_secondes'] % 60) . ' s' : '—' ?></td>
                <td><?= $s['reussi'] ? '✅ Sortis !' : '❌ Coincés' ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  <?php endif; ?>
</main>

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
?>
<main class="concept-page resa-page">

  <?php if (!empty($erreur)): ?>
    <div class="alert alert-error"><?= htmlspecialchars($erreur) ?></div>
  <?php endif; ?>
  <?php if (!empty($succes)): ?>
    <div class="alert alert-success"><?= $succes ?></div>
  <?php endif; ?>

  <form method="post" action="<?= BASE_URL ?>/reservation" class="resa-form">

    <!-- ============ INSCRIPTION ============ -->
    <section class="concept-section" style="padding-top:30px;">
      <h1 class="sec-title">INSCRIPTION</h1>
      <p class="sec-sub">Nous avons besoin de vos informations !</p>

      <div class="resa-grid">
        <div class="resa-col">
          <input class="resa-field" type="text"  name="nom"       placeholder="NOM"       value="<?= htmlspecialchars($utilisateur['nom'] ?? '') ?>" required>
          <input class="resa-field" type="text"  name="prenom"    placeholder="PRÉNOM"    value="<?= htmlspecialchars($utilisateur['prenom'] ?? '') ?>" required>
          <input class="resa-field" type="email" name="email"     placeholder="EMAIL"     value="<?= htmlspecialchars($utilisateur['email'] ?? '') ?>" required>
          <input class="resa-field" type="text"  name="telephone" placeholder="TÉLÉPHONE" value="<?= htmlspecialchars($utilisateur['telephone'] ?? '') ?>">
          <?php if (!$estConnecte): ?>
            <input class="resa-field" type="password" name="mot_de_passe" placeholder="MOT DE PASSE (espace privé)" minlength="6" required>
          <?php endif; ?>
        </div>
        <div class="resa-col">
          <input class="resa-field" type="text" name="nom_equipe" placeholder="NOM DE L'ÉQUIPE"
                 value="<?= $equipe ? htmlspecialchars($equipe['nom']) : '' ?>" <?= $equipe ? 'readonly' : 'required' ?>>
          <input class="resa-field" type="number" name="nb_joueurs" placeholder="NOMBRE DE PARTICIPANTS (2 à 6)" min="2" max="6" required>
          <label class="resa-dob-label">Date de naissance</label>
          <div class="resa-dob">
            <select class="resa-field" name="naiss_jour" aria-label="Jour">
              <option value="">Jour</option>
              <?php for ($j = 1; $j <= 31; $j++): ?><option><?= $j ?></option><?php endfor; ?>
            </select>
            <select class="resa-field" name="naiss_mois" aria-label="Mois">
              <option value="">Mois</option>
              <?php foreach ($moisFr as $n => $m): ?><option value="<?= $n ?>"><?= ucfirst($m) ?></option><?php endforeach; ?>
            </select>
            <select class="resa-field" name="naiss_annee" aria-label="Année">
              <option value="">Année</option>
              <?php for ($a = 2010; $a >= 1940; $a--): ?><option><?= $a ?></option><?php endfor; ?>
            </select>
          </div>
        </div>
      </div>
    </section>

    <!-- ============ NOS DISPONIBILITÉS (calendrier) ============ -->
    <section class="concept-section">
      <h2 class="sec-title">NOS DISPONIBILITÉS</h2>
      <p class="sec-sub">Venez jouer avec nous en <?= htmlspecialchars($nomMois) ?> !</p>

      <div class="calendrier">
        <div class="cal-entete">
          <a class="cal-nav" href="?mois=<?= $precedent ?>">‹</a>
          <span class="cal-mois"><?= htmlspecialchars(ucfirst($nomMois)) ?></span>
          <a class="cal-nav" href="?mois=<?= $suivant ?>">›</a>
        </div>
        <div class="cal-grille">
          <span class="cal-jour">Lun</span><span class="cal-jour">Mar</span><span class="cal-jour">Mer</span>
          <span class="cal-jour">Jeu</span><span class="cal-jour">Ven</span><span class="cal-jour">Sam</span><span class="cal-jour">Dim</span>
          <?php for ($i = 0; $i < $decalage; $i++): ?><span class="cal-vide"></span><?php endfor; ?>
          <?php for ($jour = 1; $jour <= $nbJours; $jour++):
              $dateJour = $premier->format('Y-m') . '-' . str_pad($jour, 2, '0', STR_PAD_LEFT);
              $passe = ($dateJour < $aujour->format('Y-m-d'));
          ?>
            <?php if ($passe): ?>
              <span class="cal-case cal-passe"><?= $jour ?></span>
            <?php else: ?>
              <input type="radio" name="date_session" id="cal<?= $dateJour ?>" value="<?= $dateJour ?>" class="cal-radio">
              <label for="cal<?= $dateJour ?>" class="cal-case"><?= $jour ?></label>
            <?php endif; ?>
          <?php endfor; ?>
        </div>
        <p class="cal-aide">Cliquez sur un jour disponible (sessions à 20h).</p>
      </div>
    </section>

    <!-- ============ SANTÉ ET SÉCURITÉ ============ -->
    <section class="concept-section">
      <h2 class="sec-title">SANTÉ ET SÉCURITÉ</h2>
      <p class="sec-sub">Votre santé et votre sécurité sont notre priorité !</p>

      <?php
      $questions = [
        'sante_cardiaque'    => 'Avez-vous des problèmes cardiaques ?',
        'sante_epilepsie'    => 'Souffrez-vous d\'épilepsie ou de sensibilité aux lumières stroboscopiques ?',
        'sante_respiratoire' => 'Avez-vous des difficultés respiratoires ou de l\'asthme ?',
        'sante_claustro'     => 'Souffrez-vous de claustrophobie ?',
      ];
      foreach ($questions as $nomQ => $libelle): ?>
        <div class="sante-q">
          <span class="sante-libelle"><?= $libelle ?></span>
          <div class="ouinon">
            <label><input type="radio" name="<?= $nomQ ?>" value="oui"> OUI</label>
            <label><input type="radio" name="<?= $nomQ ?>" value="non" checked> NON</label>
          </div>
        </div>
      <?php endforeach; ?>
    </section>

    <!-- ============ PAIEMENT ============ -->
    <section class="concept-section">
      <h2 class="sec-title">PAIEMENT</h2>
      <p class="sec-sub">Plus qu'une étape avant de plonger dans les Backrooms !</p>

      <div class="paiement-grid">
        <div class="pay-col">
          <h3 class="pay-titre">Je paye par carte dès maintenant</h3>
          <input class="resa-field" type="text" name="cb_numero"  placeholder="XXXX XXXX XXXX XXXX" inputmode="numeric" autocomplete="off">
          <div class="pay-ligne">
            <input class="resa-field" type="text" name="cb_exp" placeholder="MM / AA" autocomplete="off">
            <input class="resa-field" type="text" name="cb_cvv" placeholder="CVV" autocomplete="off">
          </div>
          <input class="resa-field" type="text" name="cb_tel" placeholder="TÉLÉPHONE" autocomplete="off">
          <button type="submit" name="paiement" value="carte" class="btn btn-primary pay-btn">PAYER</button>
        </div>

        <div class="pay-col">
          <h3 class="pay-titre">Je souhaite payer sur place</h3>
          <p class="pay-info">Réglez votre session directement à l'accueil le soir de l'événement.</p>
          <div class="pay-logos">
            <span>VISA</span><span>Mastercard</span><span>AMEX</span><span>ANCV</span><span> Pay</span>
          </div>
          <button type="submit" name="paiement" value="sur_place" class="btn btn-outline pay-btn">JE FINALISE</button>
        </div>
      </div>
      <p class="cal-aide" style="text-align:center;">Aucun débit réel n'est effectué : paiement de démonstration (projet étudiant).</p>
    </section>

  </form>

  <!-- Récapitulatif des réservations de l'équipe -->
  <?php if (!empty($reservations)): ?>
    <section class="concept-section">
      <h2 class="sec-title">MES RÉSERVATIONS</h2>
      <table class="tableau">
        <thead><tr><th>Salle</th><th>Date</th><th>Joueurs</th><th>Statut</th></tr></thead>
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

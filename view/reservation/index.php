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
// Mois minimum affichable : le mois en cours (on ne réserve pas dans le passé).
$moisMin   = max((new DateTime('today'))->format('Y-m'), '2026-06');

// Pré-remplissage : ce que l'utilisateur vient de saisir ($_POST) prime,
// sinon les infos de son compte. Ainsi, changer de mois ne perd RIEN.
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
    <section class="concept-section" style="padding-top:30px;">
      <h1 class="sec-title">INSCRIPTION</h1>
      <p class="sec-sub">Nous avons besoin de vos informations !</p>
      <?php if (!$estConnecte): ?>
        <p class="form-switch" style="margin:0 0 18px;">Déjà un compte&nbsp;? <a href="<?= BASE_URL ?>/compte/connexion">Connectez-vous</a></p>
      <?php endif; ?>

      <div class="resa-grid">
        <div class="resa-col">
          <input class="resa-field" type="text"  name="nom"       placeholder="NOM" aria-label="NOM"       value="<?= $champ('nom', $utilisateur['nom'] ?? '') ?>" required>
          <input class="resa-field" type="text"  name="prenom"    placeholder="PRÉNOM" aria-label="PRÉNOM"    value="<?= $champ('prenom', $utilisateur['prenom'] ?? '') ?>" required>
          <input class="resa-field" type="email" name="email"     placeholder="EMAIL" aria-label="EMAIL"     value="<?= $champ('email', $utilisateur['email'] ?? '') ?>" required>
          <input class="resa-field" type="text"  name="telephone" placeholder="TÉLÉPHONE" aria-label="TÉLÉPHONE" value="<?= $champ('telephone', $utilisateur['telephone'] ?? '') ?>">
        </div>
        <div class="resa-col">
          <input class="resa-field" type="text" name="nom_equipe" placeholder="NOM DE L'ÉQUIPE" aria-label="NOM DE L'ÉQUIPE"
                 value="<?= $equipe ? htmlspecialchars($equipe['nom']) : $champ('nom_equipe') ?>" <?= $equipe ? 'readonly' : '' ?>>
          <?php if (!$equipe): ?>
            <input class="resa-field" type="text" name="code_invite" maxlength="6"
                   placeholder="OU CODE D'INVITATION (rejoindre une équipe)" aria-label="OU CODE D'INVITATION (rejoindre une équipe)"
                   value="<?= $champ('code_invite') ?>" style="text-transform:uppercase;">
          <?php endif; ?>
          <input class="resa-field" type="number" name="nb_joueurs" placeholder="NOMBRE DE PARTICIPANTS (2 à 6)" aria-label="NOMBRE DE PARTICIPANTS (2 à 6)" min="2" max="6" value="<?= $champ('nb_joueurs') ?>" required>
          <select class="resa-field" name="salle" required aria-label="Salle">
            <option value="">CHOISIR UNE SALLE…</option>
            <option value="facile"   <?= ($_POST['salle'] ?? '') === 'facile'   ? 'selected' : '' ?>>Salle 1 — Le Niveau 0 (facile, dès 10 ans)</option>
            <option value="standard" <?= ($_POST['salle'] ?? '') === 'standard' ? 'selected' : '' ?>>Salle 2 — Les Couloirs jaunes (standard, dès 14 ans)</option>
            <option value="hardcore" <?= ($_POST['salle'] ?? '') === 'hardcore' ? 'selected' : '' ?>>Salle 3 — Le Niveau ! (hardcore, dès 16 ans)</option>
          </select>
          <label class="resa-dob-label">Date de naissance</label>
          <?php
            // Pré-remplissage : saisie en cours ($_POST) sinon la date déjà connue du compte.
            $dn  = !empty($utilisateur['date_naissance']) ? explode('-', $utilisateur['date_naissance']) : null; // [AAAA, MM, JJ]
            $dnJ = $_POST['naiss_jour']  ?? ($dn ? (int) $dn[2] : '');
            $dnM = $_POST['naiss_mois']  ?? ($dn ? (int) $dn[1] : '');
            $dnA = $_POST['naiss_annee'] ?? ($dn ? (int) $dn[0] : '');
          ?>
          <div class="resa-dob">
            <select class="resa-field" name="naiss_jour" aria-label="Jour">
              <option value="">Jour</option>
              <?php for ($j = 1; $j <= 31; $j++): ?><option <?= $dnJ == $j ? 'selected' : '' ?>><?= $j ?></option><?php endfor; ?>
            </select>
            <select class="resa-field" name="naiss_mois" aria-label="Mois">
              <option value="">Mois</option>
              <?php foreach ($moisFr as $n => $m): ?><option value="<?= $n ?>" <?= $dnM == $n ? 'selected' : '' ?>><?= ucfirst($m) ?></option><?php endforeach; ?>
            </select>
            <select class="resa-field" name="naiss_annee" aria-label="Année">
              <option value="">Année</option>
              <?php for ($a = 2010; $a >= 1940; $a--): ?><option <?= $dnA == $a ? 'selected' : '' ?>><?= $a ?></option><?php endfor; ?>
            </select>
          </div>
        </div>
      </div>
    </section>

    <!-- ============ NOS DISPONIBILITÉS (calendrier) ============ -->
    <section class="concept-section" id="disponibilites">
      <h2 class="sec-title">NOS DISPONIBILITÉS</h2>
      <p class="sec-sub">Venez jouer avec nous en <?= htmlspecialchars($nomMois) ?> !</p>

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
              <span class="cal-case cal-passe"><?= $jour ?></span>
            <?php elseif (in_array($dateJour, $joursComplets ?? [], true)): ?>
              <span class="cal-case cal-complet" title="Complet : les 3 salles sont réservées"><?= $jour ?></span>
            <?php else: ?>
              <input type="radio" name="date_session" id="cal<?= $dateJour ?>" value="<?= $dateJour ?>" class="cal-radio"
                     <?= ($_POST['date_session'] ?? '') === $dateJour ? 'checked' : '' ?>>
              <label for="cal<?= $dateJour ?>" class="cal-case"><?= $jour ?></label>
            <?php endif; ?>
          <?php endfor; ?>
        </div>
        <div class="cal-pagination">
          <?php if ($mois > $moisMin): ?>
            <!-- Boutons submit (et non liens) : la saisie du formulaire est conservée.
                 formnovalidate = ne pas bloquer sur les champs requis pendant la navigation. -->
            <button type="submit" name="mois_aff" value="<?= $precedent ?>" class="btn-link cal-btn"
                    formaction="<?= BASE_URL ?>/reservation#disponibilites" formnovalidate>&lsaquo; Mois précédent</button>
          <?php else: ?>
            <span></span>
          <?php endif; ?>
          <button type="submit" name="mois_aff" value="<?= $suivant ?>" class="btn-link cal-btn"
                  formaction="<?= BASE_URL ?>/reservation#disponibilites" formnovalidate>Passer au mois suivant &rsaquo;</button>
        </div>
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
            <label><input type="radio" name="<?= $nomQ ?>" value="oui" <?= ($_POST[$nomQ] ?? '') === 'oui' ? 'checked' : '' ?>> OUI</label>
            <label><input type="radio" name="<?= $nomQ ?>" value="non" <?= ($_POST[$nomQ] ?? '') === 'non' ? 'checked' : '' ?>> NON</label>
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
          <input class="resa-field" type="text" name="cb_numero"  placeholder="XXXX XXXX XXXX XXXX" aria-label="XXXX XXXX XXXX XXXX" inputmode="numeric" autocomplete="off" value="<?= $champ('cb_numero') ?>">
          <div class="pay-ligne">
            <input class="resa-field" type="text" name="cb_exp" placeholder="MM / AA" aria-label="MM / AA" autocomplete="off" value="<?= $champ('cb_exp') ?>">
            <input class="resa-field" type="text" name="cb_cvv" placeholder="CVV" aria-label="CVV" autocomplete="off" value="<?= $champ('cb_cvv') ?>">
          </div>
          <input class="resa-field" type="text" name="cb_tel" placeholder="TÉLÉPHONE" aria-label="TÉLÉPHONE" autocomplete="off" value="<?= $champ('cb_tel') ?>">
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

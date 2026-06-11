<main class="concept-page">
  <section class="concept-section" style="padding-top:30px;">
    <h1 class="sec-title">CLASSEMENT</h1>
    <p class="sec-sub">Les équipes qui ont survécu aux Backrooms… ou presque.</p>

    <?php if (empty($classement)): ?>
      <p>Aucune partie jouée pour le moment. Le classement apparaîtra après les premières sessions&nbsp;!</p>
      <div class="cta-bloc">
        <a href="<?= BASE_URL ?>/reservation" class="btn btn-primary">RÉSERVER UNE SESSION</a>
      </div>
    <?php else: ?>
      <table class="tableau classement-table">
        <thead>
          <tr><th>#</th><th>Équipe</th><th>Points</th><th>Meilleur temps</th><th>Parties</th><th>Sorties</th></tr>
        </thead>
        <tbody>
          <?php foreach ($classement as $i => $c):
            $rang = $i + 1;
            $medaille = [1 => '🥇', 2 => '🥈', 3 => '🥉'][$rang] ?? $rang;
          ?>
            <tr<?= $rang <= 3 ? ' class="podium"' : '' ?>>
              <td><?= $medaille ?></td>
              <td><?= htmlspecialchars($c['nom']) ?></td>
              <td><?= (int) $c['total_points'] ?> pts</td>
              <td><?= $c['meilleur_temps'] !== null ? floor($c['meilleur_temps'] / 60) . ' min ' . ($c['meilleur_temps'] % 60) . ' s' : '—' ?></td>
              <td><?= (int) $c['nb_parties'] ?></td>
              <td><?= (int) $c['nb_reussites'] ?> ✅</td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <div class="cta-bloc">
        <p>Votre équipe peut faire mieux&nbsp;?</p>
        <a href="<?= BASE_URL ?>/reservation" class="btn btn-primary">DÉFIER LES BACKROOMS</a>
      </div>
    <?php endif; ?>
  </section>
</main>

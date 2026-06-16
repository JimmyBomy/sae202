<main class="concept-page">
  <section class="concept-section pt30">
    <h1 class="sec-title"><?= t('cl_title') ?></h1>
    <p class="sec-sub"><?= t('cl_sub') ?></p>

    <?php if (empty($classement)): ?>
      <p><?= t('cl_empty') ?></p>
      <div class="cta-bloc">
        <a href="<?= BASE_URL ?>/reservation" class="btn btn-primary"><?= t('home_btn_reserver') ?></a>
      </div>
    <?php else: ?>
      <table class="tableau classement-table">
        <thead>
          <tr><th>#</th><th><?= t('cl_equipe') ?></th><th><?= t('cl_points') ?></th><th><?= t('cl_temps') ?></th><th><?= t('cl_parties') ?></th><th><?= t('cl_sorties') ?></th></tr>
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
        <p><?= t('cl_cta') ?></p>
        <a href="<?= BASE_URL ?>/reservation" class="btn btn-primary"><?= t('cl_cta_btn') ?></a>
      </div>
    <?php endif; ?>
  </section>
</main>

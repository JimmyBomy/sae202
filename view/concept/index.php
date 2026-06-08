<main class="container page-contenu">
  <section class="concept">
    <h1>Le Concept</h1>
    <p class="page-intro">
      Les <strong>Backrooms</strong> sont une légende urbaine : une dimension parallèle vide
      et angoissante où l'on tombe par un « bug de la réalité ». Notre escape game nocturne
      recrée cet univers d'<em>espaces liminaux</em> — des lieux de transition familiers mais
      désertés, à la lumière jaunâtre et au bourdonnement permanent.
    </p>

    <div class="cartes">
      <div class="carte">
        <h2>Une immersion totale</h2>
        <p>
          Décors construits sur mesure, jeux de lumière, nappe sonore oppressante et
          comédiens « entités » : tout est pensé pour brouiller vos repères dès le premier pas.
        </p>
      </div>
      <div class="carte">
        <h2>Une nuit entière</h2>
        <p>
          L'aventure démarre le soir (19h–20h) et se poursuit jusqu'au petit matin, avec
          <strong>4 heures de jeu effectif</strong> pour tenter de « sortir des Backrooms ».
        </p>
      </div>
      <div class="carte">
        <h2>En équipe</h2>
        <p>
          De 2 à 6 joueurs. La coopération, l'observation et le sang-froid sont vos seules
          armes face aux couloirs sans fin.
        </p>
      </div>
    </div>

    <h2 class="section-titre">Ils ont survécu… et témoignent</h2>
    <?php if (empty($avis)): ?>
      <p>Soyez les premiers à partager votre expérience après votre passage&nbsp;!</p>
    <?php else: ?>
      <div class="avis-liste">
        <?php foreach ($avis as $a): ?>
          <figure class="avis-card">
            <div class="avis-note"><?= str_repeat('★', (int)$a['note']) . str_repeat('☆', 5 - (int)$a['note']) ?></div>
            <blockquote><?= htmlspecialchars($a['texte']) ?></blockquote>
            <figcaption>— <?= htmlspecialchars($a['pseudo']) ?></figcaption>
          </figure>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <div class="cta-bloc">
      <a href="<?= BASE_URL ?>/reservation" class="btn btn-primary">RÉSERVER UNE SESSION</a>
    </div>
  </section>
</main>

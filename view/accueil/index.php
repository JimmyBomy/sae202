<?php
// On définit une classe spécifique pour le body de l'accueil afin de mettre le fond
$page_class = 'page-accueil';
?>
<main class="home-main">
  <div class="home-content">
    <h1 class="hero-title">BACKROOMS</h1>
    <p class="hero-text">
      Inspiré de la légende urbaine des Backrooms, une dimension parallèle angoissante accessible uniquement par un "bug de la réalité", notre concept exploite la psychologie des espaces liminaux : des lieux de transition vides, répétitifs et vaguement familiers, qui génèrent un sentiment d'isolement et d'étrangeté. L'expérience se déroule du soir (19h-20h) au lendemain matin, avec 4 heures de jeu effectif pour tenter de "sortir des Backrooms". Les décors immersifs et nos comédiens rendent cette expérience inoubliable !
    </p>
    
    <div class="hero-buttons">
      <a href="<?= BASE_URL ?>/reservation" class="btn btn-primary">RÉSERVER UNE SESSION</a>
      <a href="<?= BASE_URL ?>/concept" class="btn-link">EN SAVOIR PLUS &gt;</a>
    </div>

    <div class="hero-features">
      <a href="<?= BASE_URL ?>/concept" class="feature-item">
        <svg class="feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
          <path d="M4 22h16M6 2v20M14 2v20M14 2H6M10 12h1" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <span>ENTRER DANS<br>LES BACKROOMS &gt;</span>
      </a>
      
      <a href="<?= BASE_URL ?>/infos" class="feature-item">
        <svg class="feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
          <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <span>JOUEURS &gt;</span>
      </a>

      <a href="<?= BASE_URL ?>/regles" class="feature-item">
        <svg class="feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
          <path d="M19.428 15.428a2 2 0 0 0-1.022-.547l-2.387-.477a6 6 0 0 0-3.86.517l-.318.158a6 6 0 0 1-3.86.517L6.05 15.12a2 2 0 0 0-1.806.547M8 4h8l-1 1v5.172a2 2 0 0 0 .586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 0 0 9 10.172V5L8 4Z" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <span>RÈGLES &gt;</span>
      </a>
    </div>
  </div>
</main>
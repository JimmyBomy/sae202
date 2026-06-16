<main class="home-main">
  <div class="home-content">
    <h1 class="hero-title">BACKROOMS</h1>
    <p class="hero-text"><?= t('home_intro') ?></p>

    <div class="hero-buttons">
      <a href="<?= BASE_URL ?>/reservation" class="btn btn-primary"><?= t('home_btn_reserver') ?></a>
      <a href="<?= BASE_URL ?>/concept" class="btn-link"><?= t('home_btn_plus') ?></a>
    </div>

    <div class="hero-features">
      <a href="<?= BASE_URL ?>/concept" class="feature-item">
        <img class="feature-icon" src="<?= BASE_URL ?>/view/img/icone-porte.png" alt="">
        <span><?= t('feat_entrer') ?></span>
      </a>

      <a href="<?= BASE_URL ?>/infos" class="feature-item">
        <img class="feature-icon" src="<?= BASE_URL ?>/view/img/icone-joueurs.png" alt="">
        <span><?= t('feat_joueurs') ?></span>
      </a>

      <a href="<?= BASE_URL ?>/regles" class="feature-item">
        <img class="feature-icon" src="<?= BASE_URL ?>/view/img/icone-puzzle.png" alt="">
        <span><?= t('feat_regles') ?></span>
      </a>
    </div>
  </div>
</main>

<section class="home-video">
  <h2 class="sec-title"><?= t('video_titre') ?></h2>
  <p class="sec-sub"><?= t('video_sous') ?></p>
  <div class="video-wrap">
    <iframe src="https://www.youtube-nocookie.com/embed/e9Fs0jREKUw" title="Bande-annonce BACKROOMS"
            loading="lazy" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
  </div>
</section>
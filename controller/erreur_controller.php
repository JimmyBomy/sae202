<?php
// Page 404 thématisée : on "no-clip" hors de la réalité, comme dans le lore Backrooms.
function erreur_404() {
    $titrePage = 'Erreur 404';
    $page_class = 'page-concept';
    require_once('view/inc/header.php');
    ?>
    <main class="concept-page" style="text-align:center; padding-top:60px; padding-bottom:80px;">
      <h1 class="sec-title" style="font-size:5rem;">ERREUR 404</h1>
      <p class="sec-sub">Niveau inconnu</p>
      <p style="max-width:56ch; margin:0 auto 12px; color:#e3e3e3;">
        Vous venez de <strong>no-clip hors de la réalité</strong>…<br>
        Cette page n'existe pas — ou alors elle n'a jamais existé.
      </p>
      <p style="color:#9a9a8a; margin-bottom:34px;">Ne restez pas ici trop longtemps. <em>Il</em> rôde.</p>
      <a href="<?= BASE_URL ?>/" class="btn btn-primary">RETROUVER LA SORTIE</a>
    </main>
    <?php
    require_once('view/inc/footer.php');
}

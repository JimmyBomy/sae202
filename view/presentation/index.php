<?php
// Détection des médias présents (déposés dans view/uploads/), pour un rendu propre.
$introVid    = file_exists('view/uploads/intro.mp4')    ? BASE_URL . '/view/uploads/intro.mp4'    : null;
$discoursVid = file_exists('view/uploads/discours.mp4') ? BASE_URL . '/view/uploads/discours.mp4' : null;
$affiches = glob('view/uploads/affiches/*.{jpg,jpeg,png,webp}', GLOB_BRACE) ?: [];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex">
<title>Soutenance — BACKROOMS</title>
<link rel="icon" type="image/png" href="<?= BASE_URL ?>/view/img/favicon.png">
<style>
  @font-face{font-family:'VT323';src:url('<?= BASE_URL ?>/view/fonts/vt323-400.woff2') format('woff2');font-display:swap;}
  *{box-sizing:border-box;margin:0;padding:0;}
  body{min-height:100vh;font-family:'Segoe UI',Arial,sans-serif;color:#f5f5f0;padding:34px 20px 60px;
    background:#14130d;background-image:linear-gradient(rgba(13,12,9,.82),rgba(13,12,9,.9)),
      repeating-linear-gradient(0deg,#181712 0 2px,#14130d 2px 4px);}
  .wrap{max-width:1000px;margin:0 auto;}
  h1{font-family:'VT323',monospace;color:#d1b023;font-size:clamp(2.4rem,8vw,4rem);letter-spacing:2px;text-align:center;line-height:1;}
  .lede{text-align:center;color:#bdbdb0;margin:6px 0 30px;}
  h2{font-family:'VT323',monospace;color:#d1b023;font-size:1.9rem;letter-spacing:1px;margin:34px 0 14px;border-bottom:1px solid #3a3320;padding-bottom:6px;}
  video{width:100%;border-radius:10px;border:1px solid #3a3320;background:#000;display:block;}
  .ph{border:1px dashed #4a4636;border-radius:10px;padding:26px;text-align:center;color:#8f8f88;background:rgba(10,10,8,.4);}
  .affiches{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:16px;}
  .affiches img{width:100%;border-radius:8px;border:1px solid #3a3320;}
  .actions{display:flex;gap:16px;flex-wrap:wrap;justify-content:center;margin-top:40px;}
  .btn{font-family:'VT323',monospace;font-size:1.6rem;letter-spacing:1px;background:#d1b023;color:#14130d;border:none;
    border-radius:8px;padding:14px 34px;cursor:pointer;text-decoration:none;display:inline-block;}
  .btn:hover{background:#e3c63a;}
  .btn-ghost{background:none;color:#e6e6e6;border:1px solid #4a4636;}
  .btn-ghost:hover{border-color:#d1b023;color:#d1b023;}
</style>
</head>
<body>
  <div class="wrap">
    <h1>BACKROOMS — SOUTENANCE</h1>
    <p class="lede">Page de présentation (non listée). À n'ouvrir que pendant le passage oral.</p>

    <h2>🎬 Vidéo d'introduction</h2>
    <?php if ($introVid): ?>
      <video src="<?= $introVid ?>" controls preload="metadata" playsinline></video>
    <?php else: ?>
      <div class="ph">Déposez la vidéo dans <code>view/uploads/intro.mp4</code> — elle s'affichera ici.</div>
    <?php endif; ?>

    <h2>🔇 Vidéo d'accompagnement (sans son)</h2>
    <?php if ($discoursVid): ?>
      <video src="<?= $discoursVid ?>" muted loop autoplay playsinline></video>
    <?php else: ?>
      <div class="ph">Vidéo muette à boucler pendant le discours — déposez-la dans <code>view/uploads/discours.mp4</code>.</div>
    <?php endif; ?>

    <h2>🖼️ Affiches & flyers</h2>
    <?php if ($affiches): ?>
      <div class="affiches">
        <?php foreach ($affiches as $a): ?><img src="<?= BASE_URL . '/' . htmlspecialchars($a) ?>" alt="Affiche" loading="lazy"><?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="ph">Déposez les visuels dans <code>view/uploads/affiches/</code> (jpg, png, webp) — ils apparaîtront ici.</div>
    <?php endif; ?>

    <div class="actions">
      <a class="btn" href="<?= BASE_URL ?>/survie">🎮 Lancer le mini-jeu</a>
      <a class="btn btn-ghost" href="<?= BASE_URL ?>/">← Retour à l'accueil</a>
    </div>
  </div>
</body>
</html>

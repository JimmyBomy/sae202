<?php
// Fonction robuste pour scanner un dossier en ignorant la casse (majuscule/minuscule)
function getImagesFromDir($dir) {
    if (!is_dir($dir)) return [];
    $files = glob($dir . '/*.*') ?: [];
    // Filtre pour ne garder que les images (insensible à la casse grâce au "i" à la fin)
    return array_filter($files, function($file) {
        return preg_match('/\.(jpg|jpeg|png|webp|svg|gif)$/i', $file);
    });
}

// Détection des médias présents
$introVid    = file_exists('view/uploads/intro.mp4')    ? BASE_URL . '/view/uploads/intro.mp4'    : null;
$discoursVid = file_exists('view/uploads/discours.mp4') ? BASE_URL . '/view/uploads/discours.mp4' : null;

// Utilisation de notre nouvelle fonction
$affiches = getImagesFromDir('view/uploads/affiches');
$qrcodes  = getImagesFromDir('view/uploads/qrcode');
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
  video{display:block;margin:0 auto;width:auto;max-width:100%;max-height:72vh;border-radius:10px;border:1px solid #3a3320;background:#000;cursor:zoom-in;}
  .ph{border:1px dashed #4a4636;border-radius:10px;padding:26px;text-align:center;color:#8f8f88;background:rgba(10,10,8,.4);}
  
  /* Nouveau CSS pour des affiches/QR Codes propres et non zoomés */
  .affiches, .qrcodes {display:flex;flex-wrap:wrap;justify-content:center;gap:24px;}
  .affiches img, .qrcodes img {
    height:auto;
    width:auto;
    max-height:min(320px,55vh);
    max-width:100%;
    border-radius:8px;
    border:1px solid #3a3320;
    cursor:zoom-in;
    object-fit:contain;
  }
  
  /* Styles spécifiques quand les images passent en plein écran */
  .affiches img:fullscreen, .qrcodes img:fullscreen {height:100%;width:100%;background:#000;border:none;}
  .affiches img:-webkit-full-screen, .qrcodes img:-webkit-full-screen {height:100%;width:100%;background:#000;border:none;}
  .affiches img:-moz-full-screen, .qrcodes img:-moz-full-screen {height:100%;width:100%;background:#000;border:none;}

  .actions{display:flex;gap:16px;flex-wrap:wrap;justify-content:center;margin-top:40px;}
  .btn{font-family:'VT323',monospace;font-size:1.6rem;letter-spacing:1px;background:#d1b023;color:#14130d;border:none;
    border-radius:8px;padding:14px 34px;cursor:pointer;text-decoration:none;display:inline-block;}
  .btn:hover{background:#e3c63a;}
  .btn-ghost{background:none;color:#e6e6e6;border:1px solid #4a4636;}
  .btn-ghost:hover{border-color:#d1b023;color:#d1b023;}
  .reveal-zone{text-align:center;}
  #vainqueur{margin-top:18px;font-family:'VT323',monospace;animation:pop .55s ease;}
  #vainqueur .nom{display:block;color:#d1b023;font-size:clamp(2.6rem,9vw,5rem);line-height:1;text-shadow:0 0 18px rgba(209,176,35,.5);}
  #vainqueur .det{display:block;color:#7ee2a8;font-size:1.5rem;margin-top:6px;}
  @keyframes pop{0%{transform:scale(.6);opacity:0}60%{transform:scale(1.08)}100%{transform:scale(1);opacity:1}}
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
    <?php if (!empty($affiches)): ?>
      <div class="affiches">
        <?php foreach ($affiches as $a): ?><img src="<?= BASE_URL . '/' . htmlspecialchars($a) ?>" alt="Affiche" loading="lazy"><?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="ph">Déposez les visuels dans <code>view/uploads/affiches/</code> (jpg, png, webp) — ils apparaîtront ici.</div>
    <?php endif; ?>

    <h2>📱 QR CODE</h2>
    <?php if (!empty($qrcodes)): ?>
      <div class="qrcodes">
        <?php foreach ($qrcodes as $q): ?><img src="<?= BASE_URL . '/' . htmlspecialchars($q) ?>" alt="QR Code" loading="lazy"><?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="ph">Déposez le QR Code dans <code>view/uploads/qrcode/</code> (jpg, png, svg) — il apparaîtra ici.</div>
    <?php endif; ?>

    <h2>🏆 Le grand gagnant</h2>
    <div class="reveal-zone">
      <button class="btn" id="btn-reveal" onclick="revealVainqueur()">Révéler le vainqueur</button>
      <div id="vainqueur" class="ph hidden" style="border:none;background:none;"></div>
    </div>

    <div class="actions">
      <a class="btn" href="<?= BASE_URL ?>/survie">🎮 Lancer le mini-jeu</a>
      <a class="btn btn-ghost" href="<?= BASE_URL ?>/">← Retour à l'accueil</a>
    </div>
  </div>

  <script>
    // Fonction de bascule (toggle) plein écran couvrant les 4 supports (Standard, WebKit, Moz, MS)
    function toggleFullScreen(elem) {
      const isFullScreen = document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement;
      
      if (!isFullScreen) {
        if (elem.requestFullscreen) { elem.requestFullscreen(); } 
        else if (elem.webkitRequestFullscreen) { elem.webkitRequestFullscreen(); } 
        else if (elem.mozRequestFullScreen) { elem.mozRequestFullScreen(); } 
        else if (elem.msRequestFullscreen) { elem.msRequestFullscreen(); }
      } else {
        if (document.exitFullscreen) { document.exitFullscreen(); } 
        else if (document.webkitExitFullscreen) { document.webkitExitFullscreen(); } 
        else if (document.mozCancelFullScreen) { document.mozCancelFullScreen(); } 
        else if (document.msExitFullscreen) { document.msExitFullscreen(); }
      }
    }

    // Plein écran au clic UNIQUEMENT sur les images (la vidéo garde sa barre de contrôle native, propre).
    document.querySelectorAll('.affiches img, .qrcodes img').forEach(media => {
      media.addEventListener('click', function(){ toggleFullScreen(this); });
    });

    // Révélation du vainqueur (1er du classement du mini-jeu).
    function revealVainqueur(){
      fetch('<?= BASE_URL ?>/survie/classement').then(r=>r.json()).then(rows=>{
        const v=document.getElementById('vainqueur');
        if(!rows.length){ v.innerHTML='<span class="det">Aucun score pour le moment…</span>'; }
        else{ const w=rows[0];
          v.innerHTML='🏆<span class="nom">'+w.pseudo+'</span>'+
            '<span class="det">'+w.score+' pts · '+w.niveau+' portes</span>'+
            '<span class="det" style="color:#d1b023">gagne 4 places en avant-première !</span>'; }
        v.classList.remove('hidden');
        document.getElementById('btn-reveal').classList.add('hidden');
      }).catch(()=>{});
    }
    window.revealVainqueur=revealVainqueur;
  </script>
</body>
</html>
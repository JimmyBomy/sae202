<?php
// Scan d'un dossier (images, insensible à la casse).
function getImagesFromDir($dir) {
    if (!is_dir($dir)) return [];
    $files = glob($dir . '/*.*') ?: [];
    return array_filter($files, function($file) {
        return preg_match('/\.(jpg|jpeg|png|webp|svg|gif)$/i', $file);
    });
}
$introVid    = file_exists('view/uploads/intro.mp4')    ? BASE_URL . '/view/uploads/intro.mp4'    : null;
$discoursVid = file_exists('view/uploads/discours.mp4') ? BASE_URL . '/view/uploads/discours.mp4' : null;
$affiches = getImagesFromDir('view/uploads/affiches');
$qrcodes  = getImagesFromDir('view/uploads/qrcode');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex">
<title>BACKROOMS</title>
<link rel="icon" type="image/png" href="<?= BASE_URL ?>/view/img/favicon.png">
<style>
  @font-face{font-family:'VT323';src:url('<?= BASE_URL ?>/view/fonts/vt323-400.woff2') format('woff2');font-display:swap;}
  *{box-sizing:border-box;margin:0;padding:0;}
  body{min-height:100vh;font-family:'Segoe UI',Arial,sans-serif;color:#f5f5f0;padding:40px 20px 60px;
    background:#14130d;background-image:linear-gradient(rgba(13,12,9,.82),rgba(13,12,9,.9)),
      repeating-linear-gradient(0deg,#181712 0 2px,#14130d 2px 4px);}
  .wrap{max-width:1000px;margin:0 auto;}
  .hidden{display:none!important;}
  h1{font-family:'VT323',monospace;color:#d1b023;font-size:clamp(2.8rem,9vw,4.6rem);letter-spacing:3px;text-align:center;line-height:1;margin-bottom:10px;}
  h2{font-family:'VT323',monospace;color:#d1b023;font-size:1.9rem;letter-spacing:1px;margin:40px 0 16px;border-bottom:1px solid #3a3320;padding-bottom:6px;}
  video{display:block;margin:0 auto;width:auto;max-width:100%;max-height:74vh;border-radius:10px;border:1px solid #3a3320;background:#000;cursor:zoom-in;}
  video:fullscreen{max-height:none;width:100%;height:100%;object-fit:contain;}
  .media{display:flex;flex-wrap:wrap;justify-content:center;gap:24px;}
  .media img{height:auto;width:auto;max-height:min(360px,60vh);max-width:100%;border-radius:8px;border:1px solid #3a3320;cursor:zoom-in;object-fit:contain;}
  .media img:fullscreen{height:100%;width:100%;background:#000;border:none;}
  .qrcodes.flou img{filter:blur(26px);}
  .qrcodes img{transition:filter .5s;}
  .reveal-zone{text-align:center;}
  .btn{font-family:'VT323',monospace;font-size:1.6rem;letter-spacing:1px;background:#d1b023;color:#14130d;border:none;
    border-radius:8px;padding:14px 34px;cursor:pointer;text-decoration:none;display:inline-block;}
  .btn:hover{background:#e3c63a;}
  .btn-ghost{background:none;color:#e6e6e6;border:1px solid #4a4636;}
  .btn-ghost:hover{border-color:#d1b023;color:#d1b023;}
  .actions{display:flex;gap:16px;flex-wrap:wrap;justify-content:center;margin-top:48px;}
  #vainqueur{margin-top:20px;font-family:'VT323',monospace;animation:pop .55s ease;}
  #vainqueur .nom{display:block;color:#d1b023;font-size:clamp(2.8rem,10vw,5.5rem);line-height:1;text-shadow:0 0 18px rgba(209,176,35,.5);}
  #vainqueur .det{display:block;color:#7ee2a8;font-size:1.5rem;margin-top:6px;}
  @keyframes pop{0%{transform:scale(.6);opacity:0}60%{transform:scale(1.08)}100%{transform:scale(1);opacity:1}}
</style>
</head>
<body>
  <div class="wrap">
    <h1>BACKROOMS</h1>

    <?php if ($introVid): ?>
      <h2>Bande-annonce</h2>
      <video src="<?= $introVid ?>" preload="metadata" playsinline></video>
    <?php endif; ?>

    <?php if ($discoursVid): ?>
      <h2>Ambiance</h2>
      <video src="<?= $discoursVid ?>" muted loop autoplay playsinline></video>
    <?php endif; ?>

    <?php if (!empty($affiches)): ?>
      <h2>Affiches</h2>
      <div class="media">
        <?php foreach ($affiches as $a): ?><img src="<?= BASE_URL . '/' . htmlspecialchars($a) ?>" alt="Affiche" loading="lazy"><?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($qrcodes)): ?>
      <h2>QR code</h2>
      <div class="reveal-zone">
        <button class="btn" id="btn-qr" onclick="revealQR()">Afficher le QR code</button>
        <div class="media qrcodes flou">
          <?php foreach ($qrcodes as $q): ?><img src="<?= BASE_URL . '/' . htmlspecialchars($q) ?>" alt="QR code" loading="lazy"><?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

    <h2>Le grand gagnant</h2>
    <div class="reveal-zone">
      <button class="btn" id="btn-reveal" onclick="revealVainqueur()">Révéler le vainqueur</button>
      <div id="vainqueur" class="hidden"></div>
    </div>

    <div class="actions">
      <a class="btn btn-ghost" href="<?= BASE_URL ?>/">Retour à l'accueil</a>
    </div>
  </div>

  <script>
    // Masque l'URL réelle dans la barre d'adresse (illisible pour le public qui voit l'écran).
    try{ history.replaceState(null,'','<?= BASE_URL ?>/'); }catch(e){}

    function toggleFullScreen(elem){
      const fs = document.fullscreenElement||document.webkitFullscreenElement||document.mozFullScreenElement||document.msFullscreenElement;
      if(!fs){ (elem.requestFullscreen||elem.webkitRequestFullscreen||elem.mozRequestFullScreen||elem.msRequestFullscreen).call(elem); }
      else{ (document.exitFullscreen||document.webkitExitFullscreen||document.mozCancelFullScreen||document.msExitFullscreen).call(document); }
    }

    // Vidéos : pas de barre de contrôle, mais CLIC = plein écran + lecture (clic de nouveau = pause + sortie).
    document.querySelectorAll('video, .media img').forEach(m=>{
      m.addEventListener('click', function(){
        if(this.tagName==='VIDEO'){
          const fs=document.fullscreenElement||document.webkitFullscreenElement;
          if(fs){ this.pause(); } else { this.play().catch(()=>{}); }
        }
        toggleFullScreen(this);
      });
    });

    function revealQR(){
      const z=document.querySelector('.qrcodes'); if(z) z.classList.remove('flou');
      const b=document.getElementById('btn-qr'); if(b) b.classList.add('hidden');
    }
    window.revealQR=revealQR;

    function revealVainqueur(){
      fetch('<?= BASE_URL ?>/survie/classement').then(r=>r.json()).then(rows=>{
        const v=document.getElementById('vainqueur');
        if(!rows.length){ v.innerHTML='<span class="det">Aucun score pour le moment</span>'; }
        else{ const w=rows[0];
          v.innerHTML='<span class="nom">'+w.pseudo+'</span>'+
            '<span class="det">'+w.score+' pts · '+w.niveau+' portes</span>'+
            '<span class="det" style="color:#d1b023">gagne 4 places en avant-première</span>'; }
        v.classList.remove('hidden');
        document.getElementById('btn-reveal').classList.add('hidden');
      }).catch(()=>{});
    }
    window.revealVainqueur=revealVainqueur;
  </script>
</body>
</html>

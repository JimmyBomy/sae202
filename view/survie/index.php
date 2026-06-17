<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex">
<title>SURVIE — Niveau 0 · BACKROOMS</title>
<link rel="icon" type="image/png" href="<?= BASE_URL ?>/view/img/favicon.png">
<style>
  @font-face{font-family:'VT323';src:url('<?= BASE_URL ?>/view/fonts/vt323-400.woff2') format('woff2');font-display:swap;}
  *{box-sizing:border-box;margin:0;padding:0;}
  body{min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:18px;
    font-family:'Segoe UI',Arial,sans-serif;color:#f5f5f0;text-align:center;padding:20px;
    background:#14130d;background-image:linear-gradient(rgba(13,12,9,.82),rgba(13,12,9,.9)),
      repeating-linear-gradient(0deg,#181712 0 2px,#14130d 2px 4px);}
  h1{font-family:'VT323',monospace;color:#d1b023;font-size:clamp(2.2rem,9vw,4rem);line-height:1;letter-spacing:2px;}
  .sub{color:#bdbdb0;max-width:520px;font-size:.95rem;line-height:1.5;}
  .hud{display:flex;gap:26px;font-family:'VT323',monospace;font-size:1.6rem;color:#d1b023;}
  .hud b{color:#fff;}
  .barre{width:min(420px,90vw);height:12px;background:#2a2820;border-radius:6px;overflow:hidden;border:1px solid #3a3320;}
  .barre span{display:block;height:100%;width:100%;background:#d1b023;transition:width .1s linear;}
  .grille{display:grid;gap:12px;width:min(620px,92vw);}
  .porte{aspect-ratio:3/4;border:none;border-radius:8px;cursor:pointer;background-size:cover;background-position:center;
    background-image:url('<?= BASE_URL ?>/view/img/cal-ferme.webp');transition:transform .08s,box-shadow .12s;}
  .porte:hover{transform:translateY(-3px);box-shadow:0 0 14px rgba(209,176,35,.35);}
  .porte.ouverte{background-image:url('<?= BASE_URL ?>/view/img/cal-ouvert.webp');box-shadow:0 0 18px rgba(209,176,35,.5);}
  .btn{font-family:'VT323',monospace;font-size:1.5rem;letter-spacing:1px;background:#d1b023;color:#14130d;border:none;
    border-radius:6px;padding:10px 28px;cursor:pointer;text-decoration:none;display:inline-block;}
  .btn:hover{background:#e3c63a;}
  .btn-ghost{background:none;color:#9a9a8a;border:1px solid #3a3320;}
  .ecran{display:flex;flex-direction:column;align-items:center;gap:18px;}
  .hidden{display:none;}
  .liens{display:flex;gap:14px;flex-wrap:wrap;justify-content:center;margin-top:6px;}
  .msg{font-family:'VT323',monospace;font-size:2rem;color:#ff8a7a;}
  .msg.win{color:#7ee2a8;}
</style>
</head>
<body>

  <!-- Écran d'accueil -->
  <div id="start" class="ecran">
    <h1>SURVIE — NIVEAU 0</h1>
    <p class="sub">Vous êtes perdu·e dans les Backrooms. Une seule porte est <strong style="color:#d1b023">ouverte</strong> à chaque palier&nbsp;:
       trouvez-la avant que le temps ne s'écoule. Une porte fermée = une entité vous attrape. Atteignez le niveau 12 pour vous échapper.</p>
    <button class="btn" onclick="demarrer()">ENTRER</button>
    <a class="btn btn-ghost" href="<?= BASE_URL ?>/">← Retour à l'accueil</a>
  </div>

  <!-- Jeu -->
  <div id="jeu" class="ecran hidden">
    <div class="hud"><span>NIVEAU <b id="niveau">1</b></span><span>SCORE <b id="score">0</b></span></div>
    <div class="barre"><span id="barre"></span></div>
    <div class="grille" id="grille"></div>
  </div>

  <!-- Fin -->
  <div id="fin" class="ecran hidden">
    <p class="msg" id="msg-fin"></p>
    <div class="hud"><span>SCORE FINAL <b id="score-fin">0</b></span></div>
    <div class="liens">
      <button class="btn" onclick="demarrer()">REJOUER</button>
      <a class="btn btn-ghost" href="<?= BASE_URL ?>/">← Accueil</a>
    </div>
  </div>

<script>
(function(){
  const $=id=>document.getElementById(id);
  let niveau,score,tempsMax,timer,deadline;
  const NIVEAU_MAX=12;

  window.demarrer=function(){
    niveau=1;score=0;
    $('start').classList.add('hidden');$('fin').classList.add('hidden');$('jeu').classList.remove('hidden');
    palier();
  };

  function palier(){
    $('niveau').textContent=niveau;$('score').textContent=score;
    const nb=Math.min(4+niveau,16);                 // de plus en plus de portes
    const cols=Math.ceil(Math.sqrt(nb));
    const g=$('grille');g.style.gridTemplateColumns='repeat('+cols+',1fr)';g.innerHTML='';
    const ouverte=Math.floor(Math.random()*nb);
    for(let i=0;i<nb;i++){
      const p=document.createElement('button');p.className='porte';
      if(i===ouverte){p.classList.add('ouverte');p.onclick=reussi;}
      else{p.onclick=()=>perdu("Une entité vous a attrapé…");}
      g.appendChild(p);
    }
    tempsMax=Math.max(1100,3200-niveau*180);        // de moins en moins de temps
    deadline=Date.now()+tempsMax;
    clearInterval(timer);
    timer=setInterval(tick,50);
  }

  function tick(){
    const reste=deadline-Date.now();
    $('barre').style.width=Math.max(0,reste/tempsMax*100)+'%';
    if(reste<=0){clearInterval(timer);perdu("Le temps vous a rattrapé…");}
  }

  function reussi(){
    clearInterval(timer);
    score+=niveau*10;
    if(niveau>=NIVEAU_MAX){return gagne();}
    niveau++;palier();
  }

  function perdu(texte){
    clearInterval(timer);
    $('msg-fin').textContent=texte;$('msg-fin').classList.remove('win');
    terminer();
  }
  function gagne(){
    $('msg-fin').textContent="VOUS VOUS ÊTES ÉCHAPPÉ DES BACKROOMS !";$('msg-fin').classList.add('win');
    terminer();
  }
  function terminer(){
    $('score-fin').textContent=score;
    $('jeu').classList.add('hidden');$('fin').classList.remove('hidden');
  }
})();
</script>
</body>
</html>

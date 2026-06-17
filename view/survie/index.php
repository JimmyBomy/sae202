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
  body{min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:16px;
    font-family:'Segoe UI',Arial,sans-serif;color:#f5f5f0;text-align:center;padding:20px;overflow-x:hidden;
    background:#14130d;background-image:linear-gradient(rgba(13,12,9,.82),rgba(13,12,9,.9)),
      repeating-linear-gradient(0deg,#181712 0 2px,#14130d 2px 4px);}
  body::before{content:"";position:fixed;inset:0;pointer-events:none;z-index:5;
    background:radial-gradient(ellipse at center,transparent 55%,rgba(0,0,0,.55) 100%);animation:flicker 6s infinite;}
  @keyframes flicker{0%,97%,100%{opacity:1}98%{opacity:.7}99%{opacity:.9}}
  body.shake{animation:shake .4s;}
  @keyframes shake{0%,100%{transform:translate(0,0)}20%{transform:translate(-8px,4px)}40%{transform:translate(8px,-4px)}60%{transform:translate(-6px,-3px)}80%{transform:translate(6px,3px)}}
  body.flash{background-color:#1c3a1c;}
  h1{font-family:'VT323',monospace;color:#d1b023;font-size:clamp(2.2rem,9vw,4rem);line-height:1;letter-spacing:2px;}
  .sub{color:#bdbdb0;max-width:540px;font-size:.95rem;line-height:1.5;}
  .amb{color:#9a9a8a;font-style:italic;min-height:1.2em;font-size:.9rem;}
  .hud{display:flex;gap:22px;align-items:center;font-family:'VT323',monospace;font-size:1.6rem;color:#d1b023;flex-wrap:wrap;justify-content:center;}
  .hud b{color:#fff;}
  .vies{letter-spacing:2px;font-size:1.4rem;}
  .barre{width:min(440px,92vw);height:12px;background:#2a2820;border-radius:6px;overflow:hidden;border:1px solid #3a3320;}
  .barre span{display:block;height:100%;width:100%;background:#d1b023;}
  .barre span.urgent{background:#c0392b;}
  .grille{display:grid;gap:12px;width:min(620px,94vw);}
  .porte{position:relative;aspect-ratio:3/4;border:none;border-radius:8px;cursor:pointer;background-size:cover;background-position:center;
    background-image:url('<?= BASE_URL ?>/view/img/cal-ferme.webp');transition:transform .08s,box-shadow .12s;}
  .porte:hover{transform:translateY(-3px);box-shadow:0 0 14px rgba(209,176,35,.35);}
  .porte.ouverte{background-image:url('<?= BASE_URL ?>/view/img/cal-ouvert.webp');box-shadow:0 0 20px rgba(209,176,35,.55);}
  .porte.entite{box-shadow:0 0 18px rgba(192,57,43,.6);}
  .porte.entite::after{content:"👁";position:absolute;inset:0;display:flex;align-items:center;justify-content:center;
    font-size:2rem;background:rgba(120,15,15,.5);border-radius:8px;animation:eye 1.1s infinite;}
  @keyframes eye{0%,100%{opacity:.55}50%{opacity:1}}
  .btn{font-family:'VT323',monospace;font-size:1.5rem;letter-spacing:1px;background:#d1b023;color:#14130d;border:none;
    border-radius:6px;padding:10px 28px;cursor:pointer;text-decoration:none;display:inline-block;}
  .btn:hover{background:#e3c63a;}
  .btn-ghost{background:none;color:#9a9a8a;border:1px solid #3a3320;}
  .ecran{display:flex;flex-direction:column;align-items:center;gap:16px;position:relative;z-index:6;}
  .hidden{display:none;}
  .liens{display:flex;gap:14px;flex-wrap:wrap;justify-content:center;margin-top:6px;}
  .msg{font-family:'VT323',monospace;font-size:2.1rem;color:#ff8a7a;}
  .msg.win{color:#7ee2a8;}
  .hs{color:#d1b023;font-family:'VT323',monospace;font-size:1.4rem;}
  #son{position:fixed;top:14px;right:16px;z-index:7;background:none;border:1px solid #3a3320;color:#d1b023;
    border-radius:6px;padding:6px 10px;font-size:1.1rem;cursor:pointer;}
</style>
</head>
<body>
  <button id="son" onclick="toggleSon()" title="Son">🔊</button>

  <!-- Accueil -->
  <div id="start" class="ecran">
    <h1>SURVIE — NIVEAU 0</h1>
    <p class="sub">Perdu·e dans les Backrooms. À chaque palier, <strong style="color:#d1b023">une seule porte est ouverte</strong> :
       trouve-la avant la fin du chrono. Une porte fermée te coûte une vie ; derrière une porte marquée
       <span style="color:#ff8a7a">👁</span> rôde une <strong style="color:#ff8a7a">entité</strong> — n'y touche pas.
       <br>3 vies. Atteins le <strong>niveau 12</strong> pour t'échapper.</p>
    <p class="hs" id="hs-start"></p>
    <button class="btn" onclick="demarrer()">ENTRER</button>
    <a class="btn btn-ghost" href="<?= BASE_URL ?>/">← Retour à l'accueil</a>
  </div>

  <!-- Jeu -->
  <div id="jeu" class="ecran hidden">
    <div class="hud">
      <span>NIVEAU <b id="niveau">1</b></span>
      <span>SCORE <b id="score">0</b></span>
      <span class="vies" id="vies">❤️❤️❤️</span>
    </div>
    <p class="amb" id="amb"></p>
    <div class="barre"><span id="barre"></span></div>
    <div class="grille" id="grille"></div>
  </div>

  <!-- Fin -->
  <div id="fin" class="ecran hidden">
    <p class="msg" id="msg-fin"></p>
    <div class="hud"><span>SCORE <b id="score-fin">0</b></span></div>
    <p class="hs" id="hs-fin"></p>
    <div class="liens">
      <button class="btn" onclick="demarrer()">REJOUER</button>
      <a class="btn btn-ghost" href="<?= BASE_URL ?>/presentation">← Présentation</a>
      <a class="btn btn-ghost" href="<?= BASE_URL ?>/">Accueil</a>
    </div>
  </div>

<script>
(function(){
  const $=id=>document.getElementById(id);
  const NIVEAU_MAX=12;
  const AMBIANCES=["Les néons bourdonnent…","La moquette est humide.","Quelque chose respire derrière toi.",
    "Le couloir n'en finit pas.","Ne fais aucun bruit.","Une odeur d'amande flotte dans l'air.",
    "Les murs se rapprochent.","Tu n'es plus seul·e.","Reste concentré·e.","Presque la sortie…","Cours.","La lumière vacille."];
  let niveau,score,vies,tempsMax,deadline,timer;
  let hs=parseInt(localStorage.getItem('backrooms_hs')||'0',10);
  let soundOn=localStorage.getItem('backrooms_son')!=='off';

  // --- Son synthétisé (sans fichier) ---
  let actx;
  function tone(freq,dur,type='square',vol=.15){
    if(!soundOn)return;
    try{actx=actx||new (window.AudioContext||window.webkitAudioContext)();
      const o=actx.createOscillator(),g=actx.createGain();
      o.type=type;o.frequency.value=freq;o.connect(g);g.connect(actx.destination);
      g.gain.setValueAtTime(vol,actx.currentTime);
      g.gain.exponentialRampToValueAtTime(.0001,actx.currentTime+dur);
      o.start();o.stop(actx.currentTime+dur);
    }catch(e){}
  }
  const sOk=()=>{tone(660,.08);setTimeout(()=>tone(990,.12),70);};
  const sBad=()=>tone(140,.3,'sawtooth',.2);
  const sDead=()=>{tone(200,.2,'sawtooth',.2);setTimeout(()=>tone(90,.5,'sawtooth',.22),120);};
  const sWin=()=>{[523,659,784,1047].forEach((f,i)=>setTimeout(()=>tone(f,.18),i*120));};
  window.toggleSon=function(){soundOn=!soundOn;localStorage.setItem('backrooms_son',soundOn?'on':'off');$('son').textContent=soundOn?'🔊':'🔇';};

  function majHS(){$('son').textContent=soundOn?'🔊':'🔇';
    $('hs-start').textContent=hs?('Meilleur score : '+hs):'';}
  majHS();

  window.demarrer=function(){
    niveau=1;score=0;vies=3;
    $('start').classList.add('hidden');$('fin').classList.add('hidden');$('jeu').classList.remove('hidden');
    palier();
  };

  function palier(){
    $('niveau').textContent=niveau;$('score').textContent=score;
    $('vies').textContent='❤️'.repeat(vies)+'🖤'.repeat(3-vies);
    $('amb').textContent=AMBIANCES[(niveau-1)%AMBIANCES.length];
    const nb=Math.min(4+niveau,16);
    const cols=Math.ceil(Math.sqrt(nb));
    const g=$('grille');g.style.gridTemplateColumns='repeat('+cols+',1fr)';g.innerHTML='';
    const ouverte=Math.floor(Math.random()*nb);
    // entités (à partir du niveau 3), jamais sur la porte ouverte
    const nbEnt=niveau<3?0:Math.min(Math.floor(niveau/3),Math.floor((nb-1)/2));
    const ent=new Set();
    while(ent.size<nbEnt){const r=Math.floor(Math.random()*nb);if(r!==ouverte)ent.add(r);}
    for(let i=0;i<nb;i++){
      const p=document.createElement('button');p.className='porte';p.type='button';
      if(i===ouverte){p.classList.add('ouverte');p.onclick=reussi;}
      else if(ent.has(i)){p.classList.add('entite');p.onclick=()=>mort("Une entité vous a dévoré…");}
      else{p.onclick=()=>erreur();}
      g.appendChild(p);
    }
    tempsMax=Math.max(1100,3200-niveau*180);
    deadline=Date.now()+tempsMax;
    clearInterval(timer);timer=setInterval(tick,50);
  }

  function tick(){
    const reste=deadline-Date.now();
    const pct=Math.max(0,reste/tempsMax*100);
    const b=$('barre');b.style.width=pct+'%';b.classList.toggle('urgent',pct<30);
    if(reste<=0){clearInterval(timer);erreur("Le temps vous a rattrapé…");}
  }

  function reussi(){
    clearInterval(timer);
    const bonus=Math.round((deadline-Date.now())/tempsMax*50);   // bonus vitesse
    score+=niveau*10+Math.max(0,bonus);
    sOk();flash();
    if(niveau>=NIVEAU_MAX)return gagne();
    niveau++;palier();
  }

  function erreur(txt){
    clearInterval(timer);
    vies--;sBad();shake();
    if(vies<=0)return mort(txt||"Vous êtes resté·e coincé·e…");
    palier();                                   // on rejoue le palier
  }

  function mort(txt){clearInterval(timer);vies=0;sDead();shake();
    $('msg-fin').textContent=txt;$('msg-fin').classList.remove('win');terminer();}
  function gagne(){sWin();$('msg-fin').textContent="VOUS VOUS ÊTES ÉCHAPPÉ DES BACKROOMS !";$('msg-fin').classList.add('win');terminer();}

  function terminer(){
    if(score>hs){hs=score;localStorage.setItem('backrooms_hs',hs);}
    $('score-fin').textContent=score;
    $('hs-fin').textContent='Meilleur score : '+hs;
    $('hs-start').textContent='Meilleur score : '+hs;
    $('jeu').classList.add('hidden');$('fin').classList.remove('hidden');
  }

  function shake(){document.body.classList.add('shake');setTimeout(()=>document.body.classList.remove('shake'),400);}
  function flash(){document.body.classList.add('flash');setTimeout(()=>document.body.classList.remove('flash'),160);}
})();
</script>
</body>
</html>

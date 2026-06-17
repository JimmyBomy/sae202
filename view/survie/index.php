<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="robots" content="noindex">
<title>ÉVASION DES BACKROOMS — 20 s</title>
<link rel="icon" type="image/png" href="<?= BASE_URL ?>/view/img/favicon.png">
<style>
  @font-face{font-family:'VT323';src:url('<?= BASE_URL ?>/view/fonts/vt323-400.woff2') format('woff2');font-display:swap;}
  *{box-sizing:border-box;margin:0;padding:0;-webkit-tap-highlight-color:transparent;}
  html,body{height:100%;}
  body{background:#0b0a07;color:#f5f5f0;font-family:'Segoe UI',Arial,sans-serif;text-align:center;
    overflow:hidden;background-image:linear-gradient(rgba(13,12,9,.82),rgba(13,12,9,.9)),
      repeating-linear-gradient(0deg,#181712 0 2px,#14130d 2px 4px);
    display:flex;flex-direction:column;}
  .overlay{position:fixed;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;
    gap:14px;padding:22px;z-index:10;overflow:auto;background:rgba(11,10,7,.92);}
  .hidden{display:none!important;}
  h1{font-family:'VT323',monospace;color:#d1b023;font-size:clamp(2rem,8vw,3.8rem);line-height:1;letter-spacing:2px;}
  .sub{color:#cfcfc4;max-width:520px;line-height:1.5;font-size:.92rem;}
  .prix{color:#d1b023;font-family:'VT323',monospace;font-size:1.4rem;border:1px solid #d1b023;border-radius:8px;padding:7px 14px;}
  .btn{font-family:'VT323',monospace;font-size:1.5rem;letter-spacing:1px;background:#d1b023;color:#14130d;border:none;
    border-radius:8px;padding:12px 32px;cursor:pointer;text-decoration:none;display:inline-block;}
  .btn:hover{background:#e3c63a;}
  .btn-ghost{background:none;color:#bdbdb0;border:1px solid #3a3320;}
  .liens{display:flex;gap:12px;flex-wrap:wrap;justify-content:center;}
  .msg{font-family:'VT323',monospace;font-size:2.1rem;color:#7ee2a8;}
  input{font-family:inherit;font-size:1rem;padding:11px 12px;border-radius:6px;border:1px solid #3a3320;background:#1d1c15;color:#f5f5f0;width:min(260px,78vw);text-align:center;}
  table{border-collapse:collapse;width:min(420px,92vw);font-size:.92rem;}
  th,td{padding:6px 9px;border-bottom:1px solid #2a2820;text-align:left;}
  th{color:#d1b023;font-family:'VT323',monospace;font-size:1.05rem;letter-spacing:1px;}
  td.r{text-align:right;font-family:'VT323',monospace;color:#fff;font-size:1.05rem;}
  tr.top td{color:#d1b023;}

  /* ===== Jeu ===== */
  #jeu{flex:1;display:flex;flex-direction:column;}
  #topbar{display:flex;justify-content:space-between;align-items:center;padding:10px 16px;
    font-family:'VT323',monospace;font-size:1.7rem;color:#d1b023;}
  #topbar b{color:#fff;}
  #combo{color:#7ee2a8;}
  #chrono-barre{height:10px;background:#2a2820;}
  #chrono-barre span{display:block;height:100%;width:100%;background:#d1b023;transition:width .15s linear;}
  #chrono-barre span.urgent{background:#c0392b;}
  #grille{display:grid;grid-template-columns:1fr 1fr;grid-auto-rows:1fr;gap:10px;padding:12px;
    width:min(520px,92vw);aspect-ratio:2/3;max-height:74vh;margin:auto;}
  .porte{position:relative;border:none;border-radius:10px;cursor:pointer;background-size:cover;background-position:center;
    background-image:url('<?= BASE_URL ?>/view/img/cal-ferme.webp');transition:transform .06s;min-height:0;}
  .porte:active{transform:scale(.96);}
  .porte.ouverte{background-image:url('<?= BASE_URL ?>/view/img/cal-ouvert.webp');box-shadow:0 0 22px rgba(126,226,168,.6),inset 0 0 0 3px #3ad17a;}
  .porte.entite::after{content:"👁";position:absolute;inset:0;display:flex;align-items:center;justify-content:center;
    font-size:9vmin;background:rgba(120,15,15,.45);border-radius:10px;}
  body.shake{animation:shk .25s;}@keyframes shk{0%,100%{transform:translate(0,0)}33%{transform:translate(-6px,3px)}66%{transform:translate(6px,-3px)}}
</style>
</head>
<body>

<!-- Jeu -->
<div id="jeu" class="hidden">
  <div id="topbar"><span>⏱ <b id="t-temps">20</b></span><span id="combo"></span><span>SCORE <b id="t-score">0</b></span></div>
  <div id="chrono-barre"><span id="t-barre"></span></div>
  <div id="grille"></div>
</div>

<!-- Accueil -->
<div id="start" class="overlay">
  <h1>ÉVASION DES BACKROOMS</h1>
  <p class="sub"><strong style="color:#d1b023">20 secondes</strong> pour fuir les couloirs.
     Touche la <strong style="color:#7ee2a8">porte ouverte</strong> le plus vite possible pour enchaîner les combos.
     Évite les portes <strong style="color:#ff8a7a">👁 entités</strong> (−2 s&nbsp;!).</p>
  <p class="prix">🏆 Meilleur score = <strong>4 places en avant-première&nbsp;!</strong></p>
  <p class="sub" style="color:#9a9a8a">⚠️ Une seule partie possible — donne tout !</p>
  <input id="pseudo" maxlength="30" placeholder="Ton pseudo (obligatoire)" autocomplete="off">
  <button class="btn" onclick="Jeu.start()">JOUER</button>
  <div id="lb-start"></div>
  <a class="btn btn-ghost" href="<?= BASE_URL ?>/">← Retour à l'accueil</a>
</div>

<!-- Déjà joué -->
<div id="deja" class="overlay hidden">
  <h1>DÉJÀ JOUÉ</h1>
  <p class="sub">Tu as déjà participé au concours 🎮 — une seule partie par personne.</p>
  <div id="lb-deja"></div>
  <a class="btn" href="<?= BASE_URL ?>/">Retour à l'accueil</a>
</div>

<!-- Fin (pop-up) -->
<div id="fin" class="overlay hidden">
  <p class="msg">PARTIE TERMINÉE</p>
  <p class="sub">Ton score : <strong id="fin-score" style="color:#d1b023;font-size:1.6rem">0</strong>
     · <span id="fin-portes">0</span> portes</p>
  <div id="fin-ok"><p class="prix">✔ Score enregistré au classement !</p></div>
  <div id="lb-fin"></div>
  <a class="btn" href="<?= BASE_URL ?>/">Retour à l'accueil</a>
</div>

<script>
const BASE='<?= BASE_URL ?>', CSRF='<?= csrf_token() ?>';
// Masque l'URL réelle dans la barre d'adresse (les gens ne doivent pas pouvoir la lire/retaper).
try{ history.replaceState(null,'',BASE+'/'); }catch(e){}
const Jeu=(function(){
  const $=id=>document.getElementById(id);
  const DUREE=20000, CLE='bk_survie_joue';
  let fin0,score,combo,portes,raf,running,pseudo='',lastScore=0,lastPortes=0;

  // Une seule partie par appareil (localStorage — PAS d'IP, car tout l'amphi partage la connexion).
  if(localStorage.getItem(CLE)){
    $('start').classList.add('hidden');
    $('deja').classList.remove('hidden');
    chargerClassement('lb-deja');
  }

  function start(){
    if(localStorage.getItem(CLE)){location.reload();return;}
    const p=$('pseudo').value.trim();
    if(!p){$('pseudo').focus();$('pseudo').style.borderColor='#c0392b';return;}  // pseudo OBLIGATOIRE
    pseudo=p;
    localStorage.setItem(CLE,'1');                 // ← partie consommée dès le lancement
    score=0;combo=0;portes=0;running=true;
    $('start').classList.add('hidden');$('jeu').classList.remove('hidden');
    fin0=performance.now()+DUREE;
    manche();boucle();
  }

  function boucle(){
    if(!running)return;
    const reste=fin0-performance.now();
    if(reste<=0){return termine();}
    $('t-temps').textContent=Math.ceil(reste/1000);
    const pct=reste/DUREE*100;
    const b=$('t-barre');b.style.width=pct+'%';b.classList.toggle('urgent',pct<25);
    raf=requestAnimationFrame(boucle);
  }

  function manche(){
    const ecoule=1-(fin0-performance.now())/DUREE;
    const nb=6;
    const g=$('grille');g.style.gridTemplateColumns='repeat(2,1fr)';g.innerHTML='';
    const ouverte=Math.random()*nb|0;
    const nbEnt=Math.min(1+Math.floor(ecoule*3),3);
    const ent=new Set();while(ent.size<nbEnt){const r=Math.random()*nb|0;if(r!==ouverte)ent.add(r);}
    for(let i=0;i<nb;i++){
      const p=document.createElement('button');p.className='porte';p.type='button';
      if(i===ouverte){p.classList.add('ouverte');p.onclick=bon;}
      else if(ent.has(i)){p.classList.add('entite');p.onclick=entite;}
      else p.onclick=ferme;
      g.appendChild(p);
    }
  }

  function bon(){combo=Math.min(combo+1,5);score+=10*combo;portes++;sOk();maj();manche();}
  function ferme(){combo=0;sBad();maj();manche();}
  function entite(){combo=0;fin0-=2000;sBad();shake();maj();manche();}
  function maj(){$('t-score').textContent=score;$('combo').textContent=combo>1?('COMBO x'+combo):'';}

  function termine(){
    running=false;cancelAnimationFrame(raf);lastScore=score;lastPortes=portes;
    $('jeu').classList.add('hidden');
    $('fin-score').textContent=score;$('fin-portes').textContent=portes;
    $('fin').classList.remove('hidden');
    envoyer();
  }

  function chargerClassement(cible){
    fetch(BASE+'/survie/classement').then(r=>r.json()).then(rows=>{
      let h='<table><tr><th>#</th><th>Joueur</th><th>Score</th><th>Portes</th></tr>';
      if(!rows.length)h+='<tr><td colspan="4" style="color:#9a9a8a">Sois le premier !</td></tr>';
      rows.forEach((r,i)=>{h+='<tr class="'+(i===0?'top':'')+'"><td>'+(i===0?'🏆':(i+1))+'</td><td>'+r.pseudo+
        '</td><td class="r">'+r.score+'</td><td class="r">'+r.niveau+'</td></tr>';});
      document.getElementById(cible).innerHTML=h+'</table>';
    }).catch(()=>{});
  }
  function envoyer(){
    const fd=new FormData();fd.append('csrf',CSRF);fd.append('pseudo',pseudo);fd.append('score',lastScore);fd.append('niveau',lastPortes);
    fetch(BASE+'/survie/soumettre',{method:'POST',body:fd})
      .then(r=>r.json()).then(()=>chargerClassement('lb-fin')).catch(()=>chargerClassement('lb-fin'));
  }

  let actx;function tone(f,d,t='square',v=.12){try{actx=actx||new(AudioContext||webkitAudioContext)();
    const o=actx.createOscillator(),g=actx.createGain();o.type=t;o.frequency.value=f;o.connect(g);g.connect(actx.destination);
    g.gain.setValueAtTime(v,actx.currentTime);g.gain.exponentialRampToValueAtTime(.0001,actx.currentTime+d);o.start();o.stop(actx.currentTime+d);}catch(e){}}
  const sOk=()=>tone(740+combo*90,.07);
  const sBad=()=>tone(150,.2,'sawtooth',.18);
  function shake(){document.body.classList.add('shake');setTimeout(()=>document.body.classList.remove('shake'),250);}

  if(!localStorage.getItem(CLE)) chargerClassement('lb-start');
  return {start};
})();
</script>
</body>
</html>

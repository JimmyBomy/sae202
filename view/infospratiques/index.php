<main class="concept-page">

  <section class="concept-section" style="padding-bottom:0;">
    <h1 class="sec-title">INFOS PRATIQUES</h1>
    <p class="sec-sub">Tout ce qu'il faut savoir avant de venir</p>

    <!-- Raccourcis vers les sections -->
    <div class="quick-links">
      <a href="#localisation" class="quick-link">
        <svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
        <span>Localisation &rsaquo;</span>
      </a>
      <a href="#contact" class="quick-link">
        <svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
        <span>Contact &rsaquo;</span>
      </a>
      <a href="#tarifs" class="quick-link">
        <svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7H12m5 7H9.5a3.5 3.5 0 0 1 0-7H12M7 8h10M7 16h10"/></svg>
        <span>Tarifs &rsaquo;</span>
      </a>
      <a href="#hebergement" class="quick-link">
        <svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M2 18v-6a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v6M2 14h20M6 10V8a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2M2 18v2M22 18v2"/></svg>
        <span>Hébergement &rsaquo;</span>
      </a>
      <a href="#interdictions" class="quick-link">
        <svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
        <span>Interdictions &rsaquo;</span>
      </a>
    </div>
  </section>

  <!-- ============ LOCALISATION ============ -->
  <section id="localisation" class="concept-section">
    <h2 class="sec-title">LOCALISATION</h2>
    <p class="sec-sub">Où se situe notre escape game ?</p>
    <div class="loc-grid">
      <div class="loc-info">
        <div class="info-field"><span class="info-label">Adresse</span><p>Cours Émile Zola</p></div>
        <div class="info-field"><span class="info-label">Ville</span><p>Villeurbanne</p></div>
        <div class="info-field"><span class="info-label">Code postal</span><p>69100</p></div>
      </div>
      <div class="loc-map">
        <?php if (GOOGLE_MAPS_KEY !== ''): ?>
          <!-- Carte Google Maps stylée (style "Pamplona" de Snazzy Maps, tons jaunes) -->
          <div id="map-backrooms"></div>
          <script>
            function initMap() {
              // Style "Pamplona" récupéré sur snazzymaps.com
              const stylePamplona = [{"featureType":"all","elementType":"geometry","stylers":[{"color":"#f2e4b2"}]},{"featureType":"all","elementType":"labels.text.fill","stylers":[{"gamma":0.01},{"lightness":20}]},{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"saturation":-31},{"lightness":-33},{"weight":2},{"gamma":0.8}]},{"featureType":"all","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"lightness":30},{"saturation":30}]},{"featureType":"landscape.natural.landcover","elementType":"geometry","stylers":[{"visibility":"on"},{"saturation":"-17"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"saturation":20}]},{"featureType":"poi","elementType":"labels.text","stylers":[{"saturation":"-26"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"lightness":20},{"saturation":-20},{"visibility":"off"},{"color":"#e4a5a5"}]},{"featureType":"road","elementType":"geometry","stylers":[{"lightness":10},{"saturation":-30}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"saturation":25},{"lightness":25}]},{"featureType":"water","elementType":"all","stylers":[{"lightness":-20}]}];
              const villeurbanne = { lat: 45.7672, lng: 4.8794 };
              const map = new google.maps.Map(document.getElementById('map-backrooms'), {
                center: villeurbanne,
                zoom: 14,
                styles: stylePamplona,
                streetViewControl: false,
                mapTypeControl: false
              });
              new google.maps.Marker({ position: villeurbanne, map: map, title: 'BACKROOMS' });
            }
          </script>
          <script async src="https://maps.googleapis.com/maps/api/js?key=<?= htmlspecialchars(GOOGLE_MAPS_KEY) ?>&callback=initMap"></script>
        <?php else: ?>
          <!-- Repli OpenStreetMap tant que la clé Google Maps n'est pas renseignée -->
          <iframe
            src="https://www.openstreetmap.org/export/embed.html?bbox=4.8550%2C45.7550%2C4.9100%2C45.7850&layer=mapnik&marker=45.7672%2C4.8794"
            title="Carte de localisation à Villeurbanne" loading="lazy"></iframe>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- ============ CONTACT ============ -->
  <section id="contact" class="concept-section">
    <h2 class="sec-title">CONTACT</h2>
    <p class="sec-sub">Comment nous contacter ?</p>
    <div class="loc-grid">
      <div class="loc-info">
        <div class="info-field"><span class="info-label">Adresse mail</span><p>backroomsescapegame@gmail.com</p></div>
        <div class="info-field"><span class="info-label">Numéro de téléphone</span><p>07 07 07 07 07</p></div>
        <div class="info-field"><span class="info-label">Réseaux sociaux</span><p>@backroomsescapegame (Instagram)<br>Backrooms Escape Game (Facebook)</p></div>
      </div>
      <!-- Le formulaire envoie vers le contrôleur Contact (qui envoie le mail) -->
      <form class="loc-form" action="<?= BASE_URL ?>/contact" method="post">
        <?= csrf_input() ?>
        <input type="hidden" name="sujet" value="Message depuis la page Infos pratiques">
        <div class="form-group"><input type="text" name="nom" placeholder="Nom *" required></div>
        <div class="form-group"><input type="email" name="email" placeholder="Email *" required></div>
        <div class="form-group"><input type="text" name="telephone" placeholder="Téléphone"></div>
        <div class="form-group"><textarea name="message" rows="4" placeholder="Votre message *" required></textarea></div>
        <button type="submit" class="btn btn-primary">ENVOYER</button>
      </form>
    </div>
  </section>

  <!-- ============ TARIFS ============ -->
  <section id="tarifs" class="concept-section">
    <h2 class="sec-title">TARIFS</h2>
    <p class="sec-sub">Combien coûte une partie ? (4h+ de jeu, repas et nuit sur place inclus)</p>
    <div class="loc-grid">
      <div>
        <table class="tableau tarif-grille">
          <thead><tr><th>Participants</th><th>Prix / personne</th></tr></thead>
          <tbody>
            <tr><td>2 personnes</td><td>170 €</td></tr>
            <tr><td>3 personnes</td><td>165 €</td></tr>
            <tr><td>4 personnes</td><td>160 €</td></tr>
            <tr><td>5 personnes</td><td>155 €</td></tr>
            <tr><td>6 personnes</td><td>150 €</td></tr>
            <tr><td>7 à 10 personnes</td><td>145 €</td></tr>
          </tbody>
        </table>
        <p class="tarif-note">Salle <strong>Hardcore</strong> (5h)&nbsp;: majoration de <strong>+10 €/personne</strong>.<br>Aucun tarif étudiant.</p>
      </div>

      <!-- Calculateur de prix : salle + nombre de participants -> total -->
      <div class="calc-tarif">
        <h3 class="pay-titre">Estimez votre tarif</h3>
        <div class="form-group">
          <label for="calc-salle">Salle</label>
          <select id="calc-salle" class="resa-field">
            <option value="facile">Salle 1 — Facile</option>
            <option value="standard">Salle 2 — Standard</option>
            <option value="hardcore">Salle 3 — Hardcore (+10 €/pers)</option>
          </select>
        </div>
        <div class="form-group">
          <label for="calc-nb">Nombre de participants (2 à 10)</label>
          <input id="calc-nb" class="resa-field" type="number" min="2" max="10" value="4">
        </div>
        <div class="calc-resultat">
          <span class="calc-pp" id="calc-pp"></span>
          <span class="calc-total" id="calc-total"></span>
        </div>
        <a href="<?= BASE_URL ?>/reservation" class="btn btn-primary" style="margin-top:14px;">RÉSERVER</a>
      </div>
    </div>
    <script>
      (function () {
        // Tarif dégressif (identique à la fonction PHP prix_total) :
        const grille = { 2:170, 3:165, 4:160, 5:155, 6:150 };
        const tarif  = n => (n >= 7 ? 145 : (grille[n] || 170));
        const salle = document.getElementById('calc-salle');
        const nb    = document.getElementById('calc-nb');
        const pp    = document.getElementById('calc-pp');
        const total = document.getElementById('calc-total');
        function maj() {
          const n = parseInt(nb.value, 10);
          if (isNaN(n) || n < 2 || n > 10) { pp.textContent = ''; total.textContent = 'Indiquez de 2 à 10 participants.'; return; }
          const parPers = tarif(n) + (salle.value === 'hardcore' ? 10 : 0);
          pp.textContent = parPers + ' € / personne';
          total.textContent = 'Total : ' + (parPers * n) + ' €';
        }
        salle.addEventListener('change', maj);
        nb.addEventListener('input', maj);
        maj();
      })();
    </script>
  </section>

  <!-- ============ HÉBERGEMENT & RESTAURATION ============ -->
  <section id="hebergement" class="concept-section">
    <h2 class="sec-title">HÉBERGEMENT &amp; RESTAURATION</h2>
    <p class="sec-sub">L'immersion continue toute la nuit</p>
    <div class="cartes cartes-3">
      <div class="carte">
        <h2>La chambre</h2>
        <p>Chambre double (lit séparable), volontairement nue&nbsp;: ni télé, ni table, ni mobilier
           superflu — 10&nbsp;m² maximum, pour rester fidèle à l'ambiance liminale.</p>
      </div>
      <div class="carte">
        <h2>La restauration</h2>
        <p>Rations de survie thématiques et la fameuse boisson «&nbsp;Eau d'Amande&nbsp;»,
           tout droit sorties du lore des Backrooms.</p>
      </div>
      <div class="carte">
        <h2>Le petit-déjeuner</h2>
        <p><strong>Offert</strong> si votre équipe s'échappe&nbsp;! En cas d'échec, il vous sera
           facturé <strong>6&nbsp;€</strong>… une motivation de plus pour sortir.</p>
      </div>
    </div>
  </section>

  <!-- ============ INTERDICTIONS ============ -->
  <section id="interdictions" class="concept-section">
    <h2 class="sec-title">INTERDICTIONS</h2>
    <p class="sec-sub">Pour la sécurité et le bon déroulement de la partie, ces objets sont interdits :</p>
    <div class="interdits-grid">
      <div class="interdit"><img class="interdit-ic" src="<?= BASE_URL ?>/view/img/interdit-telephone.webp" alt="" loading="lazy"><span>Téléphone portable</span></div>
      <div class="interdit"><img class="interdit-ic" src="<?= BASE_URL ?>/view/img/interdit-nourriture.webp" alt="" loading="lazy"><span>Nourriture</span></div>
      <div class="interdit"><img class="interdit-ic" src="<?= BASE_URL ?>/view/img/interdit-arme-feu.webp" alt="" loading="lazy"><span>Arme à feu</span></div>
      <div class="interdit"><img class="interdit-ic" src="<?= BASE_URL ?>/view/img/interdit-cigarette.webp" alt="" loading="lazy"><span>Cigarette</span></div>
      <div class="interdit"><img class="interdit-ic" src="<?= BASE_URL ?>/view/img/interdit-bombe.webp" alt="" loading="lazy"><span>Bombe</span></div>
      <div class="interdit"><img class="interdit-ic" src="<?= BASE_URL ?>/view/img/interdit-arme-blanche.webp" alt="" loading="lazy"><span>Arme blanche</span></div>
      <div class="interdit"><img class="interdit-ic" src="<?= BASE_URL ?>/view/img/interdit-animaux.webp" alt="" loading="lazy"><span>Animaux</span></div>
      <div class="interdit"><img class="interdit-ic" src="<?= BASE_URL ?>/view/img/interdit-parapluie.webp" alt="" loading="lazy"><span>Parapluie</span></div>
    </div>
  </section>

  <div class="cta-bloc">
    <a href="<?= BASE_URL ?>/reservation" class="btn btn-primary">RÉSERVER UNE SESSION</a>
  </div>
</main>

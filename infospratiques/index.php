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
        <div class="info-field"><span class="info-label">Adresse</span><p>12 rue des Liminaux</p></div>
        <div class="info-field"><span class="info-label">Ville</span><p>Troyes</p></div>
        <div class="info-field"><span class="info-label">Code postal</span><p>10000</p></div>
      </div>
      <div class="loc-map">
        <?php if (GOOGLE_MAPS_KEY !== ''): ?>
          <!-- Carte Google Maps stylée (style "Pamplona" de Snazzy Maps, tons jaunes) -->
          <div id="map-backrooms"></div>
          <script>
            function initMap() {
              // Style "Pamplona" récupéré sur snazzymaps.com
              const stylePamplona = [{"featureType":"all","elementType":"geometry","stylers":[{"color":"#f2e4b2"}]},{"featureType":"all","elementType":"labels.text.fill","stylers":[{"gamma":0.01},{"lightness":20}]},{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"saturation":-31},{"lightness":-33},{"weight":2},{"gamma":0.8}]},{"featureType":"all","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"lightness":30},{"saturation":30}]},{"featureType":"landscape.natural.landcover","elementType":"geometry","stylers":[{"visibility":"on"},{"saturation":"-17"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"saturation":20}]},{"featureType":"poi","elementType":"labels.text","stylers":[{"saturation":"-26"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"lightness":20},{"saturation":-20},{"visibility":"off"},{"color":"#e4a5a5"}]},{"featureType":"road","elementType":"geometry","stylers":[{"lightness":10},{"saturation":-30}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"saturation":25},{"lightness":25}]},{"featureType":"water","elementType":"all","stylers":[{"lightness":-20}]}];
              const troyes = { lat: 48.2973, lng: 4.0744 };
              const map = new google.maps.Map(document.getElementById('map-backrooms'), {
                center: troyes,
                zoom: 14,
                styles: stylePamplona,
                streetViewControl: false,
                mapTypeControl: false
              });
              new google.maps.Marker({ position: troyes, map: map, title: 'BACKROOMS' });
            }
          </script>
          <script async src="https://maps.googleapis.com/maps/api/js?key=<?= htmlspecialchars(GOOGLE_MAPS_KEY) ?>&callback=initMap"></script>
        <?php else: ?>
          <!-- Repli OpenStreetMap tant que la clé Google Maps n'est pas renseignée -->
          <iframe
            src="https://www.openstreetmap.org/export/embed.html?bbox=4.0550%2C48.2880%2C4.1000%2C48.3080&layer=mapnik&marker=48.2973%2C4.0744"
            title="Carte de localisation à Troyes" loading="lazy"></iframe>
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
        <div class="info-field"><span class="info-label">Adresse mail</span><p>backrooms.escape@lumina-studio.fr</p></div>
        <div class="info-field"><span class="info-label">Téléphone</span><p>07 07 07 07 07</p></div>
        <div class="info-field"><span class="info-label">Réseaux sociaux</span><p>Instagram · Discord · TikTok</p></div>
      </div>
      <!-- Le formulaire envoie vers le contrôleur Contact (qui envoie le mail) -->
      <form class="loc-form" action="<?= BASE_URL ?>/contact" method="post">
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
    <p class="sec-sub">Combien coûte une partie ?</p>
    <div class="cartes cartes-3">
      <div class="carte tarif-card">
        <span class="salle-tag">SALLE 1</span>
        <h2>Le Niveau 0</h2>
        <p>Exploration et cartographie. Aucune menace : l'initiation idéale.</p>
        <p class="tarif-prix">28,00 €<span>/ joueur</span></p>
        <div class="diff">
          <span class="diff-label">Niveau de difficulté</span>
          <svg class="gauge" viewBox="0 0 100 58" aria-hidden="true">
            <path d="M8 50 A42 42 0 0 1 92 50" fill="none" stroke="#2a2a20" stroke-width="8" stroke-linecap="round"/>
            <path d="M8 50 A42 42 0 0 1 92 50" fill="none" stroke="#d1b023" stroke-width="8" stroke-linecap="round" stroke-dasharray="44 200"/>
          </svg>
          <span class="diff-niv">Facile</span>
        </div>
      </div>
      <div class="carte tarif-card">
        <span class="salle-tag">SALLE 2</span>
        <h2>Les Couloirs jaunes</h2>
        <p>Recherche de ressources et gestion du stress. Présence d'entités.</p>
        <p class="tarif-prix">34,00 €<span>/ joueur</span></p>
        <div class="diff">
          <span class="diff-label">Niveau de difficulté</span>
          <svg class="gauge" viewBox="0 0 100 58" aria-hidden="true">
            <path d="M8 50 A42 42 0 0 1 92 50" fill="none" stroke="#2a2a20" stroke-width="8" stroke-linecap="round"/>
            <path d="M8 50 A42 42 0 0 1 92 50" fill="none" stroke="#d1b023" stroke-width="8" stroke-linecap="round" stroke-dasharray="87 200"/>
          </svg>
          <span class="diff-niv">Moyen</span>
        </div>
      </div>
      <div class="carte tarif-card">
        <span class="salle-tag salle-tag--hard">SALLE 3</span>
        <h2>Le Niveau !</h2>
        <p>Horreur extrême : traque continue par les comédiens, dans le noir.</p>
        <p class="tarif-prix">39,00 €<span>/ joueur</span></p>
        <div class="diff">
          <span class="diff-label">Niveau de difficulté</span>
          <svg class="gauge" viewBox="0 0 100 58" aria-hidden="true">
            <path d="M8 50 A42 42 0 0 1 92 50" fill="none" stroke="#2a2a20" stroke-width="8" stroke-linecap="round"/>
            <path d="M8 50 A42 42 0 0 1 92 50" fill="none" stroke="#c0392b" stroke-width="8" stroke-linecap="round" stroke-dasharray="132 200"/>
          </svg>
          <span class="diff-niv" style="color:#e0654f">Difficile</span>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ INTERDICTIONS ============ -->
  <section id="interdictions" class="concept-section">
    <h2 class="sec-title">INTERDICTIONS</h2>
    <p class="sec-sub">Pour la sécurité et le bon déroulement de la partie, ces objets sont interdits :</p>
    <div class="interdits-grid">
      <div class="interdit"><div class="interdit-ic">📱</div><span>Téléphone portable</span></div>
      <div class="interdit"><div class="interdit-ic">🍔</div><span>Nourriture</span></div>
      <div class="interdit"><div class="interdit-ic">🔫</div><span>Arme à feu</span></div>
      <div class="interdit"><div class="interdit-ic">🚬</div><span>Cigarette</span></div>
      <div class="interdit"><div class="interdit-ic">🧨</div><span>Explosifs</span></div>
      <div class="interdit"><div class="interdit-ic">🔪</div><span>Arme blanche</span></div>
      <div class="interdit"><div class="interdit-ic">🐾</div><span>Animaux</span></div>
      <div class="interdit"><div class="interdit-ic">☂️</div><span>Parapluie</span></div>
    </div>
  </section>

  <div class="cta-bloc">
    <a href="<?= BASE_URL ?>/reservation" class="btn btn-primary">RÉSERVER UNE SESSION</a>
  </div>
</main>

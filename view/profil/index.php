<main class="container" style="padding-top: 50px;">
    <h2>Mon Profil</h2>
    
    <div style="display: flex; gap: 20px; margin-bottom: 20px;">
        <a href="<?= BASE_URL ?>/profil" class="btn btn-primary">Informations personnelles</a>
        <a href="<?= BASE_URL ?>/profil/password" class="btn btn-outline">Mot de passe</a>
        <a href="<?= BASE_URL ?>/profil/commentaire" class="btn btn-outline">Laisser un avis</a>
    </div>

    <?php if (!empty($erreur)): ?>
        <div class="alert alert-error"><?= $erreur ?></div>
    <?php endif; ?>
    <?php if (!empty($succes)): ?>
        <div class="alert alert-success"><?= $succes ?></div>
    <?php endif; ?>

    <form action="" method="post" style="max-width: 600px; background: #222; padding: 20px; border-radius: 5px;">
        <div class="form-group">
            <label for="nom">Nom *</label>
            <input type="text" name="nom" id="nom" value="<?= htmlspecialchars($utilisateur['nom']) ?>" required>
        </div>
        <div class="form-group">
            <label for="prenom">Prénom *</label>
            <input type="text" name="prenom" id="prenom" value="<?= htmlspecialchars($utilisateur['prenom']) ?>" required>
        </div>
        <div class="form-group">
            <label for="pseudo">Pseudo *</label>
            <input type="text" name="pseudo" id="pseudo" value="<?= htmlspecialchars($utilisateur['pseudo']) ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($utilisateur['email']) ?>" required>
        </div>
        <div class="form-group">
            <label for="telephone">Téléphone</label>
            <input type="text" name="telephone" id="telephone" value="<?= htmlspecialchars($utilisateur['telephone'] ?? '') ?>">
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
    </form>

    <!-- Espace privé : équipe et scores (lecture seule) -->
    <section class="carte" style="margin-top: 30px; max-width: 600px;">
        <h2>Mon équipe</h2>
        <?php if (!$equipe): ?>
            <p>Vous n'avez pas encore d'équipe.</p>
            <a href="<?= BASE_URL ?>/reservation" class="btn btn-outline">Créer ou rejoindre une équipe</a>
        <?php else: ?>
            <p><strong><?= htmlspecialchars($equipe['nom']) ?></strong>
               — code d'invitation : <span class="code-invite"><?= htmlspecialchars($equipe['code_invite']) ?></span></p>

            <h3 style="margin-top: 20px;">Mes scores</h3>
            <?php if (empty($scores)): ?>
                <p>Aucun score enregistré pour l'instant.</p>
            <?php else: ?>
                <table class="tableau">
                    <thead><tr><th>Date</th><th>Points</th><th>Temps</th><th>Résultat</th></tr></thead>
                    <tbody>
                    <?php foreach ($scores as $s): ?>
                        <tr>
                            <td><?= htmlspecialchars(date('d/m/Y', strtotime($s['date_partie']))) ?></td>
                            <td><?= htmlspecialchars($s['points']) ?> pts</td>
                            <td><?= $s['temps_secondes'] !== null ? floor($s['temps_secondes'] / 60) . ' min ' . ($s['temps_secondes'] % 60) . ' s' : '—' ?></td>
                            <td><?= $s['reussi'] ? '✅ Sortis !' : '❌ Coincés' ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
            <a href="<?= BASE_URL ?>/reservation" class="btn btn-outline" style="margin-top: 15px;">Gérer mon équipe / réserver</a>
        <?php endif; ?>
    </section>
</main>

<main class="container" style="padding-top: 50px;">
    <h2>Mon Profil</h2>
    
    <div style="display: flex; gap: 20px; margin-bottom: 20px;">
        <a href="<?= BASE_URL ?>/profil" class="btn btn-primary">Informations personnelles</a>
        <a href="<?= BASE_URL ?>/profil/password" class="btn btn-outline">Mot de passe</a>
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
</main>

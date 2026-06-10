<main class="container" style="padding-top: 50px;">
    <h2>Mon Profil</h2>
    
    <div class="profil-onglets">
        <a href="<?= BASE_URL ?>/profil" class="btn btn-outline">Informations personnelles</a>
        <a href="<?= BASE_URL ?>/profil/password" class="btn btn-primary">Mot de passe</a>
        <a href="<?= BASE_URL ?>/profil/commentaire" class="btn btn-outline">Laisser un avis</a>
        <a href="<?= BASE_URL ?>/compte/deconnexion" class="btn btn-outline" style="margin-left:auto;">Déconnexion</a>
    </div>

    <?php if (!empty($erreur)): ?>
        <div class="alert alert-error"><?= $erreur ?></div>
    <?php endif; ?>
    <?php if (!empty($succes)): ?>
        <div class="alert alert-success"><?= $succes ?></div>
    <?php endif; ?>

    <form action="" method="post" style="max-width: 600px; background: #222; padding: 20px; border-radius: 5px;">
        <div class="form-group">
            <label for="mot_de_passe_actuel">Mot de passe actuel *</label>
            <input type="password" name="mot_de_passe_actuel" id="mot_de_passe_actuel" required>
        </div>
        <div class="form-group">
            <label for="nouveau_mot_de_passe">Nouveau mot de passe *</label>
            <input type="password" name="nouveau_mot_de_passe" id="nouveau_mot_de_passe" required>
        </div>
        <div class="form-group">
            <label for="confirmation_mot_de_passe">Confirmer le nouveau mot de passe *</label>
            <input type="password" name="confirmation_mot_de_passe" id="confirmation_mot_de_passe" required>
        </div>
        <button type="submit" class="btn btn-primary">Modifier le mot de passe</button>
    </form>
</main>

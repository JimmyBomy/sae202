<main class="container">
    <h2><?= t('pr_title') ?></h2>

    <div class="profil-onglets">
        <a href="<?= BASE_URL ?>/profil" class="btn btn-outline"><?= t('pr_tab_infos') ?></a>
        <a href="<?= BASE_URL ?>/profil/password" class="btn btn-primary"><?= t('pr_tab_pass') ?></a>
        <a href="<?= BASE_URL ?>/profil/commentaire" class="btn btn-outline"><?= t('pr_tab_avis') ?></a>
        <a href="<?= BASE_URL ?>/compte/deconnexion" class="btn btn-outline" style="margin-left:auto;"><?= t('pr_logout') ?></a>
    </div>

    <?php if (!empty($erreur)): ?>
        <div class="alert alert-error"><?= $erreur ?></div>
    <?php endif; ?>
    <?php if (!empty($succes)): ?>
        <div class="alert alert-success"><?= $succes ?></div>
    <?php endif; ?>

    <form action="" method="post" class="form-carte">
    <?= csrf_input() ?>
        <div class="form-group">
            <label for="mot_de_passe_actuel"><?= t('pw_actuel') ?></label>
            <input type="password" name="mot_de_passe_actuel" id="mot_de_passe_actuel" required>
        </div>
        <div class="form-group">
            <label for="nouveau_mot_de_passe"><?= t('pw_nouveau') ?></label>
            <input type="password" name="nouveau_mot_de_passe" id="nouveau_mot_de_passe" required>
        </div>
        <div class="form-group">
            <label for="confirmation_mot_de_passe"><?= t('pw_confirm') ?></label>
            <input type="password" name="confirmation_mot_de_passe" id="confirmation_mot_de_passe" required>
        </div>
        <button type="submit" class="btn btn-primary"><?= t('pw_btn') ?></button>
    </form>
</main>

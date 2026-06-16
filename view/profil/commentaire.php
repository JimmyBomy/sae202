<?php
$note = $note ?? '';
$texte = $texte ?? '';
?>
<main class="container">
    <h2><?= t('pr_title') ?></h2>

    <div class="profil-onglets">
        <a href="<?= BASE_URL ?>/profil" class="btn btn-outline"><?= t('pr_tab_infos') ?></a>
        <a href="<?= BASE_URL ?>/profil/password" class="btn btn-outline"><?= t('pr_tab_pass') ?></a>
        <a href="<?= BASE_URL ?>/profil/commentaire" class="btn btn-primary"><?= t('pr_tab_avis') ?></a>
        <a href="<?= BASE_URL ?>/compte/deconnexion" class="btn btn-outline" style="margin-left:auto;"><?= t('pr_logout') ?></a>
    </div>

    <?php if (!empty($erreur)): ?>
        <div class="alert alert-error"><?= $erreur ?></div>
    <?php endif; ?>
    <?php if (!empty($succes)): ?>
        <div class="alert alert-success"><?= $succes ?></div>
    <?php endif; ?>

    <p><?= t('av_intro') ?></p>

    <form action="" method="post" class="form-carte">
    <?= csrf_input() ?>
        <div class="form-group">
            <label for="note"><?= t('av_note') ?></label>
            <select name="note" id="note" required class="form-input">
                <option value="5" <?= ($note === '5' || $note === 5) ? 'selected' : '' ?>><?= t('av_o5') ?></option>
                <option value="4" <?= ($note === '4' || $note === 4) ? 'selected' : '' ?>><?= t('av_o4') ?></option>
                <option value="3" <?= ($note === '3' || $note === 3) ? 'selected' : '' ?>><?= t('av_o3') ?></option>
                <option value="2" <?= ($note === '2' || $note === 2) ? 'selected' : '' ?>><?= t('av_o2') ?></option>
                <option value="1" <?= ($note === '1' || $note === 1) ? 'selected' : '' ?>><?= t('av_o1') ?></option>
            </select>
        </div>
        <div class="form-group" style="margin-top: 15px;">
            <label for="texte"><?= t('av_txt') ?></label>
            <textarea name="texte" id="texte" rows="5" required class="form-input"><?= isset($texte) ? htmlspecialchars($texte, ENT_QUOTES, 'UTF-8') : '' ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary" style="margin-top: 15px;"><?= t('av_btn') ?></button>
    </form>
</main>

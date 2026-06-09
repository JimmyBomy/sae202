<?php
$note = $note ?? '';
$texte = $texte ?? '';
?>
<main class="container" style="padding-top: 50px;">
    <h2>Mon Profil</h2>
    
    <div style="display: flex; gap: 20px; margin-bottom: 20px;">
        <a href="<?= BASE_URL ?>/profil" class="btn btn-outline">Informations personnelles</a>
        <a href="<?= BASE_URL ?>/profil/password" class="btn btn-outline">Mot de passe</a>
        <a href="<?= BASE_URL ?>/profil/commentaire" class="btn btn-primary">Laisser un avis</a>
        <a href="<?= BASE_URL ?>/compte/deconnexion" class="btn btn-outline" style="margin-left:auto;">Déconnexion</a>
    </div>

    <?php if (!empty($erreur)): ?>
        <div class="alert alert-error"><?= $erreur ?></div>
    <?php endif; ?>
    <?php if (!empty($succes)): ?>
        <div class="alert alert-success"><?= $succes ?></div>
    <?php endif; ?>

    <p>Avez-vous réussi à sortir des Backrooms ? Laissez-nous votre avis sur l'expérience !</p>

    <form action="" method="post" style="max-width: 600px; background: #222; padding: 20px; border-radius: 5px;">
        <div class="form-group">
            <label for="note">Note (sur 5) *</label>
            <select name="note" id="note" required style="width: 100%; padding: 10px; border-radius: 4px; background: #333; color: white; border: 1px solid #444;">
                <option value="5" <?= ($note === '5' || $note === 5) ? 'selected' : '' ?>>5 - Excellent, inoubliable !</option>
                <option value="4" <?= ($note === '4' || $note === 4) ? 'selected' : '' ?>>4 - Très bien, angoissant</option>
                <option value="3" <?= ($note === '3' || $note === 3) ? 'selected' : '' ?>>3 - Bien, mais peut mieux faire</option>
                <option value="2" <?= ($note === '2' || $note === 2) ? 'selected' : '' ?>>2 - Décevant</option>
                <option value="1" <?= ($note === '1' || $note === 1) ? 'selected' : '' ?>>1 - Très mauvais</option>
            </select>
        </div>
        <div class="form-group" style="margin-top: 15px;">
            <label for="texte">Votre commentaire *</label>
            <textarea name="texte" id="texte" rows="5" required style="width: 100%; padding: 10px; border-radius: 4px; background: #333; color: white; border: 1px solid #444;"><?= isset($texte) ? htmlspecialchars($texte, ENT_QUOTES, 'UTF-8') : '' ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary" style="margin-top: 15px;">Envoyer mon avis</button>
    </form>
</main>

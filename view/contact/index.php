<main class="container" style="padding-top: 50px;">
    <h2>Nous contacter</h2>
    <p>Vous avez une question sur notre escape game ? N'hésitez pas à nous envoyer un message !</p>

    <?php if (!empty($erreur)): ?>
        <div class="alert alert-error"><?= $erreur ?></div>
    <?php endif; ?>
    <?php if (!empty($succes)): ?>
        <div class="alert alert-success"><?= $succes ?></div>
    <?php endif; ?>

    <form action="" method="post" style="max-width: 600px; background: #222; padding: 20px; border-radius: 5px;">
        <div class="form-group">
            <label for="nom">Votre nom *</label>
            <input type="text" name="nom" id="nom" required>
        </div>
        <div class="form-group">
            <label for="email">Votre email *</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div class="form-group">
            <label for="sujet">Sujet *</label>
            <input type="text" name="sujet" id="sujet" required>
        </div>
        <div class="form-group">
            <label for="message">Message *</label>
            <textarea name="message" id="message" rows="6" style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #444; background: #333; color: #fff;" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Envoyer le message</button>
    </form>
</main>

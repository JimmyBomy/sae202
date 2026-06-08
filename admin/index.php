<?php
require_once("../conf/conf.inc.php");
require_once("../model/utilisateur.php");

// Vérification basique d`autorisation via session (en plus du htaccess)
if (!isset($_SESSION["user_id"])) {
    header("Location: " . BASE_URL . "/compte/connexion");
    exit;
}
$user = get_utilisateur_by_id($_SESSION["user_id"]);
if ($user["role"] !== "admin") {
    die("Accès refusé. Vous n`êtes pas administrateur.");
}

$utilisateurs = get_tous_utilisateurs();
$total_inscrits = count($utilisateurs);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Back-Office - <?= NOM_SITE ?></title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
        .container { max-width: 1000px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1, h2 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 10px; text-align: left; }
        th { background: #eee; }
        .stats { background: #3498db; color: white; padding: 15px; border-radius: 5px; display: inline-block; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Back-Office <?= NOM_SITE ?></h1>
        <p><a href="<?= BASE_URL ?>/">Retour au site public</a></p>

        <div class="stats">
            <h2>Statistiques</h2>
            <p><strong>Total d`utilisateurs inscrits :</strong> <?= $total_inscrits ?></p>
        </div>

        <h2>Liste des Utilisateurs Inscrits</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Pseudo</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Date Inscription</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($utilisateurs as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u["id"]) ?></td>
                    <td><?= htmlspecialchars($u["nom"]) ?></td>
                    <td><?= htmlspecialchars($u["prenom"]) ?></td>
                    <td><?= htmlspecialchars($u["pseudo"]) ?></td>
                    <td><?= htmlspecialchars($u["email"]) ?></td>
                    <td><?= htmlspecialchars($u["role"]) ?></td>
                    <td><?= htmlspecialchars($u["date_inscription"]) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

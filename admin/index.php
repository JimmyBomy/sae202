<?php
/**
 * BACK-OFFICE  (accessible via https://.../gestion)
 * --------------------------------------------------
 * La PROTECTION de cette zone est assurée par Apache : le fichier
 * admin/.htaccess impose une authentification HTTP Basic (htpassword.mmi).
 * On ne peut donc PAS arriver ici sans avoir saisi un identifiant MMI/prof.
 *
 * Fonctions : statistiques, modération des avis, gestion des réservations,
 * saisie des scores, liste des inscrits et des équipes.
 */

// admin/index.php est exécuté depuis le dossier admin/. On se replace à la
// racine du projet pour que les require('model/...') des modèles fonctionnent.
chdir(dirname(__DIR__));

require_once('conf/conf.inc.php');
require_once('model/utilisateur.php');
require_once('model/commentaire.php');
require_once('model/equipe.php');
require_once('model/reservation.php');
require_once('model/score.php');

// --- Export CSV des inscrits (bouton dans la carte "Utilisateurs inscrits") ---
if (($_GET['export'] ?? '') === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="inscrits_backrooms_' . date('Ymd') . '.csv"');
    $sortie = fopen('php://output', 'w');
    fputs($sortie, "\xEF\xBB\xBF"); // BOM : accents corrects dans Excel
    fputcsv($sortie, ['ID', 'Pseudo', 'Nom', 'Prénom', 'Email', 'Téléphone', 'Naissance', 'Équipe', 'Rôle', 'Inscrit le',
                      'Cardiaque', 'Épilepsie', 'Respiratoire', 'Claustrophobie'], ';');
    foreach (get_tous_utilisateurs() as $u) {
        fputcsv($sortie, [$u['id'], $u['pseudo'], $u['nom'], $u['prenom'], $u['email'], $u['telephone'],
                          $u['date_naissance'], $u['equipe_nom'] ?? '', $u['role'], $u['date_inscription'],
                          $u['sante_cardiaque'], $u['sante_epilepsie'], $u['sante_respiratoire'], $u['sante_claustro']], ';');
    }
    fclose($sortie);
    exit;
}

// --- Traitements (modération avis) — en POST + jeton CSRF (pas de lien GET forgeable) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['id']) && csrf_verifie()) {
    $id = (int) $_POST['id'];
    if ($_POST['action'] === 'approuver') update_statut_commentaire($id, 'approuve');
    if ($_POST['action'] === 'refuser')   update_statut_commentaire($id, 'refuse');
    header('Location: ' . BASE_URL . '/gestion');
    exit;
}

// --- Traitements (statut d'une réservation) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resa'], $_POST['statut']) && csrf_verifie()) {
    $id = (int) $_POST['resa'];
    if (in_array($_POST['statut'], ['confirmee', 'annulee', 'en_attente'], true)) {
        update_statut_reservation($id, $_POST['statut']);
    }
    header('Location: ' . BASE_URL . '/gestion');
    exit;
}

// --- Traitements (saisie d'un score) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form_type'] ?? '') === 'score' && csrf_verifie()) {
    $equipe_id = (int) ($_POST['equipe_id'] ?? 0);
    $points    = (int) ($_POST['points'] ?? 0);
    $minutes   = (int) ($_POST['minutes'] ?? 0);
    $secondes  = (int) ($_POST['secondes'] ?? 0);
    $temps     = ($minutes > 0 || $secondes > 0) ? ($minutes * 60 + $secondes) : null;
    $reussi    = isset($_POST['reussi']) ? 1 : 0;
    if ($equipe_id > 0) {
        ajouter_score($equipe_id, $points, $temps, $reussi);
    }
    header('Location: ' . BASE_URL . '/gestion');
    exit;
}

// --- Données ---
$utilisateurs = get_tous_utilisateurs();
$commentaires = get_tous_commentaires();
$equipes      = get_toutes_equipes();
$reservations = get_toutes_reservations();
$scores       = get_tous_scores();

// --- Statistiques calculées ---
$total_inscrits   = count($utilisateurs);
$total_equipes    = count($equipes);
$total_resa       = count($reservations);
$resa_en_attente  = count(array_filter($reservations, fn($r) => $r['statut'] === 'en_attente'));
$avis_en_attente  = count(array_filter($commentaires, fn($c) => $c['statut'] === 'en_attente'));
$avis_approuves   = array_filter($commentaires, fn($c) => $c['statut'] === 'approuve');
$note_moyenne     = $avis_approuves ? round(array_sum(array_column($avis_approuves, 'note')) / count($avis_approuves), 1) : 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Back-Office — <?= NOM_SITE ?></title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, Helvetica, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; color: #222; }
        .wrap { max-width: 1100px; margin: auto; }
        .card { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 1px 6px rgba(0,0,0,0.08); margin-bottom: 25px; }
        h1 { margin-top: 0; } h2 { color: #333; border-bottom: 2px solid #d1b023; padding-bottom: 6px; }
        a.lien { color: #1a73e8; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { padding: 9px 10px; text-align: left; border: 1px solid #e0e0e0; font-size: 14px; }
        th { background: #fafafa; }
        .stats { display: flex; flex-wrap: wrap; gap: 15px; }
        .stat { flex: 1; min-width: 150px; background: #1a1a1a; color: #fff; padding: 16px; border-radius: 8px; text-align: center; }
        .stat .num { font-size: 2rem; font-weight: bold; color: #d1b023; display: block; }
        .stat .lbl { font-size: .85rem; text-transform: uppercase; letter-spacing: 1px; }
        .btn { padding: 5px 10px; text-decoration: none; color: #fff; border-radius: 4px; font-size: 13px; display: inline-block; border: none; cursor: pointer; font-family: inherit; }
        .btn-success { background: #2ecc71; } .btn-danger { background: #e74c3c; } .btn-info { background: #3498db; }
        .badge { padding: 3px 8px; border-radius: 10px; font-size: 12px; color:#fff; text-transform: capitalize; }
        .badge.en_attente { background:#e67e22; } .badge.confirmee, .badge.approuve { background:#27ae60; }
        .badge.annulee, .badge.refuse { background:#c0392b; }
        form.inline { display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 10px; align-items: end; }
        form.inline label { font-size: 13px; display: block; margin-bottom: 3px; }
        form.inline input, form.inline select { width: 100%; padding: 7px; border: 1px solid #ccc; border-radius: 4px; }
        button { padding: 9px 14px; background: #d1b023; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
        /* Responsive back-office : tableaux scrollables, grilles resserrées */
        @media (max-width: 700px) {
            body { padding: 10px; }
            .card { padding: 14px; }
            table { display: block; overflow-x: auto; white-space: nowrap; -webkit-overflow-scrolling: touch; }
            form.inline { grid-template-columns: 1fr 1fr; }
            .stat { min-width: 120px; }
        }
    </style>
</head>
<body>
<div class="wrap">
    <h1>Back-Office — <?= NOM_SITE ?></h1>
    <p><a class="lien" href="<?= BASE_URL ?>/">← Retour au site public</a></p>

    <!-- STATISTIQUES -->
    <div class="card">
        <h2>Statistiques</h2>
        <div class="stats">
            <div class="stat"><span class="num"><?= $total_inscrits ?></span><span class="lbl">Inscrits</span></div>
            <div class="stat"><span class="num"><?= $total_equipes ?></span><span class="lbl">Équipes</span></div>
            <div class="stat"><span class="num"><?= $total_resa ?></span><span class="lbl">Réservations</span></div>
            <div class="stat"><span class="num"><?= $resa_en_attente ?></span><span class="lbl">Résa en attente</span></div>
            <div class="stat"><span class="num"><?= $avis_en_attente ?></span><span class="lbl">Avis à modérer</span></div>
            <div class="stat"><span class="num"><?= $note_moyenne ?>/5</span><span class="lbl">Note moyenne</span></div>
        </div>
    </div>

    <!-- MODÉRATION DES AVIS -->
    <div class="card">
        <h2>Modération des avis</h2>
        <table>
            <thead><tr><th>Joueur</th><th>Note</th><th>Commentaire</th><th>Date</th><th>Statut</th><th>Action</th></tr></thead>
            <tbody>
            <?php foreach ($commentaires as $c): ?>
                <tr>
                    <td><?= htmlspecialchars($c['pseudo']) ?></td>
                    <td><?= htmlspecialchars($c['note']) ?>/5</td>
                    <td><?= htmlspecialchars($c['texte']) ?></td>
                    <td><?= htmlspecialchars($c['date_creation']) ?></td>
                    <td><span class="badge <?= htmlspecialchars($c['statut']) ?>"><?= htmlspecialchars(str_replace('_',' ',$c['statut'])) ?></span></td>
                    <td>
                        <form method="post" style="display:inline-flex; gap:6px;">
                            <?= csrf_input() ?>
                            <input type="hidden" name="id" value="<?= $c['id'] ?>">
                            <?php if ($c['statut'] === 'en_attente'): ?>
                                <button type="submit" name="action" value="approuver" class="btn btn-success">Approuver</button>
                                <button type="submit" name="action" value="refuser" class="btn btn-danger">Refuser</button>
                            <?php else: ?>
                                <button type="submit" name="action" value="approuver" class="btn btn-info">Re-publier</button>
                            <?php endif; ?>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($commentaires)): ?><tr><td colspan="6">Aucun avis.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- RÉSERVATIONS -->
    <div class="card">
        <h2>Réservations</h2>
        <table>
            <thead><tr><th>Équipe</th><th>Salle</th><th>Date session</th><th>Joueurs</th><th>Statut</th><th>Action</th></tr></thead>
            <tbody>
            <?php foreach ($reservations as $r): ?>
                <tr>
                    <td><?= htmlspecialchars($r['equipe_nom']) ?></td>
                    <td><?= htmlspecialchars(ucfirst($r['salle'])) ?></td>
                    <td><?= htmlspecialchars(date('d/m/Y H\hi', strtotime($r['date_session']))) ?></td>
                    <td><?= htmlspecialchars($r['nb_joueurs']) ?></td>
                    <td><span class="badge <?= htmlspecialchars($r['statut']) ?>"><?= htmlspecialchars(str_replace('_',' ',$r['statut'])) ?></span></td>
                    <td>
                        <form method="post" style="display:inline-flex; gap:6px;">
                            <?= csrf_input() ?>
                            <input type="hidden" name="resa" value="<?= $r['id'] ?>">
                            <button type="submit" name="statut" value="confirmee" class="btn btn-success">Confirmer</button>
                            <button type="submit" name="statut" value="annulee" class="btn btn-danger">Annuler</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($reservations)): ?><tr><td colspan="6">Aucune réservation.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- SAISIE DES SCORES -->
    <div class="card">
        <h2>Saisir un score</h2>
        <?php if (empty($equipes)): ?>
            <p>Aucune équipe pour le moment.</p>
        <?php else: ?>
            <form method="post" class="inline">
                <?= csrf_input() ?>
                <input type="hidden" name="form_type" value="score">
                <div><label>Équipe</label>
                    <select name="equipe_id" required>
                        <?php foreach ($equipes as $e): ?>
                            <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div><label>Points</label><input type="number" name="points" value="0" min="0" required></div>
                <div><label>Minutes</label><input type="number" name="minutes" value="0" min="0"></div>
                <div><label>Secondes</label><input type="number" name="secondes" value="0" min="0" max="59"></div>
                <div><label>Sortis ?</label><input type="checkbox" name="reussi" value="1"></div>
                <div><button type="submit">Enregistrer</button></div>
            </form>
        <?php endif; ?>

        <h3 style="margin-top:25px;">Derniers scores</h3>
        <table>
            <thead><tr><th>Équipe</th><th>Points</th><th>Temps</th><th>Résultat</th><th>Date</th></tr></thead>
            <tbody>
            <?php foreach ($scores as $s): ?>
                <tr>
                    <td><?= htmlspecialchars($s['equipe_nom']) ?></td>
                    <td><?= htmlspecialchars($s['points']) ?></td>
                    <td><?= $s['temps_secondes'] !== null ? floor($s['temps_secondes']/60).' min '.($s['temps_secondes']%60).' s' : '—' ?></td>
                    <td><?= $s['reussi'] ? 'Sortis ✅' : 'Coincés ❌' ?></td>
                    <td><?= htmlspecialchars(date('d/m/Y', strtotime($s['date_partie']))) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($scores)): ?><tr><td colspan="5">Aucun score.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- ÉQUIPES -->
    <div class="card">
        <h2>Équipes</h2>
        <table>
            <thead><tr><th>ID</th><th>Nom</th><th>Code</th><th>Membres</th><th>Créée le</th></tr></thead>
            <tbody>
            <?php foreach ($equipes as $e): ?>
                <tr>
                    <td><?= htmlspecialchars($e['id']) ?></td>
                    <td><?= htmlspecialchars($e['nom']) ?></td>
                    <td><?= htmlspecialchars($e['code_invite']) ?></td>
                    <td><?= htmlspecialchars($e['nb_membres']) ?></td>
                    <td><?= htmlspecialchars(date('d/m/Y', strtotime($e['date_creation']))) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($equipes)): ?><tr><td colspan="5">Aucune équipe.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- INSCRITS -->
    <div class="card">
        <h2>Utilisateurs inscrits</h2>
        <p><a href="?export=csv" class="btn btn-info">⬇ Exporter en CSV</a></p>
        <table>
            <thead><tr><th>ID</th><th>Pseudo</th><th>Nom</th><th>Email</th><th>Naissance</th><th>Santé</th><th>Équipe</th><th>Rôle</th><th>Inscrit le</th></tr></thead>
            <tbody>
            <?php foreach ($utilisateurs as $u):
                // Alerte santé : liste des contre-indications déclarées "oui"
                $alertes = [];
                if (($u['sante_cardiaque'] ?? '') === 'oui')    $alertes[] = 'cardiaque';
                if (($u['sante_epilepsie'] ?? '') === 'oui')    $alertes[] = 'épilepsie';
                if (($u['sante_respiratoire'] ?? '') === 'oui') $alertes[] = 'respiratoire';
                if (($u['sante_claustro'] ?? '') === 'oui')     $alertes[] = 'claustrophobie';
            ?>
                <tr>
                    <td><?= htmlspecialchars($u['id']) ?></td>
                    <td><?= htmlspecialchars($u['pseudo']) ?></td>
                    <td><?= htmlspecialchars($u['prenom'].' '.$u['nom']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= $u['date_naissance'] ? htmlspecialchars(date('d/m/Y', strtotime($u['date_naissance']))) : '—' ?></td>
                    <td><?= $alertes ? '<span style="color:#c0392b;font-weight:bold;">⚠ ' . htmlspecialchars(implode(', ', $alertes)) . '</span>' : 'RAS' ?></td>
                    <td><?= htmlspecialchars($u['equipe_nom'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($u['role']) ?></td>
                    <td><?= htmlspecialchars(date('d/m/Y', strtotime($u['date_inscription']))) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

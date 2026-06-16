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
require_once('model/message.php');

// --- 2e barrière : connexion par un VRAI compte administrateur (en plus du htpasswd Apache) ---
require_once(__DIR__ . '/auth.php');

// --- Export CSV des inscrits ---
if (($_GET['export'] ?? '') === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="inscrits_backrooms_' . date('Ymd') . '.csv"');
    $sortie = fopen('php://output', 'w');
    fputs($sortie, "\xEF\xBB\xBF"); // BOM : accents corrects dans Excel
    fputcsv($sortie, ['ID', 'Pseudo', 'Nom', 'Prénom', 'Email', 'Téléphone', 'Naissance', 'Équipe', 'Rôle', 'Inscrit le',
                      'Cardiaque', 'Épilepsie', 'Respiratoire', 'Claustrophobie', 'Régime'], ';');
    foreach (get_tous_utilisateurs() as $u) {
        fputcsv($sortie, [$u['id'], $u['pseudo'], $u['nom'], $u['prenom'], $u['email'], $u['telephone'],
                          $u['date_naissance'], $u['equipe_nom'] ?? '', $u['role'], $u['date_inscription'],
                          $u['sante_cardiaque'], $u['sante_epilepsie'], $u['sante_respiratoire'], $u['sante_claustro'], $u['regime'] ?? ''], ';');
    }
    fclose($sortie);
    exit;
}

// --- Traitements (modération avis) — en POST + jeton CSRF (pas de lien GET forgeable) ---
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['action'], $_POST['id']) && csrf_verifie()) {
    $id = (int) $_POST['id'];
    if ($_POST['action'] === 'approuver') update_statut_commentaire($id, 'approuve');
    if ($_POST['action'] === 'refuser')   update_statut_commentaire($id, 'refuse');
    header('Location: ' . BASE_URL . '/gestion');
    exit;
}

// --- Traitements (statut d'une réservation) ---
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['resa'], $_POST['statut']) && csrf_verifie()) {
    $id = (int) $_POST['resa'];
    if (in_array($_POST['statut'], ['confirmee', 'annulee', 'en_attente'], true)) {
        update_statut_reservation($id, $_POST['statut']);
    }
    header('Location: ' . BASE_URL . '/gestion');
    exit;
}

// --- Traitements (saisie d'un score) ---
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['form_type'] ?? '') === 'score' && csrf_verifie()) {
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

// --- Traitements (messagerie : marquer lu / supprimer) — POST + CSRF ---
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['msg_action'], $_POST['msg_id']) && csrf_verifie()) {
    $mid = (int) $_POST['msg_id'];
    if ($_POST['msg_action'] === 'lu')    marquer_message_lu($mid);
    if ($_POST['msg_action'] === 'suppr') supprimer_message($mid);
    header('Location: ' . BASE_URL . '/gestion');
    exit;
}

// --- Données ---
$messages         = get_tous_messages();
$messages_non_lus = compter_messages_non_lus();
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

// Réservations actives par salle (barres)
$parSalle = ['facile' => 0, 'standard' => 0, 'hardcore' => 0];
foreach ($reservations as $r) {
    if ($r['statut'] !== 'annulee' && isset($parSalle[$r['salle']])) $parSalle[$r['salle']]++;
}
$maxSalle = max(1, max($parSalle));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Back-Office — <?= NOM_SITE ?></title>
    <link rel="icon" type="image/png" href="/view/img/favicon.png">
    <style>
        /* Polices auto-hébergées du site (mêmes fichiers que le front) */
        @font-face { font-family: 'VT323'; src: url('/view/fonts/vt323-400.woff2') format('woff2'); font-display: swap; }
        @font-face { font-family: 'Montserrat'; font-weight: 600; src: url('/view/fonts/montserrat-600.woff2') format('woff2'); font-display: swap; }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Montserrat', Arial, sans-serif;
            color: #f5f5f5;
            padding: 30px 20px 60px;
            background-color: #111;
            background-image: linear-gradient(rgba(13,12,9,.78), rgba(13,12,9,.86)), url('/view/img/fond.jpg');
            background-size: cover; background-position: center; background-attachment: fixed;
        }
        .wrap { max-width: 1100px; margin: auto; }
        h1 { font-family: 'VT323', monospace; font-size: 3.6rem; letter-spacing: 2px; line-height: 1; margin-bottom: 8px; }
        h2 { font-family: 'VT323', monospace; font-size: 2.3rem; letter-spacing: 2px; font-weight: 400; margin: 44px 0 18px; }
        a.lien { color: #d1b023; }

        /* --- Statistiques : 6 boîtes (maquette) --- */
        .stats { display: grid; grid-template-columns: repeat(6, 1fr); gap: 14px; }
        .stat {
            border: 2px solid #e6e0c8; border-radius: 4px; background: rgba(10,10,8,.55);
            text-align: center; padding: 16px 6px 12px;
        }
        .stat .num { font-family: 'VT323', monospace; font-size: 2.6rem; line-height: 1; display: block; }
        .stat .lbl { font-size: .62rem; letter-spacing: 1px; text-transform: uppercase; color: #d1b023; }

        /* --- Barres par salle (maquette) --- */
        .salles-barres { margin-top: 22px; display: flex; flex-direction: column; gap: 12px; }
        .barre-ligne { display: flex; align-items: center; gap: 14px; }
        .barre-ligne .nom { width: 110px; font-family: 'VT323', monospace; font-size: 1.3rem; letter-spacing: 1px; text-transform: uppercase; text-align: right; }
        .barre { flex: 1; height: 22px; background: #f5f5f0; border-radius: 11px; overflow: hidden; }
        .barre span { display: block; height: 100%; background: #d1b023; border-radius: 11px; }
        .barre-ligne .nb { width: 30px; font-family: 'VT323', monospace; font-size: 1.4rem; }

        /* --- Modération : cartes (maquette) --- */
        .avis-cartes { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 16px; }
        .avis-carte {
            border: 1px solid #d1b023; border-radius: 6px; background: rgba(10,10,8,.72);
            padding: 16px 14px; text-align: center; display: flex; flex-direction: column; gap: 8px;
        }
        .avis-carte .pseudo { color: #d1b023; font-weight: 600; }
        .avis-carte .date { color: #9a9a8a; font-size: .72rem; }
        .avis-carte .etoiles { color: #d1b023; font-size: 1.15rem; letter-spacing: 3px; }

        /* --- Messagerie (boîte de réception du formulaire de contact) --- */
        .badge-nonlu { font-family: 'Montserrat', Arial, sans-serif; font-size: .7rem; background: #d1b023; color: #14130d; padding: 3px 9px; border-radius: 10px; vertical-align: middle; }
        .msg-liste { display: flex; flex-direction: column; gap: 12px; }
        .msg-carte { border: 1px solid #3a382c; border-left: 4px solid #3a382c; border-radius: 6px; background: rgba(10,10,8,.6); padding: 14px 16px; }
        .msg-carte.msg-nonlu { border-left-color: #d1b023; background: rgba(40,36,18,.55); }
        .msg-head { display: flex; justify-content: space-between; flex-wrap: wrap; gap: 8px; font-size: .85rem; color: #cfcfcf; }
        .msg-date { color: #9a9a8a; font-size: .75rem; }
        .msg-sujet { margin: 8px 0 6px; font-weight: 600; color: #f5f5f5; display: flex; align-items: center; gap: 8px; }
        .msg-sujet .pastille { width: 9px; height: 9px; border-radius: 50%; background: #d1b023; display: inline-block; }
        .msg-corps { color: #d8d8d0; font-size: .9rem; line-height: 1.5; white-space: pre-wrap; margin-bottom: 12px; }
        .msg-actions { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }

        /* Responsive back-office */
        table { max-width: 100%; }
        @media (max-width: 760px) {
            .stats { grid-template-columns: repeat(3, 1fr); }
            .resa-table, table { display: block; overflow-x: auto; white-space: nowrap; -webkit-overflow-scrolling: touch; }
            .barre-ligne .nom { width: 78px; font-size: 1rem; }
            h1 { font-size: 2.6rem; }
        }
        @media (max-width: 460px) {
            .stats { grid-template-columns: repeat(2, 1fr); }
            body { padding: 18px 12px 50px; }
        }
        .avis-carte .texte { font-style: italic; font-size: .8rem; color: #e3e3e3; flex: 1; }
        .avis-actions { display: flex; gap: 10px; justify-content: center; }
        .btn { font-family: 'Montserrat', Arial, sans-serif; font-weight: 600; letter-spacing: 1px; text-transform: uppercase;
               border: none; border-radius: 3px; padding: 7px 14px; font-size: .72rem; cursor: pointer; }
        .btn-accepter { background: #d1b023; color: #1a1a1a; }
        .btn-refuser  { background: transparent; color: #e74c3c; border: 1px solid #e74c3c; }
        .badge-modere { font-size: .72rem; color: #9a9a8a; text-transform: uppercase; letter-spacing: 1px; }

        /* --- Réservations : lignes sombres (maquette) --- */
        .resa-table { width: 100%; border-collapse: separate; border-spacing: 0 8px; }
        .resa-table td { background: rgba(10,10,8,.78); padding: 10px 14px; font-size: .85rem; }
        .resa-table tr td:first-child { border-radius: 5px 0 0 5px; font-weight: 600; }
        .resa-table tr td:last-child  { border-radius: 0 5px 5px 0; }
        .pill { display: inline-block; padding: 3px 12px; border-radius: 3px; font-size: .72rem; font-weight: 600; color: #1a1a1a; }
        .pill-attente  { background: #e67e22; }
        .pill-confirme { background: #2ecc71; }
        .pill-annule   { background: #e74c3c; color: #fff; }
        .btn-carre { width: 30px; height: 30px; border: none; border-radius: 4px; font-size: 1rem; font-weight: bold; cursor: pointer; color: #fff; }
        .btn-ok  { background: #2ecc71; }
        .btn-non { background: #e74c3c; }
        .actions-resa { display: flex; gap: 8px; justify-content: flex-end; }

        /* --- Tableaux secondaires (équipes, inscrits, scores) --- */
        .tbl { width: 100%; border-collapse: collapse; }
        .tbl th { font-size: .68rem; text-transform: uppercase; letter-spacing: 1px; color: #d1b023; text-align: left; padding: 6px 10px; border-bottom: 1px solid #3a3320; }
        .tbl td { padding: 8px 10px; font-size: .82rem; border-bottom: 1px solid rgba(255,255,255,.06); }
        .alerte-sante { color: #ff8a75; font-weight: 600; }

        /* --- Formulaire score --- */
        form.inline { display: grid; grid-template-columns: repeat(auto-fit, minmax(110px, 1fr)); gap: 10px; align-items: end;
                      border: 1px solid #3a3320; border-radius: 6px; padding: 16px; background: rgba(10,10,8,.6); }
        form.inline label { font-size: .68rem; text-transform: uppercase; letter-spacing: 1px; color: #d1b023; display: block; margin-bottom: 4px; }
        form.inline input, form.inline select { width: 100%; padding: 8px; background: #1d1c15; border: 1px solid #3a3320; border-radius: 4px; color: #f5f5f5; }

        @media (max-width: 900px) { .stats { grid-template-columns: repeat(3, 1fr); } }
        @media (max-width: 700px) {
            body { padding: 16px 10px 40px; }
            .stats { grid-template-columns: repeat(2, 1fr); }
            h1 { font-size: 2.6rem; } h2 { font-size: 1.8rem; }
            .resa-table, .tbl { display: block; overflow-x: auto; white-space: nowrap; -webkit-overflow-scrolling: touch; }
            .barre-ligne .nom { width: 86px; font-size: 1.05rem; }
        }
    </style>
</head>
<body>
<div class="wrap">
    <h1>BACK-OFFICE</h1>
    <p>
        <a class="lien" href="<?= BASE_URL ?>/">← Retour au site public</a>
        &nbsp;·&nbsp; Connecté : <strong><?= htmlspecialchars($_SESSION['admin_pseudo'] ?? 'admin') ?></strong>
        &nbsp;·&nbsp; <a class="lien" href="<?= BASE_URL ?>/gestion?logout=1">Déconnexion</a>
    </p>

    <!-- ============ STATISTIQUES ============ -->
    <h2>STATISTIQUES :</h2>
    <div class="stats">
        <div class="stat"><span class="num"><?= $total_inscrits ?></span><span class="lbl">Inscrits</span></div>
        <div class="stat"><span class="num"><?= $total_equipes ?></span><span class="lbl">Équipes</span></div>
        <div class="stat"><span class="num"><?= $total_resa ?></span><span class="lbl">Réservations</span></div>
        <div class="stat"><span class="num"><?= $resa_en_attente ?></span><span class="lbl">En attente</span></div>
        <div class="stat"><span class="num"><?= $avis_en_attente ?></span><span class="lbl">Avis à modérer</span></div>
        <div class="stat"><span class="num"><?= $note_moyenne ?>/5</span><span class="lbl">Note moyenne</span></div>
    </div>

    <div class="salles-barres">
        <?php foreach ($parSalle as $s => $n): ?>
            <div class="barre-ligne">
                <span class="nom"><?= htmlspecialchars($s) ?></span>
                <div class="barre"><span style="width:<?= round($n / $maxSalle * 100) ?>%"></span></div>
                <span class="nb"><?= $n ?></span>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- ============ MESSAGERIE (boîte de réception du formulaire de contact) ============ -->
    <h2>MESSAGERIE<?php if ($messages_non_lus): ?> <span class="badge-nonlu"><?= $messages_non_lus ?> non lu<?= $messages_non_lus > 1 ? 's' : '' ?></span><?php endif; ?></h2>
    <?php if (empty($messages)): ?>
        <p>Aucun message reçu pour le moment.</p>
    <?php else: ?>
        <div class="msg-liste">
            <?php foreach ($messages as $m): ?>
                <div class="msg-carte<?= $m['lu'] ? '' : ' msg-nonlu' ?>">
                    <div class="msg-head">
                        <span><strong><?= htmlspecialchars($m['nom']) ?></strong>
                            &lt;<a class="lien" href="mailto:<?= htmlspecialchars($m['email']) ?>"><?= htmlspecialchars($m['email']) ?></a>&gt;</span>
                        <span class="msg-date"><?= htmlspecialchars(date('d/m/Y - H\hi', strtotime($m['date_creation']))) ?></span>
                    </div>
                    <div class="msg-sujet"><?php if (!$m['lu']): ?><span class="pastille" title="Non lu"></span><?php endif; ?><?= htmlspecialchars($m['sujet']) ?></div>
                    <p class="msg-corps"><?= htmlspecialchars($m['message']) ?></p>
                    <form method="post" class="msg-actions">
                        <?= csrf_input() ?>
                        <input type="hidden" name="msg_id" value="<?= $m['id'] ?>">
                        <?php if (!$m['lu']): ?>
                            <button type="submit" name="msg_action" value="lu" class="btn btn-accepter">Marquer comme lu</button>
                        <?php endif; ?>
                        <a class="lien" href="mailto:<?= htmlspecialchars($m['email']) ?>?subject=RE:%20<?= rawurlencode($m['sujet']) ?>">Répondre par mail</a>
                        <button type="submit" name="msg_action" value="suppr" class="btn btn-refuser" onclick="return confirm('Supprimer définitivement ce message ?');">Supprimer</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- ============ MODÉRATION DES AVIS ============ -->
    <h2>MODÉRATION DES AVIS</h2>
    <?php if (empty($commentaires)): ?>
        <p>Aucun avis pour le moment.</p>
    <?php else: ?>
        <div class="avis-cartes">
            <?php foreach ($commentaires as $c): ?>
                <div class="avis-carte">
                    <span class="pseudo"><?= htmlspecialchars($c['pseudo']) ?></span>
                    <span class="date"><?= htmlspecialchars(date('d/m/Y - H\hi', strtotime($c['date_creation']))) ?></span>
                    <span class="etoiles"><?= str_repeat('★', (int) $c['note']) . str_repeat('☆', 5 - (int) $c['note']) ?></span>
                    <p class="texte"><?= htmlspecialchars($c['texte']) ?></p>
                    <form method="post" class="avis-actions">
                        <?= csrf_input() ?>
                        <input type="hidden" name="id" value="<?= $c['id'] ?>">
                        <?php if ($c['statut'] === 'en_attente'): ?>
                            <button type="submit" name="action" value="approuver" class="btn btn-accepter">Accepter</button>
                            <button type="submit" name="action" value="refuser" class="btn btn-refuser">Refuser</button>
                        <?php elseif ($c['statut'] === 'approuve'): ?>
                            <span class="badge-modere">✔ publié</span>
                            <button type="submit" name="action" value="refuser" class="btn btn-refuser">Retirer</button>
                        <?php else: ?>
                            <span class="badge-modere">✖ refusé</span>
                            <button type="submit" name="action" value="approuver" class="btn btn-accepter">Publier</button>
                        <?php endif; ?>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- ============ RÉSERVATIONS ============ -->
    <h2>RÉSERVATIONS :</h2>
    <?php if (empty($reservations)): ?>
        <p>Aucune réservation.</p>
    <?php else: ?>
        <table class="resa-table">
            <?php foreach ($reservations as $r): ?>
                <tr>
                    <td><?= htmlspecialchars($r['equipe_nom']) ?></td>
                    <td><?= htmlspecialchars(ucfirst($r['salle'])) ?></td>
                    <td><?= htmlspecialchars(date('d/m/y', strtotime($r['date_session']))) ?></td>
                    <td><?= htmlspecialchars($r['nb_joueurs']) ?> joueurs</td>
                    <td>
                        <?php if ($r['statut'] === 'confirmee'): ?><span class="pill pill-confirme">Confirmé</span>
                        <?php elseif ($r['statut'] === 'annulee'): ?><span class="pill pill-annule">Annulé</span>
                        <?php else: ?><span class="pill pill-attente">En attente</span><?php endif; ?>
                    </td>
                    <td>
                        <form method="post" class="actions-resa">
                            <?= csrf_input() ?>
                            <input type="hidden" name="resa" value="<?= $r['id'] ?>">
                            <button type="submit" name="statut" value="confirmee" class="btn-carre btn-ok" title="Confirmer">✓</button>
                            <button type="submit" name="statut" value="annulee" class="btn-carre btn-non" title="Annuler">✕</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <!-- ============ SAISIE DES SCORES ============ -->
    <h2>SAISIR UN SCORE</h2>
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
            <div><button type="submit" class="btn btn-accepter" style="width:100%;">Enregistrer</button></div>
        </form>
    <?php endif; ?>

    <?php if (!empty($scores)): ?>
        <table class="tbl" style="margin-top:16px;">
            <thead><tr><th>Équipe</th><th>Points</th><th>Temps</th><th>Résultat</th><th>Date</th></tr></thead>
            <tbody>
            <?php foreach ($scores as $s): ?>
                <tr>
                    <td><?= htmlspecialchars($s['equipe_nom']) ?></td>
                    <td><?= htmlspecialchars($s['points']) ?></td>
                    <td><?= $s['temps_secondes'] !== null ? floor($s['temps_secondes']/60).' min '.($s['temps_secondes']%60).' s' : '—' ?></td>
                    <td><?= $s['reussi'] ? 'Sortis ✅' : 'Coincés ❌' ?></td>
                    <td><?= htmlspecialchars(date('d/m/y', strtotime($s['date_partie']))) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- ============ ÉQUIPES ============ -->
    <h2>ÉQUIPES</h2>
    <table class="tbl">
        <thead><tr><th>ID</th><th>Nom</th><th>Code</th><th>Membres</th><th>Créée le</th></tr></thead>
        <tbody>
        <?php foreach ($equipes as $e): ?>
            <tr>
                <td><?= htmlspecialchars($e['id']) ?></td>
                <td><?= htmlspecialchars($e['nom']) ?></td>
                <td><?= htmlspecialchars($e['code_invite']) ?></td>
                <td><?= htmlspecialchars($e['nb_membres']) ?></td>
                <td><?= htmlspecialchars(date('d/m/y', strtotime($e['date_creation']))) ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($equipes)): ?><tr><td colspan="5">Aucune équipe.</td></tr><?php endif; ?>
        </tbody>
    </table>

    <!-- ============ INSCRITS ============ -->
    <h2>UTILISATEURS INSCRITS</h2>
    <p style="margin-bottom:10px;"><a href="?export=csv" class="btn btn-accepter" style="text-decoration:none;">⬇ Exporter en CSV</a></p>
    <table class="tbl">
        <thead><tr><th>ID</th><th>Pseudo</th><th>Nom</th><th>Email</th><th>Naissance</th><th>Santé</th><th>Régime</th><th>Équipe</th><th>Rôle</th><th>Inscrit le</th></tr></thead>
        <tbody>
        <?php foreach ($utilisateurs as $u):
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
                <td><?= $u['date_naissance'] ? htmlspecialchars(date('d/m/y', strtotime($u['date_naissance']))) : '—' ?></td>
                <td><?= $alertes ? '<span class="alerte-sante">⚠ ' . htmlspecialchars(implode(', ', $alertes)) . '</span>' : 'RAS' ?></td>
                <td><?= $u['regime'] && $u['regime'] !== 'aucun' ? htmlspecialchars(ucfirst(str_replace('_', ' ', $u['regime']))) : '—' ?></td>
                <td><?= htmlspecialchars($u['equipe_nom'] ?? '—') ?></td>
                <td><?= htmlspecialchars($u['role']) ?></td>
                <td><?= htmlspecialchars(date('d/m/y', strtotime($u['date_inscription']))) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>

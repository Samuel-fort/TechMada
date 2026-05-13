<?= $this->extend('layouts/app') ?>

<?= $this->section('topbar_actions') ?>
    <a href="<?= route_to('employe.create') ?>" 
       class="btn-forest" 
       style="padding: 7px 14px; font-size: 0.82rem">
        <i class="bi bi-plus-lg"></i> Nouvelle demande
    </a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Messages de succès -->
<?php if (session()->has('message')): ?>
    <div class="flash flash-success">
        <i class="bi bi-check-circle-fill"></i>
        <?= session('message') ?>
    </div>
<?php endif; ?>

<!-- STATISTIQUES -->
<div class="metrics">
    
    <!-- Demandes en attente -->
    <div class="metric">
        <div class="metric-top">
            <div class="metric-icon mi-amber">
                <i class="bi bi-hourglass-split"></i>
            </div>
        </div>
        <div class="metric-val"><?= $stats['en_attente'] ?? 0 ?></div>
        <div class="metric-label">En attente</div>
    </div>
    
    <!-- Demandes approuvées -->
    <div class="metric">
        <div class="metric-top">
            <div class="metric-icon mi-green">
                <i class="bi bi-check-circle"></i>
            </div>
        </div>
        <div class="metric-val"><?= $stats['approuvees'] ?? 0 ?></div>
        <div class="metric-label">Approuvées</div>
    </div>
    
    <!-- Jours restants -->
    <div class="metric">
        <div class="metric-top">
            <div class="metric-icon mi-forest">
                <i class="bi bi-calendar-check"></i>
            </div>
        </div>
        <div class="metric-val"><?= $stats['jours_restants'] ?? 0 ?></div>
        <div class="metric-label">Jours restants</div>
        <div class="metric-sub">sur 30 cette année</div>
    </div>
    
    <!-- Demandes refusées -->
    <div class="metric">
        <div class="metric-top">
            <div class="metric-icon mi-red">
                <i class="bi bi-x-circle"></i>
            </div>
        </div>
        <div class="metric-val"><?= $stats['refusees'] ?? 0 ?></div>
        <div class="metric-label">Refusée</div>
    </div>

</div>

<!-- MES SOLDES DE CONGÉS -->
<div class="data-card">
    <div class="data-card-head">
        <h3>Mes soldes de congés — 2026</h3>
    </div>
    <div style="padding: 1rem 1.25rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem">
        
        <?php if (!empty($soldes)): ?>
            <?php foreach ($soldes as $solde): ?>
                <div class="solde-card" style="margin: 0">
                    <div class="solde-header">
                        <span class="solde-type"><?= $solde['type_conge'] ?></span>
                        <span class="solde-nums">
                            <strong><?= $solde['jours_restants'] ?></strong> / <?= $solde['jours_total'] ?> j
                        </span>
                    </div>
                    <div class="solde-bar">
                        <div class="solde-fill <?= $solde['jours_restants'] <= 5 ? 'danger' : ($solde['jours_restants'] <= 10 ? 'warn' : '') ?>" 
                             style="width: <?= ($solde['jours_restants'] / $solde['jours_total'] * 100) ?>%">
                        </div>
                    </div>
                    <div class="solde-label">
                        <?= $solde['jours_restants'] ?> jours restants · <?= $solde['jours_pris'] ?> pris
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty">
                <i class="bi bi-inbox"></i>
                <p>Aucun solde de congé disponible</p>
            </div>
        <?php endif; ?>
    
    </div>
</div>

<!-- MES DERNIÈRES DEMANDES -->
<div class="data-card">
    <div class="data-card-head">
        <h3>Mes dernières demandes</h3>
        <a href="<?= route_to('employe.index') ?>" 
           style="font-size: 0.8rem; color: var(--forest); text-decoration: none">
            Voir tout →
        </a>
    </div>
    
    <?php if (!empty($demandes)): ?>
        <table class="tbl">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Du</th>
                    <th>Au</th>
                    <th>Durée</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (array_slice($demandes, 0, 3) as $demande): // Afficher seulement les 3 dernières ?>
                    <tr>
                        <td>
                            <span class="type-badge t-<?= str_replace(' ', '-', strtolower($demande['type_conge'])) ?>">
                                <?= $demande['type_conge'] ?>
                            </span>
                        </td>
                        <td class="td-muted"><?= formatDate($demande['date_debut']) ?></td>
                        <td class="td-muted"><?= formatDate($demande['date_fin']) ?></td>
                        <td class="td-mono"><?= getDaysCount($demande['date_debut'], $demande['date_fin']) ?> j</td>
                        <td>
                            <span class="statut s-<?= str_replace('_', '', $demande['statut']) ?>">
                                <?= ucfirst(str_replace('_', ' ', $demande['statut'])) ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($demande['statut'] === 'en_attente'): ?>
                                <button class="btn-sm btn-cancel" 
                                        onclick="confirmerAnnulation(<?= $demande['id'] ?>)">
                                    <i class="bi bi-x"></i> Annuler
                                </button>
                            <?php else: ?>
                                <span class="td-muted" style="font-size: 0.75rem">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="empty">
            <i class="bi bi-inbox"></i>
            <p>Vous n'avez pas encore fait de demande de congé</p>
        </div>
    <?php endif; ?>

</div>

<?= $this->endSection() ?>

<?php
// Fonctions utilitaires pour la vue
function formatDate($date) {
    return date('j M Y', strtotime($date));
}

function getDaysCount($debut, $fin) {
    $d1 = new DateTime($debut);
    $d2 = new DateTime($fin);
    $interval = $d1->diff($d2);
    return $interval->days + 1;
}

function confirmerAnnulation($id) {
    if (confirm('Êtes-vous sûr de vouloir annuler cette demande?')) {
        // Envoyer vers le formulaire d'annulation
        window.location.href = '<?= route_to('employe.cancel', $id) ?>';
    }
}
?>

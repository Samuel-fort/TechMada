<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<!-- Messages -->
<?php if (session()->has('message')): ?>
    <div class="flash flash-success">
        <i class="bi bi-check-circle-fill"></i>
        <?= session('message') ?>
    </div>
<?php endif; ?>

<!-- FILTRES -->
<div style="display: flex; gap: 8px; margin-bottom: 1.25rem; flex-wrap: wrap">
    <button onclick="filtrerDemandes('tous')" 
            class="btn-filter" 
            style="padding: 6px 14px; border-radius: 20px; font-size: 0.8rem; font-weight: 500; border: 1.5px solid var(--forest); background: var(--forest); color: var(--white); cursor: pointer">
        Tous (<?= count($demandes) ?>)
    </button>
    <button onclick="filtrerDemandes('en_attente')" 
            class="btn-filter" 
            style="padding: 6px 14px; border-radius: 20px; font-size: 0.8rem; font-weight: 500; border: 1.5px solid var(--border); background: var(--white); color: var(--muted); cursor: pointer">
        En attente (<?= count(array_filter($demandes, fn($d) => $d['statut'] === 'en_attente')) ?>)
    </button>
    <button onclick="filtrerDemandes('approuvee')" 
            class="btn-filter" 
            style="padding: 6px 14px; border-radius: 20px; font-size: 0.8rem; font-weight: 500; border: 1.5px solid var(--border); background: var(--white); color: var(--muted); cursor: pointer">
        Approuvées (<?= count(array_filter($demandes, fn($d) => $d['statut'] === 'approuvee')) ?>)
    </button>
    <button onclick="filtrerDemandes('refusee')" 
            class="btn-filter" 
            style="padding: 6px 14px; border-radius: 20px; font-size: 0.8rem; font-weight: 500; border: 1.5px solid var(--border); background: var(--white); color: var(--muted); cursor: pointer">
        Refusées (<?= count(array_filter($demandes, fn($d) => $d['statut'] === 'refusee')) ?>)
    </button>
    
    <select class="f-select" 
            style="font-size: 0.8rem; padding: 6px 10px; width: auto; margin-left: auto"
            onchange="filtrerDepartement(this.value)">
        <option value="">Tous les départements</option>
        <?php foreach ($departements as $dept): ?>
            <option value="<?= $dept['id'] ?>"><?= $dept['nom'] ?></option>
        <?php endforeach; ?>
    </select>
</div>

<!-- TABLEAU DES DEMANDES -->
<div class="data-card">
    <div class="data-card-head">
        <h3>Toutes les demandes</h3>
    </div>

    <?php if (!empty($demandes)): ?>
        <table class="tbl">
            <thead>
                <tr>
                    <th>Employé</th>
                    <th>Type</th>
                    <th>Période</th>
                    <th>Durée</th>
                    <th>Solde dispo</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($demandes as $demande): ?>
                    <tr class="demande-row" data-statut="<?= $demande['statut'] ?>" data-dept="<?= $demande['departement_id'] ?>">
                        
                        <!-- Employé -->
                        <td>
                            <div class="profile-row">
                                <div class="avatar av-green" style="width: 32px; height: 32px; font-size: 0.7rem">
                                    <?= getInitials($demande['employe_nom']) ?>
                                </div>
                                <div class="profile-info">
                                    <div class="pname"><?= $demande['employe_nom'] ?></div>
                                    <div class="pdept">
                                        <?= $demande['departement_nom'] ?> · 
                                        <?= date('j M', strtotime($demande['date_debut'])) ?> → 
                                        <?= date('j M', strtotime($demande['date_fin'])) ?>
                                    </div>
                                </div>
                            </div>
                        </td>
                        
                        <!-- Type -->
                        <td>
                            <span class="type-badge t-<?= str_replace(' ', '-', strtolower($demande['type_conge'])) ?>">
                                <?= $demande['type_conge'] ?>
                            </span>
                        </td>
                        
                        <!-- Période -->
                        <td class="td-muted" style="font-size: 0.8rem">
                            <?= date('d/m', strtotime($demande['date_debut'])) ?> – 
                            <?= date('d/m/Y', strtotime($demande['date_fin'])) ?>
                        </td>
                        
                        <!-- Durée -->
                        <td class="td-mono">
                            <?php 
                                $d1 = new DateTime($demande['date_debut']);
                                $d2 = new DateTime($demande['date_fin']);
                                $interval = $d1->diff($d2);
                                echo $interval->days + 1;
                            ?> j
                        </td>
                        
                        <!-- Solde disponible -->
                        <td>
                            <?php 
                                $color = 'var(--success)';
                                $warning = '';
                                if ($demande['jours_disponibles'] < 0) {
                                    $color = 'var(--danger)';
                                    $warning = ' ⚠ insuffisant';
                                } elseif ($demande['jours_disponibles'] <= 5) {
                                    $color = 'var(--warn)';
                                }
                            ?>
                            <span style="font-family: 'DM Mono', monospace; font-size: 0.82rem; color: <?= $color ?>; font-weight: 500">
                                <?= $demande['jours_disponibles'] ?> j
                            </span>
                            <span style="font-size: 0.72rem; color: <?= $color ?>"><?= $warning ?></span>
                        </td>
                        
                        <!-- Statut -->
                        <td>
                            <span class="statut s-<?= str_replace('_', '', $demande['statut']) ?>">
                                <?= ucfirst(str_replace('_', ' ', $demande['statut'])) ?>
                            </span>
                        </td>
                        
                        <!-- Actions -->
                        <td>
                            <?php if ($demande['statut'] === 'en_attente'): ?>
                                <div class="action-btns">
                                    <button class="btn-sm btn-approve" 
                                            onclick="ouvrirApprouve(<?= $demande['id'] ?>)"
                                            <?= $demande['jours_disponibles'] < 0 ? 'disabled style="opacity: 0.4; cursor: not-allowed;"' : '' ?>>
                                        <i class="bi bi-check-lg"></i> Approuver
                                    </button>
                                    <button class="btn-sm btn-refuse" 
                                            onclick="ouvrirRefus(<?= $demande['id'] ?>)">
                                        <i class="bi bi-x-lg"></i> Refuser
                                    </button>
                                </div>
                            <?php else: ?>
                                <span class="td-muted" style="font-size: 0.75rem">
                                    Traité par <?= $demande['traite_par_nom'] ?? 'RH' ?>
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="empty">
            <i class="bi bi-inbox"></i>
            <p>Aucune demande de congé</p>
        </div>
    <?php endif; ?>

</div>

<!-- Scripts de filtrage -->
<script>
function filtrerDemandes(statut) {
    document.querySelectorAll('.demande-row').forEach(row => {
        if (statut === 'tous' || row.dataset.statut === statut) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function filtrerDepartement(deptId) {
    document.querySelectorAll('.demande-row').forEach(row => {
        if (!deptId || row.dataset.dept === deptId) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function ouvrirApprouve(id) {
    // Rediriger vers la page d'approbation
    fetch('<?= route_to('rh.approve') ?>', {
        method: 'POST',
        body: new FormData(form),
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function ouvrirRefus(id) {
    if (confirm('Êtes-vous sûr de vouloir refuser cette demande?')) {
        const commentaire = prompt('Commentaire (optionnel):');
        
        const form = new FormData();
        form.append('id', id);
        if (commentaire) {
            form.append('commentaire_rh', commentaire);
        }
        
        fetch('<?= route_to('rh.refuse') ?>', {
            method: 'POST',
            body: form,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
}
</script>

<?= $this->endSection() ?>

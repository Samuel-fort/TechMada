<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1>Tableau de bord RH</h1>
            <p class="text-muted">Vue d'ensemble des demandes de congé</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card metrics-card">
                <div class="card-body">
                    <h6 class="card-title">En attente de traitement</h6>
                    <div class="metric-value"><?= $pending_count ?? 0 ?></div>
                    <small class="text-muted">demandes</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card metrics-card">
                <div class="card-body">
                    <h6 class="card-title">Approuvées ce mois</h6>
                    <div class="metric-value"><?= $approved_this_month ?? 0 ?></div>
                    <small class="text-muted">ce mois</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card metrics-card">
                <div class="card-body">
                    <h6 class="card-title">Nombre d'employés</h6>
                    <div class="metric-value"><?= $total_employes ?? 0 ?></div>
                    <small class="text-muted">actifs</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Demandes récentes en attente</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($recent_demandes)): ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Employé</th>
                                        <th>Type de congé</th>
                                        <th>Dates</th>
                                        <th>Jours</th>
                                        <th>Solde disponible</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_demandes as $demande): ?>
                                        <tr>
                                            <td><?= $demande['employe_nom'] ?></td>
                                            <td>
                                                <span class="badge" style="background-color: #<?= substr(md5($demande['type_conge_nom']), 0, 6) ?>;">
                                                    <?= $demande['type_conge_nom'] ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?= date_format(date_create($demande['date_debut']), 'd/m/Y') ?> 
                                                à 
                                                <?= date_format(date_create($demande['date_fin']), 'd/m/Y') ?>
                                            </td>
                                            <td><?= $demande['nombre_jours'] ?> j</td>
                                            <td>
                                                <strong><?= $demande['solde_disponible'] ?? '-' ?></strong>
                                            </td>
                                            <td>
                                                <a href="<?= route_to('rh.index') ?>" class="btn btn-sm btn-primary">
                                                    Traiter
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">Aucune demande en attente</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

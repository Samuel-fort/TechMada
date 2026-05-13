<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1>Tableau de bord administrateur</h1>
            <p class="text-muted">Vue d'ensemble du système TechMada RH</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card metrics-card">
                <div class="card-body">
                    <h6 class="card-title">Employés actifs</h6>
                    <div class="metric-value"><?= $total_employes ?? 0 ?></div>
                    <small class="text-muted">inscrits</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card metrics-card">
                <div class="card-body">
                    <h6 class="card-title">Demandes en attente</h6>
                    <div class="metric-value"><?= $pending_demandes ?? 0 ?></div>
                    <small class="text-muted">à traiter</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card metrics-card">
                <div class="card-body">
                    <h6 class="card-title">Approuvées ce mois</h6>
                    <div class="metric-value"><?= $approved_this_month ?? 0 ?></div>
                    <small class="text-muted">ce mois</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card metrics-card">
                <div class="card-body">
                    <h6 class="card-title">Départements</h6>
                    <div class="metric-value"><?= $total_departements ?? 0 ?></div>
                    <small class="text-muted">actifs</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Gestion rapide</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="<?= route_to('admin.employes') ?>" class="list-group-item list-group-item-action">
                            <strong>Gérer les employés</strong>
                            <small class="d-block text-muted">Ajouter, modifier, supprimer</small>
                        </a>
                        <a href="<?= route_to('admin.departements') ?>" class="list-group-item list-group-item-action">
                            <strong>Gérer les départements</strong>
                            <small class="d-block text-muted">Créer et configurer</small>
                        </a>
                        <a href="<?= route_to('admin.types_conge') ?>" class="list-group-item list-group-item-action">
                            <strong>Types de congé</strong>
                            <small class="d-block text-muted">Définir les jours max</small>
                        </a>
                        <a href="<?= route_to('admin.soldes') ?>" class="list-group-item list-group-item-action">
                            <strong>Gestion des soldes</strong>
                            <small class="d-block text-muted">Visualiser et ajuster</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Dernières demandes</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($recent_demandes)): ?>
                        <?php foreach (array_slice($recent_demandes, 0, 5) as $demande): ?>
                            <div class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between">
                                    <strong><?= $demande['employe_nom'] ?></strong>
                                    <span class="badge bg-warning">Attente</span>
                                </div>
                                <small class="text-muted">
                                    <?= $demande['type_conge_nom'] ?> · 
                                    <?= date_format(date_create($demande['date_debut']), 'd/m') ?> 
                                    - 
                                    <?= date_format(date_create($demande['date_fin']), 'd/m/Y') ?>
                                </small>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">Aucune demande récente</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

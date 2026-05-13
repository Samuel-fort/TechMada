<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1>Mon profil</h1>
            <p class="text-muted">Informations personnelles et congés</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Informations personnelles</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <td><strong>Nom</strong></td>
                            <td><?= $employe['nom'] ?? 'N/A' ?></td>
                        </tr>
                        <tr>
                            <td><strong>Email</strong></td>
                            <td><?= $employe['email'] ?? 'N/A' ?></td>
                        </tr>
                        <tr>
                            <td><strong>Département</strong></td>
                            <td><?= $employe['departement'] ?? 'N/A' ?></td>
                        </tr>
                        <tr>
                            <td><strong>Rôle</strong></td>
                            <td>
                                <span class="badge bg-info">
                                    <?= ucfirst($employe['role'] ?? 'employe') ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Département</strong></td>
                            <td>
                                <?= $employe['departement_nom'] ?? 'Non assigné' ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Mes soldes de congé (<?= date('Y') ?>)</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($soldes)): ?>
                        <?php foreach ($soldes as $solde): ?>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <strong><?= $solde['type_conge_nom'] ?></strong>
                                    <span class="badge bg-primary">
                                        <?= $solde['jours_restants'] ?>/<?= $solde['jours_attribues'] ?>
                                    </span>
                                </div>
                                <div class="progress" style="height: 1.5rem;">
                                    <?php
                                    $percentage = $solde['jours_attribues'] > 0
                                        ? round(($solde['jours_restants'] / $solde['jours_attribues']) * 100)
                                        : 0;
                                    $class = $percentage > 50 ? 'success' : ($percentage > 20 ? 'warning' : 'danger');
                                    ?>
                                    <div class="progress-bar bg-<?= $class ?>" style="width: <?= $percentage ?>%;">
                                        <?= $percentage ?>%
                                    </div>
                                </div>
                                <small class="text-muted">
                                    <?= $solde['jours_utilises'] ?> jours utilisés
                                </small>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">Aucun solde disponible</p>
                    <?php endif; ?>

                    <div class="mt-4">
                        <a href="<?= route_to('employe.create') ?>" class="btn btn-primary btn-sm w-100">
                            Demander un congé
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Mes dernières demandes</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($demandes)): ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Période</th>
                                        <th>Jours</th>
                                        <th>Statut</th>
                                        <th>Date demande</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($demandes, 0, 10) as $demande): ?>
                                        <tr>
                                            <td><?= $demande['type_conge_nom'] ?></td>
                                            <td>
                                                <?= date_format(date_create($demande['date_debut']), 'd/m') ?>
                                                -
                                                <?= date_format(date_create($demande['date_fin']), 'd/m/Y') ?>
                                            </td>
                                            <td><?= $demande['nombre_jours'] ?></td>
                                            <td>
                                                <?php
                                                $class = match($demande['statut']) {
                                                    'approuvee' => 'success',
                                                    'refusee' => 'danger',
                                                    'en_attente' => 'warning',
                                                    'annulee' => 'secondary',
                                                    default => 'info'
                                                };
                                                ?>
                                                <span class="badge bg-<?= $class ?>">
                                                    <?= ucfirst(str_replace('_', ' ', $demande['statut'])) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?= date_format(date_create($demande['date_demande']), 'd/m/Y') ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center py-4">Aucune demande</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

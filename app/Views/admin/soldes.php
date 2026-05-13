<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1>Gestion des soldes</h1>
            <p class="text-muted">Vue complète des soldes de congé pour tous les employés</p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="<?= route_to('admin.soldes') ?>" class="row g-3">
                        <div class="col-md-3">
                            <label for="annee" class="form-label">Année</label>
                            <input type="number" id="annee" name="annee" class="form-control" value="<?= get('annee') ?? date('Y') ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="departement_id" class="form-label">Département</label>
                            <select id="departement_id" name="departement_id" class="form-select">
                                <option value="">Tous</option>
                                <?php if (!empty($departements)): ?>
                                    <?php foreach ($departements as $dept): ?>
                                        <option value="<?= $dept['id'] ?>" <?= (get('departement_id') == $dept['id']) ? 'selected' : '' ?>>
                                            <?= $dept['nom'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">Filtrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <?php if (!empty($soldes)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Employé</th>
                                        <th>Département</th>
                                        <th>Type de congé</th>
                                        <th>Attribués</th>
                                        <th>Utilisés</th>
                                        <th>Restants</th>
                                        <th>État</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($soldes as $solde): ?>
                                        <tr>
                                            <td>
                                                <strong><?= $solde['employe_nom'] ?></strong>
                                            </td>
                                            <td><?= $solde['departement'] ?></td>
                                            <td><?= $solde['type_conge_nom'] ?></td>
                                            <td><?= $solde['jours_attribues'] ?></td>
                                            <td><?= $solde['jours_utilises'] ?></td>
                                            <td>
                                                <strong><?= $solde['jours_restants'] ?></strong>
                                            </td>
                                            <td>
                                                <?php
                                                $percentage = $solde['jours_attribues'] > 0 
                                                    ? round(($solde['jours_restants'] / $solde['jours_attribues']) * 100) 
                                                    : 0;
                                                if ($percentage > 50) {
                                                    $class = 'success';
                                                } elseif ($percentage > 20) {
                                                    $class = 'warning';
                                                } else {
                                                    $class = 'danger';
                                                }
                                                ?>
                                                <div class="progress" style="height: 1.5rem;">
                                                    <div class="progress-bar bg-<?= $class ?>" role="progressbar" style="width: <?= $percentage ?>%;" aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100">
                                                        <?= $percentage ?>%
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center py-4">Aucune donnée à afficher</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

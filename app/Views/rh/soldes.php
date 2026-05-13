<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1>Gestion des soldes de congé</h1>
            <p class="text-muted">Suivi des jours de congé disponibles par employé</p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="<?= route_to('rh.soldes') ?>" class="row g-3">
                        <div class="col-md-3">
                            <label for="employe_id" class="form-label">Employé</label>
                            <select id="employe_id" name="employe_id" class="form-select">
                                <option value="">Tous</option>
                                <?php if (!empty($employes)): ?>
                                    <?php foreach ($employes as $e): ?>
                                        <option value="<?= $e['id'] ?>" <?= (get('employe_id') == $e['id']) ? 'selected' : '' ?>>
                                            <?= $e['nom'] ?> (<?= $e['departement'] ?>)
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="annee" class="form-label">Année</label>
                            <input type="number" id="annee" name="annee" class="form-control" value="<?= get('annee') ?? date('Y') ?>">
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
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Employé</th>
                                        <th>Département</th>
                                        <th>Type de congé</th>
                                        <th>Jours attribués</th>
                                        <th>Jours utilisés</th>
                                        <th>Jours restants</th>
                                        <th>Pourcentage</th>
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
                                                $badgeClass = $percentage > 50 ? 'success' : ($percentage > 20 ? 'warning' : 'danger');
                                                ?>
                                                <span class="badge bg-<?= $badgeClass ?>"><?= $percentage ?>%</span>
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

<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1>Historique des demandes traitées</h1>
            <p class="text-muted">Toutes les demandes approuvées ou refusées</p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="<?= route_to('rh.history') ?>" class="row g-3">
                        <div class="col-md-3">
                            <label for="status" class="form-label">Statut</label>
                            <select id="status" name="status" class="form-select">
                                <option value="">Tous</option>
                                <option value="approuvee" <?= (get('status') === 'approuvee') ? 'selected' : '' ?>>Approuvées</option>
                                <option value="refusee" <?= (get('status') === 'refusee') ? 'selected' : '' ?>>Refusées</option>
                                <option value="annulee" <?= (get('status') === 'annulee') ? 'selected' : '' ?>>Annulées</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="employe_id" class="form-label">Employé</label>
                            <select id="employe_id" name="employe_id" class="form-select">
                                <option value="">Tous</option>
                                <?php if (!empty($employes)): ?>
                                    <?php foreach ($employes as $e): ?>
                                        <option value="<?= $e['id'] ?>" <?= (get('employe_id') == $e['id']) ? 'selected' : '' ?>>
                                            <?= $e['nom'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="annee" class="form-label">Année</label>
                            <input type="number" id="annee" name="annee" class="form-control" value="<?= get('annee') ?? date('Y') ?>">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Filtrer</button>
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
                    <?php if (!empty($demandes)): ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Employé</th>
                                        <th>Type</th>
                                        <th>Période</th>
                                        <th>Jours</th>
                                        <th>Statut</th>
                                        <th>Traité par</th>
                                        <th>Date traitement</th>
                                        <th>Remarques</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($demandes as $demande): ?>
                                        <tr>
                                            <td><?= $demande['employe_nom'] ?></td>
                                            <td><?= $demande['type_conge_nom'] ?></td>
                                            <td>
                                                <?= date_format(date_create($demande['date_debut']), 'd/m') ?> 
                                                -
                                                <?= date_format(date_create($demande['date_fin']), 'd/m/Y') ?>
                                            </td>
                                            <td><?= $demande['nombre_jours'] ?></td>
                                            <td>
                                                <?php
                                                $statusClass = match($demande['statut']) {
                                                    'approuvee' => 'badge-success',
                                                    'refusee' => 'badge-danger',
                                                    'annulee' => 'badge-secondary',
                                                    default => 'badge-warning'
                                                };
                                                ?>
                                                <span class="badge <?= $statusClass ?>">
                                                    <?= ucfirst($demande['statut']) ?>
                                                </span>
                                            </td>
                                            <td><?= $demande['traite_par'] ?? '-' ?></td>
                                            <td><?= $demande['date_traitement'] ? date_format(date_create($demande['date_traitement']), 'd/m/Y') : '-' ?></td>
                                            <td><?= $demande['comment_rh'] ?? '-' ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center py-4">Aucune demande à afficher</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

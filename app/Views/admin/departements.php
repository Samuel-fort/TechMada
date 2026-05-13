<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1>Gestion des départements</h1>
                    <p class="text-muted">Organisation de l'entreprise</p>
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDepartementModal">
                    Ajouter un département
                </button>
            </div>
        </div>
    </div>

    <?= session()->getFlashdata('message') ? '<div class="alert alert-' . session()->getFlashdata('message_type') . '" role="alert">' . session()->getFlashdata('message') . '</div>' : '' ?>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <?php if (!empty($departements)): ?>
                        <div class="row">
                            <?php foreach ($departements as $dept): ?>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title"><?= $dept['nom'] ?></h5>
                                            <p class="card-text text-muted">
                                                <?= $dept['nombre_employes'] ?? 0 ?> employés
                                            </p>
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editDepartementModal" onclick="editDepartement(<?= $dept['id'] ?>, '<?= $dept['nom'] ?>')">
                                                    Modifier
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center py-4">Aucun département enregistré</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ajouter département -->
<div class="modal fade" id="addDepartementModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un département</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= route_to('admin.store_departement') ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom du département</label>
                        <input type="text" id="nom" name="nom" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1>Gestion des types de congé</h1>
                    <p class="text-muted">Définir les congés disponibles et jours maximum</p>
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTypeModal">
                    Ajouter un type
                </button>
            </div>
        </div>
    </div>

    <?= session()->getFlashdata('message') ? '<div class="alert alert-' . session()->getFlashdata('message_type') . '" role="alert">' . session()->getFlashdata('message') . '</div>' : '' ?>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <?php if (!empty($types)): ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Type de congé</th>
                                        <th>Jours maximum par an</th>
                                        <th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($types as $type): ?>
                                        <tr>
                                            <td>
                                                <strong><?= $type['nom'] ?></strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary"><?= $type['jours_max'] ?> jours</span>
                                            </td>
                                            <td><?= $type['description'] ?? '-' ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editTypeModal" onclick="editType(<?= $type['id'] ?>, '<?= $type['nom'] ?>', <?= $type['jours_max'] ?>, '<?= $type['description'] ?? '' ?>')">
                                                    Modifier
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center py-4">Aucun type de congé enregistré</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ajouter type -->
<div class="modal fade" id="addTypeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un type de congé</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= route_to('admin.store_type_conge') ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" id="nom" name="nom" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="jours_max" class="form-label">Jours maximum par an</label>
                        <input type="number" id="jours_max" name="jours_max" class="form-control" required min="1">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description (optionnel)</label>
                        <textarea id="description" name="description" class="form-control" rows="3"></textarea>
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

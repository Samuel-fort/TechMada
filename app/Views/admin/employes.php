<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1>Gestion des employés</h1>
                    <p class="text-muted">Ajouter, modifier et gérer les employés</p>
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEmployeModal">
                    Ajouter un employé
                </button>
            </div>
        </div>
    </div>

    <?= session()->getFlashdata('message') ? '<div class="alert alert-' . session()->getFlashdata('message_type') . '" role="alert">' . session()->getFlashdata('message') . '</div>' : '' ?>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <?php if (!empty($employes)): ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th>Département</th>
                                        <th>Rôle</th>
                                        <th>Date embauche</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($employes as $employe): ?>
                                        <tr>
                                            <td><strong><?= $employe['nom'] ?></strong></td>
                                            <td><?= $employe['email'] ?></td>
                                            <td><?= $employe['departement'] ?? '-' ?></td>
                                            <td>
                                                <span class="badge bg-info"><?= ucfirst($employe['role']) ?></span>
                                            </td>
                                            <td><?= date_format(date_create($employe['date_embauche']), 'd/m/Y') ?></td>
                                            <td>
                                                <a href="<?= route_to('admin.edit_employe', $employe['id']) ?>" class="btn btn-sm btn-outline-primary">
                                                    Modifier
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center py-4">Aucun employé enregistré</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ajouter employé -->
<div class="modal fade" id="addEmployeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un employé</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= route_to('admin.store_employe') ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom complet</label>
                        <input type="text" id="nom" name="nom" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="mot_de_passe" class="form-label">Mot de passe</label>
                        <input type="password" id="mot_de_passe" name="mot_de_passe" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="departement_id" class="form-label">Département</label>
                        <select id="departement_id" name="departement_id" class="form-select" required>
                            <option value="">Sélectionner...</option>
                            <?php if (!empty($departements)): ?>
                                <?php foreach ($departements as $dept): ?>
                                    <option value="<?= $dept['id'] ?>"><?= $dept['nom'] ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Rôle</label>
                        <select id="role" name="role" class="form-select" required>
                            <option value="employe">Employé</option>
                            <option value="rh">RH</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="date_embauche" class="form-label">Date d'embauche</label>
                        <input type="date" id="date_embauche" name="date_embauche" class="form-control" required>
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

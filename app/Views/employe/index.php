<?= $this->extend('layouts/app') ?>

<?= $this->section('topbar_actions') ?>
    <a href="<?= route_to('employe.create') ?>" 
       class="btn-forest" 
       style="padding: 7px 14px; font-size: 0.82rem">
        <i class="bi bi-plus-lg"></i> Nouvelle demande
    </a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- LISTE DES DEMANDES -->
<div class="data-card">
    <div class="data-card-head">
        <h3>Toutes mes demandes</h3>
        <div style="display: flex; gap: 6px">
            <select id="filtre-statut" 
                    class="f-select" 
                    style="font-size: 0.8rem; padding: 6px 10px; width: auto"
                    onchange="filtrerDemandes()">
                <option value="">Tous les statuts</option>
                <option value="en_attente">En attente</option>
                <option value="approuvee">Approuvée</option>
                <option value="refusee">Refusée</option>
                <option value="annulee">Annulée</option>
            </select>
        </div>
    </div>

    <?php if (!empty($demandes)): ?>
        <table class="tbl">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Début</th>
                    <th>Fin</th>
                    <th>Durée</th>
                    <th>Statut</th>
                    <th>Commentaire RH</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($demandes as $demande): ?>
                    <tr>
                        <td>
                            <span class="type-badge t-<?= str_replace(' ', '-', strtolower($demande['type_conge'])) ?>">
                                <?= $demande['type_conge'] ?>
                            </span>
                        </td>
                        <td class="td-muted">
                            <?= date('j M Y', strtotime($demande['date_debut'])) ?>
                        </td>
                        <td class="td-muted">
                            <?= date('j M Y', strtotime($demande['date_fin'])) ?>
                        </td>
                        <td class="td-mono">
                            <?php 
                                $d1 = new DateTime($demande['date_debut']);
                                $d2 = new DateTime($demande['date_fin']);
                                $interval = $d1->diff($d2);
                                echo $interval->days + 1;
                            ?> j
                        </td>
                        <td>
                            <span class="statut s-<?= str_replace('_', '', $demande['statut']) ?>">
                                <?= ucfirst(str_replace('_', ' ', $demande['statut'])) ?>
                            </span>
                        </td>
                        <td style="font-size: 0.78rem; color: <?= $demande['statut'] === 'refusee' ? 'var(--danger)' : 'var(--muted)' ?>">
                            <?php if ($demande['commentaire_rh']): ?>
                                <?= $demande['commentaire_rh'] ?>
                            <?php else: ?>
                                <span style="color: var(--muted)">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($demande['statut'] === 'en_attente'): ?>
                                <button class="btn-sm btn-cancel" 
                                        onclick="confirmerAnnulation(<?= $demande['id'] ?>)">
                                    <i class="bi bi-x"></i> Annuler
                                </button>
                            <?php else: ?>
                                <span class="td-muted" style="font-size: 0.75rem">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="empty">
            <i class="bi bi-inbox"></i>
            <p>Vous n'avez pas encore fait de demande de congé</p>
        </div>
    <?php endif; ?>

</div>

<!-- Script pour filtrer -->
<script>
function filtrerDemandes() {
    const statut = document.getElementById('filtre-statut').value;
    
    if (statut === '') {
        // Afficher tous
        document.querySelectorAll('.tbl tbody tr').forEach(row => {
            row.style.display = '';
        });
    } else {
        // Filtrer par statut
        document.querySelectorAll('.tbl tbody tr').forEach(row => {
            const cellStatut = row.querySelector('td:nth-child(5) .statut');
            if (cellStatut) {
                const rowStatut = cellStatut.textContent.trim().toLowerCase().replace(' ', '_');
                if (rowStatut === statut) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    }
}

function confirmerAnnulation(id) {
    if (confirm('Êtes-vous sûr de vouloir annuler cette demande?')) {
        // Créer un formulaire invisible et l'envoyer
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= route_to('employe.cancel_post') ?>';
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'id';
        input.value = id;
        
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<?= $this->endSection() ?>

<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<!-- FORMULAIRE DE DEMANDE DE CONGÉ -->
<div style="display: grid; grid-template-columns: 1fr 300px; gap: 1.5rem; align-items: start;" class="form-layout">

    <!-- Formulaire principal -->
    <div>
        <div class="form-section">
            <h3>Détails de la demande</h3>

            <form action="<?= route_to('employe.store') ?>" method="post">
                <?= csrf_field() ?>

                <!-- Type de congé -->
                <div class="f-group" style="margin-bottom: 1rem">
                    <label class="f-label">
                        Type de congé 
                        <span style="color: var(--danger)">*</span>
                    </label>
                    <select name="type_conge_id" class="f-select" required>
                        <option value="">-- Choisir un type --</option>
                        <?php if (!empty($types_conge)): ?>
                            <?php foreach ($types_conge as $type): ?>
                                <option value="<?= $type['id'] ?>" <?= old('type_conge_id') == $type['id'] ? 'selected' : '' ?>>
                                    <?= $type['nom'] ?> 
                                    (<?= $type['jours_max'] ?> j max)
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <?php if (session('errors.type_conge_id')): ?>
                        <div class="f-error">
                            <i class="bi bi-exclamation-circle"></i> 
                            <?= session('errors.type_conge_id') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Dates -->
                <div class="form-grid-2" style="margin-bottom: 1rem">
                    <div class="f-group">
                        <label class="f-label">
                            Date de début 
                            <span style="color: var(--danger)">*</span>
                        </label>
                        <input type="date" 
                               name="date_debut" 
                               class="f-input" 
                               required
                               value="<?= old('date_debut') ?>"
                               onchange="calculerDuree()"/>
                        <?php if (session('errors.date_debut')): ?>
                            <div class="f-error"><?= session('errors.date_debut') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="f-group">
                        <label class="f-label">
                            Date de fin 
                            <span style="color: var(--danger)">*</span>
                        </label>
                        <input type="date" 
                               name="date_fin" 
                               class="f-input" 
                               required
                               value="<?= old('date_fin') ?>"
                               onchange="calculerDuree()"/>
                        <?php if (session('errors.date_fin')): ?>
                            <div class="f-error"><?= session('errors.date_fin') ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Calcul automatique de la durée -->
                <div id="duree-box" class="f-computed" style="display: none">
                    <div class="f-computed-num" id="duree-nombre">0</div>
                    <div class="f-computed-label">
                        jours calendaires calculés<br>
                        <span id="duree-periode" style="font-size: 0.7rem; opacity: 0.7"></span>
                    </div>
                </div>

                <!-- Motif -->
                <div class="f-group" style="margin-bottom: 1rem">
                    <label class="f-label">Motif (optionnel)</label>
                    <textarea name="motif" 
                              class="f-textarea" 
                              placeholder="Précisez le motif de votre demande si nécessaire...">
                        <?= old('motif') ?>
                    </textarea>
                    <div class="f-hint">Le motif est visible par le responsable RH.</div>
                </div>

                <!-- Boutons d'action -->
                <div class="form-actions">
                    <button type="submit" class="btn-forest">
                        <i class="bi bi-send"></i> Soumettre la demande
                    </button>
                    <a href="<?= route_to('employe.dashboard') ?>" class="btn-secondary">
                        <i class="bi bi-x"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Panneau latéral : informations -->
    <div style="display: flex; flex-direction: column; gap: 1rem">

        <!-- Soldes actuels -->
        <div class="data-card" style="margin: 0">
            <div class="data-card-head">
                <h3>
                    <i class="bi bi-piggy-bank" style="color: var(--forest); margin-right: 5px"></i>
                    Vos soldes actués
                </h3>
            </div>
            <div style="padding: 0.75rem 1.1rem; display: flex; flex-direction: column; gap: 0.75rem">
                <?php if (!empty($soldes)): ?>
                    <?php foreach ($soldes as $solde): ?>
                        <div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px">
                                <span style="font-size: 0.8rem; color: var(--ink)">
                                    <?= $solde['type_conge'] ?>
                                </span>
                                <span style="font-family: 'DM Mono', monospace; font-size: 0.8rem; color: var(--forest); font-weight: 500">
                                    <?= $solde['jours_restants'] ?> j
                                </span>
                            </div>
                            <div class="solde-bar">
                                <div class="solde-fill" 
                                     style="width: <?= ($solde['jours_restants'] / $solde['jours_total'] * 100) ?>%">
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty">
                        <p>Pas de solde disponible</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Message d'info -->
        <div class="flash flash-info" style="margin: 0">
            <i class="bi bi-info-circle-fill"></i>
            <span style="font-size: 0.8rem">
                Le solde est déduit seulement à l'approbation de votre responsable.
            </span>
        </div>

        <!-- Rappel des règles -->
        <div style="background: var(--cream); border: 1px solid var(--border); border-radius: 8px; padding: 0.85rem 1rem">
            <div style="font-size: 0.78rem; font-weight: 500; color: var(--ink); margin-bottom: 0.5rem">
                <i class="bi bi-clipboard-check" style="color: var(--forest); margin-right: 5px"></i>
                Rappel des règles
            </div>
            <ul style="margin: 0; padding-left: 1rem; font-size: 0.75rem; color: var(--muted); line-height: 1.7">
                <li>Préavis minimum : 48h avant la date de début</li>
                <li>Pas de chevauchement avec une demande en cours</li>
                <li>Solde insuffisant = demande refusée automatiquement</li>
            </ul>
        </div>

    </div>

</div>

<!-- Script pour calculer la durée -->
<script>
function calculerDuree() {
    const debut = document.querySelector('input[name="date_debut"]').value;
    const fin = document.querySelector('input[name="date_fin"]').value;
    
    if (debut && fin) {
        const d1 = new Date(debut);
        const d2 = new Date(fin);
        const diff = Math.abs(d2 - d1);
        const jours = Math.ceil(diff / (1000 * 60 * 60 * 24)) + 1;
        
        // Afficher la duree
        document.getElementById('duree-nombre').textContent = jours;
        document.getElementById('duree-periode').textContent = 
            'du ' + d1.toLocaleDateString('fr-FR') + 
            ' au ' + d2.toLocaleDateString('fr-FR');
        
        // Afficher la box
        document.getElementById('duree-box').style.display = 'flex';
    }
}
</script>

<?= $this->endSection() ?>

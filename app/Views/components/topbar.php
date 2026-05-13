<?php
// Récupérer les informations de la page actuelle
$page_title = $title ?? 'Accueil';
$page_breadcrumb = $breadcrumb ?? 'Accueil';
?>

<div class="topbar">
    
    <div>
        <div class="topbar-title"><?= $page_title ?></div>
        <div class="topbar-breadcrumb">
            <?= $page_breadcrumb ?>
        </div>
    </div>
    
    <!-- Actions à droite (vide par défaut, peut être rempli par les vues) -->
    <div class="topbar-actions">
        <?= $this->renderSection('topbar_actions') ?>
    </div>

</div>

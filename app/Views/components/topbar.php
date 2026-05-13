<?php
helper('Auth');
// Récupérer les informations de la page actuelle
$page_title = $title ?? 'Accueil';
$page_breadcrumb = $breadcrumb ?? 'Accueil';
$authUser = auth()->user();
?>

<div class="topbar">
    
    <div>
        <div class="topbar-title"><?= $page_title ?></div>
        <div class="topbar-breadcrumb">
            <?= $page_breadcrumb ?>
        </div>
    </div>
    
    <!-- Actions à droite -->
    <div class="topbar-actions">
        <?php if ($authUser): ?>
            <div class="topbar-user">
                <span class="topbar-user-name"><?= $authUser['nom'] ?? 'Utilisateur' ?></span>
                <a href="<?= route_to('auth.logout') ?>" class="topbar-logout-btn">
                    <i class="bi bi-box-arrow-right"></i> Déconnexion
                </a>
            </div>
        <?php endif; ?>
        <?= $this->renderSection('topbar_actions') ?>
    </div>

</div>

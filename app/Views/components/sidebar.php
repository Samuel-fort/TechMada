<?php
// Récupérer l'utilisateur actuel
$authUser = auth()->user() ?? [];
$userRole = $authUser['role'] ?? 'employe';
$userName = $authUser['nom'] ?? 'Utilisateur';
?>

<aside class="sidebar">
    
    <!-- Brand/Logo -->
    <div class="sidebar-brand">
        <div class="sidebar-logo-icon">
            <i class="bi bi-briefcase"></i>
        </div>
        <div class="sidebar-brand-name">
            TechMada RH
            <span><?= $breadcrumb_app ?? 'Espace utilisateur' ?></span>
        </div>
    </div>
    
    <!-- Menu sections -->
    <div class="sidebar-section">Menu</div>
    
    <!-- Menu différent selon le rôle -->
    <ul class="sidebar-nav">
        
        <?php if ($userRole === 'employe'): ?>
            <!-- Menu employé -->
            <li>
                <a href="<?= route_to('employe.dashboard') ?>" class="<?= (current_url(false) === route_to('employe.dashboard')) ? 'active' : '' ?>">
                    <i class="bi bi-grid-1x2"></i> Tableau de bord
                </a>
            </li>
            <li>
                <a href="<?= route_to('employe.create') ?>" class="<?= (current_url(false) === route_to('employe.create')) ? 'active' : '' ?>">
                    <i class="bi bi-plus-circle"></i> Nouvelle demande
                </a>
            </li>
            <li>
                <a href="<?= route_to('employe.index') ?>" class="<?= (current_url(false) === route_to('employe.index')) ? 'active' : '' ?>">
                    <i class="bi bi-calendar3"></i> Mes demandes
                    <span class="nav-badge alert" id="badge-pending">0</span>
                </a>
            </li>
            <li>
                <a href="<?= route_to('employe.profile') ?>" class="<?= (current_url(false) === route_to('employe.profile')) ? 'active' : '' ?>">
                    <i class="bi bi-person"></i> Mon profil
                </a>
            </li>
        
        <?php elseif ($userRole === 'rh'): ?>
            <!-- Menu RH -->
            <li>
                <a href="<?= route_to('rh.dashboard') ?>" class="<?= (current_url(false) === route_to('rh.dashboard')) ? 'active' : '' ?>">
                    <i class="bi bi-grid-1x2"></i> Tableau de bord
                </a>
            </li>
            <li>
                <a href="<?= route_to('rh.index') ?>" class="<?= (current_url(false) === route_to('rh.index')) ? 'active' : '' ?>">
                    <i class="bi bi-inbox"></i> Demandes à traiter
                    <span class="nav-badge alert" id="badge-pending">0</span>
                </a>
            </li>
            <li>
                <a href="<?= route_to('rh.history') ?>" class="<?= (current_url(false) === route_to('rh.history')) ? 'active' : '' ?>">
                    <i class="bi bi-archive"></i> Historique
                </a>
            </li>
            <li>
                <a href="<?= route_to('rh.soldes') ?>" class="<?= (current_url(false) === route_to('rh.soldes')) ? 'active' : '' ?>">
                    <i class="bi bi-people"></i> Soldes employés
                </a>
            </li>
        
        <?php elseif ($userRole === 'admin'): ?>
            <!-- Menu Admin -->
            <li>
                <a href="<?= route_to('admin.dashboard') ?>" class="<?= (current_url(false) === route_to('admin.dashboard')) ? 'active' : '' ?>">
                    <i class="bi bi-speedometer2"></i> Vue d'ensemble
                </a>
            </li>
            <li>
                <a href="<?= route_to('rh.index') ?>" class="<?= (current_url(false) === route_to('rh.index')) ? 'active' : '' ?>">
                    <i class="bi bi-inbox"></i> Toutes les demandes
                    <span class="nav-badge alert" id="badge-pending">0</span>
                </a>
            </li>
            <li>
                <a href="<?= route_to('admin.employes') ?>" class="<?= (current_url(false) === route_to('admin.employes')) ? 'active' : '' ?>">
                    <i class="bi bi-people"></i> Employés
                </a>
            </li>
            <li>
                <a href="<?= route_to('admin.departements') ?>" class="<?= (current_url(false) === route_to('admin.departements')) ? 'active' : '' ?>">
                    <i class="bi bi-building"></i> Départements
                </a>
            </li>
            <li>
                <a href="<?= route_to('admin.types_conge') ?>" class="<?= (current_url(false) === route_to('admin.types_conge')) ? 'active' : '' ?>">
                    <i class="bi bi-tags"></i> Types de congé
                </a>
            </li>
            <li>
                <a href="<?= route_to('admin.soldes') ?>" class="<?= (current_url(false) === route_to('admin.soldes')) ? 'active' : '' ?>">
                    <i class="bi bi-sliders"></i> Soldes annuels
                </a>
            </li>
        <?php endif; ?>
    
    </ul>
    
    <!-- Utilisateur actuel -->
    <div class="sidebar-user">
        <div class="s-user-row">
            <div class="avatar av-green">
                <?= getInitials($userName) ?>
            </div>
            <div>
                <div class="user-name"><?= $userName ?></div>
                <div class="user-role"><?= ucfirst($userRole) ?></div>
            </div>
            <a href="<?= route_to('auth.logout') ?>" 
               title="Déconnexion" 
               style="margin-left: auto; color: rgba(255, 255, 255, 0.25); font-size: 1.1rem; cursor: pointer;">
                <i class="bi bi-box-arrow-right"></i>
            </a>
        </div>
    </div>

</aside>


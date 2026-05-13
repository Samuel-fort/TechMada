<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'TechMada RH' ?> — Gestion des congés</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= base_url('css/app.css') ?>" rel="stylesheet">
    
    <?php if (isset($additional_css)) {
        foreach ($additional_css as $css_file) {
            echo '<link href="' . base_url('css/' . $css_file) . '" rel="stylesheet">';
        }
    } ?>
</head>
<body>

<div class="app-wrap">
    
    <!-- Sidebar -->
    <?= $this->include('components/sidebar') ?>
    
    <!-- Contenu principal -->
    <div class="main">
        
        <!-- Topbar -->
        <?= $this->include('components/topbar') ?>
        
        <!-- Contenu -->
        <div class="content">
            <?= $this->renderSection('content') ?>
        </div>
        
        <!-- Footer -->
        <div class="footer-app">
            <i class="bi bi-c-circle"></i> 2026 
            <span>TechMada RH</span> 
            — Projet CodeIgniter 4
        </div>
    </div>

</div>

<!-- Scripts -->
<script src="<?= base_url('js/app.js') ?>"></script>

<?php if (isset($additional_js)) {
    foreach ($additional_js as $js_file) {
        echo '<script src="' . base_url('js/' . $js_file) . '"></script>';
    }
} ?>

</body>
</html>

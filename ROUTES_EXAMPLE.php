// ========================================
// EXEMPLE DE ROUTES POUR TECHMADA RH
// À ajouter dans app/Config/Routes.php
// ========================================

// Récupérer l'instance du routeur
$routes = \Config\Services::routes();

// ────────────────────────────────────────
// AUTHENTIFICATION
// ────────────────────────────────────────
$routes->get('/login', 'AuthController::login', ['as' => 'auth.login']);
$routes->post('/authenticate', 'AuthController::authenticate', ['as' => 'auth.authenticate']);
$routes->get('/logout', 'AuthController::logout', ['as' => 'auth.logout']);
$routes->get('/register', 'AuthController::register', ['as' => 'auth.register']);
$routes->post('/register', 'AuthController::storeUser', ['as' => 'auth.store']);

// ────────────────────────────────────────
// DASHBOARD / ACCUEIL (redirection selon rôle)
// ────────────────────────────────────────
$routes->get('/', 'DashboardController::index', ['as' => 'dashboard']);

// ────────────────────────────────────────
// ROUTES EMPLOYÉ (espace_employe)
// ────────────────────────────────────────
$routes->group('employe', ['filter' => 'auth', 'namespace' => 'App\Controllers'], function($routes) {
    
    // Dashboard
    $routes->get('/', 'EmployeController::dashboard', ['as' => 'employe.dashboard']);
    
    // Nouvelle demande
    $routes->get('create', 'EmployeController::create', ['as' => 'employe.create']);
    $routes->post('store', 'EmployeController::store', ['as' => 'employe.store']);
    
    // Mes demandes
    $routes->get('demandes', 'EmployeController::index', ['as' => 'employe.index']);
    
    // Annuler une demande
    $routes->post('cancel', 'EmployeController::cancel', ['as' => 'employe.cancel_post']);
    
    // Profil
    $routes->get('profile', 'EmployeController::profile', ['as' => 'employe.profile']);
    $routes->post('profile', 'EmployeController::updateProfile', ['as' => 'employe.update_profile']);
});

// ────────────────────────────────────────
// ROUTES RH (espace_rh)
// ────────────────────────────────────────
$routes->group('rh', ['filter' => 'auth:rh', 'namespace' => 'App\Controllers'], function($routes) {
    
    // Dashboard
    $routes->get('/', 'RhController::dashboard', ['as' => 'rh.dashboard']);
    
    // Demandes à traiter
    $routes->get('demandes', 'RhController::index', ['as' => 'rh.index']);
    $routes->post('approve', 'RhController::approve', ['as' => 'rh.approve']);
    $routes->post('refuse', 'RhController::refuse', ['as' => 'rh.refuse']);
    
    // Historique
    $routes->get('historique', 'RhController::history', ['as' => 'rh.history']);
    
    // Soldes employés
    $routes->get('soldes', 'RhController::soldes', ['as' => 'rh.soldes']);
});

// ────────────────────────────────────────
// ROUTES ADMIN (espace_admin)
// ────────────────────────────────────────
$routes->group('admin', ['filter' => 'auth:admin', 'namespace' => 'App\Controllers'], function($routes) {
    
    // Dashboard
    $routes->get('/', 'AdminController::dashboard', ['as' => 'admin.dashboard']);
    
    // Gestion employés
    $routes->get('employes', 'AdminController::employes', ['as' => 'admin.employes']);
    $routes->post('employes', 'AdminController::storeEmploye', ['as' => 'admin.store_employe']);
    $routes->get('employes/(:id)', 'AdminController::editEmploye/$1', ['as' => 'admin.edit_employe']);
    $routes->post('employes/(:id)', 'AdminController::updateEmploye/$1', ['as' => 'admin.update_employe']);
    
    // Départements
    $routes->get('departements', 'AdminController::departements', ['as' => 'admin.departements']);
    $routes->post('departements', 'AdminController::storeDepartement', ['as' => 'admin.store_departement']);
    
    // Types de congé
    $routes->get('types-conge', 'AdminController::typesCong', ['as' => 'admin.types_conge']);
    $routes->post('types-conge', 'AdminController::storeTypeConge', ['as' => 'admin.store_type_conge']);
    
    // Soldes annuels
    $routes->get('soldes', 'AdminController::soldes', ['as' => 'admin.soldes']);
});

// ────────────────────────────────────────
// API ROUTES (optionnel, pour AJAX)
// ────────────────────────────────────────
$routes->group('api', ['namespace' => 'App\Controllers\Api'], function($routes) {
    $routes->post('demandes/(:id)/approve', 'DemandeController::approve/$1');
    $routes->post('demandes/(:id)/refuse', 'DemandeController::refuse/$1');
    $routes->get('soldes/employe/(:id)', 'SoldeController::getEmployeSoldes/$1');
});

// ────────────────────────────────────────
// PAGE PAR DÉFAUT
// ────────────────────────────────────────
$routes->get('404', function() {
    echo view('errors/html/error_404');
});

$routes->setAutoRoute(false);

<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Authentification (publique)
$routes->get('/login', 'AuthController::login', ['as' => 'auth.login']);
$routes->post('/authenticate', 'AuthController::authenticate', ['as' => 'auth.authenticate']);
$routes->get('/logout', 'AuthController::logout', ['as' => 'auth.logout']);
$routes->get('/register', 'AuthController::register', ['as' => 'auth.register']);
$routes->post('/register', 'AuthController::storeUser', ['as' => 'auth.store']);

// Dashboard avec redirection
$routes->get('/', 'DashboardController::index', ['as' => 'dashboard', 'filter' => 'auth']);

// ────────────────────────────────────────────────────────────────
// ROUTES EMPLOYÉ
// ────────────────────────────────────────────────────────────────
$routes->group('employe', ['filter' => 'auth', 'namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('/', 'EmployeController::dashboard', ['as' => 'employe.dashboard']);
    $routes->get('create', 'EmployeController::create', ['as' => 'employe.create']);
    $routes->post('store', 'EmployeController::store', ['as' => 'employe.store']);
    $routes->get('demandes', 'EmployeController::index', ['as' => 'employe.index']);
    $routes->post('cancel', 'EmployeController::cancel', ['as' => 'employe.cancel_post']);
    $routes->get('profile', 'EmployeController::profile', ['as' => 'employe.profile']);
});

// ────────────────────────────────────────────────────────────────
// ROUTES RH
// ────────────────────────────────────────────────────────────────
$routes->group('rh', ['filter' => 'auth', 'namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('/', 'RhController::dashboard', ['as' => 'rh.dashboard']);
    $routes->get('demandes', 'RhController::index', ['as' => 'rh.index']);
    $routes->post('approve', 'RhController::approve', ['as' => 'rh.approve']);
    $routes->post('refuse', 'RhController::refuse', ['as' => 'rh.refuse']);
    $routes->get('historique', 'RhController::history', ['as' => 'rh.history']);
    $routes->get('soldes', 'RhController::soldes', ['as' => 'rh.soldes']);
});

// ────────────────────────────────────────────────────────────────
// ROUTES ADMIN
// ────────────────────────────────────────────────────────────────
$routes->group('admin', ['filter' => 'auth', 'namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('/', 'AdminController::dashboard', ['as' => 'admin.dashboard']);
    $routes->get('employes', 'AdminController::employes', ['as' => 'admin.employes']);
    $routes->post('employes', 'AdminController::storeEmploye', ['as' => 'admin.store_employe']);
    $routes->get('employes/(:id)', 'AdminController::editEmploye/$1', ['as' => 'admin.edit_employe']);
    $routes->post('employes/(:id)', 'AdminController::updateEmploye/$1', ['as' => 'admin.update_employe']);
    $routes->get('departements', 'AdminController::departements', ['as' => 'admin.departements']);
    $routes->post('departements', 'AdminController::storeDepartement', ['as' => 'admin.store_departement']);
    $routes->get('types-conge', 'AdminController::typesCong', ['as' => 'admin.types_conge']);
    $routes->post('types-conge', 'AdminController::storeTypeConge', ['as' => 'admin.store_type_conge']);
    $routes->get('soldes', 'AdminController::soldes', ['as' => 'admin.soldes']);
});

$routes->setAutoRoute(false);

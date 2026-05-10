<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ============================================
// AUTHENTIFICATION
// ============================================
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::doLogin');
$routes->post('logout', 'AuthController::logout', ['filter' => 'auth']);

// Registration (2 étapes)
$routes->get('register/step1', 'AuthController::registerStep1');
$routes->post('register/step1', 'AuthController::saveStep1');
$routes->get('register/step2', 'AuthController::registerStep2');
$routes->post('register/step2', 'AuthController::saveStep2');

// ============================================
// DASHBOARD CLIENT
// ============================================
$routes->get('dashboard', 'DashboardController::index', ['filter' => 'auth']);
$routes->get('dashboard/select-objectifs', 'DashboardController::selectObjectifs', ['filter' => 'auth']);
$routes->post('dashboard/save-objectifs', 'DashboardController::saveObjectifs', ['filter' => 'auth']);
$routes->post('dashboard/validate-code', 'DashboardController::validateCode', ['filter' => 'auth']);
$routes->post('dashboard/buy-gold', 'DashboardController::buyGold', ['filter' => 'auth']);

// ============================================
// ADMIN BACK OFFICE
// ============================================
$routes->get('admin', 'AdminController::dashboard', ['filter' => 'auth']);
$routes->get('admin/users', 'AdminController::users', ['filter' => 'auth']);
$routes->get('admin/regimes', 'AdminController::regimes', ['filter' => 'auth']);
$routes->get('admin/activites', 'AdminController::activites', ['filter' => 'auth']);
$routes->get('admin/codes', 'AdminController::codes', ['filter' => 'auth']);

// ============================================
// REGIMES
// ============================================
$routes->get('regime/browse', 'RegimeController::browse', ['filter' => 'auth']);
$routes->get('regime/active', 'RegimeController::active', ['filter' => 'auth']);
$routes->post('regime/select/(:num)', 'RegimeController::select/$1', ['filter' => 'auth']);
$routes->post('regime/cancel/(:num)', 'RegimeController::cancel/$1', ['filter' => 'auth']);

// ============================================
// ACTIVITES
// ============================================
$routes->get('activity/browse', 'ActivityController::browse', ['filter' => 'auth']);
$routes->get('activity/active', 'ActivityController::active', ['filter' => 'auth']);
$routes->post('activity/select/(:num)', 'ActivityController::select/$1', ['filter' => 'auth']);
$routes->post('activity/cancel/(:num)', 'ActivityController::cancel/$1', ['filter' => 'auth']);

// ============================================
// PAGE ACCUEIL
// ============================================
$routes->get('/', 'Home::index');

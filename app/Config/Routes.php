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
$routes->post('api/validate-code', 'DashboardController::validateCodeAjax', ['filter' => 'auth']);
$routes->post('dashboard/buy-gold', 'DashboardController::buyGold', ['filter' => 'auth']);
$routes->get('profile/edit', 'DashboardController::editProfile', ['filter' => 'auth']);
$routes->post('profile/update', 'DashboardController::updateProfile', ['filter' => 'auth']);

// ============================================
// ADMIN BACK OFFICE
// ============================================
$routes->get('admin', 'AdminController::dashboard', ['filter' => 'admin']);
$routes->get('admin/users', 'AdminController::users', ['filter' => 'admin']);
$routes->post('admin/users/delete/(:num)', 'AdminController::deleteUser/$1', ['filter' => 'admin']);
$routes->get('admin/regimes', 'AdminController::regimes', ['filter' => 'admin']);
$routes->post('admin/regimes/save', 'AdminController::saveRegime', ['filter' => 'admin']);
$routes->post('admin/regimes/delete/(:num)', 'AdminController::deleteRegime/$1', ['filter' => 'admin']);
$routes->get('admin/regimes/tariffs/(:num)', 'AdminController::getTariffs/$1', ['filter' => 'admin']);
$routes->post('admin/regimes/tariff/save', 'AdminController::saveTariff', ['filter' => 'admin']);
$routes->post('admin/regimes/tariff/delete/(:num)', 'AdminController::deleteTariff/$1', ['filter' => 'admin']);
$routes->get('admin/activites', 'AdminController::activites', ['filter' => 'admin']);
$routes->post('admin/activites/save', 'AdminController::saveActivite', ['filter' => 'admin']);
$routes->post('admin/activites/delete/(:num)', 'AdminController::deleteActivite/$1', ['filter' => 'admin']);
$routes->get('admin/codes', 'AdminController::codes', ['filter' => 'admin']);
$routes->post('admin/codes/save', 'AdminController::saveCode', ['filter' => 'admin']);
$routes->post('admin/codes/toggle/(:num)', 'AdminController::toggleCode/$1', ['filter' => 'admin']);
$routes->post('admin/codes/delete/(:num)', 'AdminController::deleteCode/$1', ['filter' => 'admin']);
$routes->get('admin/cross-tab', 'AdminController::crossTabUsers', ['filter' => 'admin']);
$routes->get('admin/parametres', 'AdminController::parametres', ['filter' => 'admin']);
$routes->post('admin/parametres/save', 'AdminController::saveParametre', ['filter' => 'admin']);
$routes->post('admin/parametres/delete/(:num)', 'AdminController::deleteParametre/$1', ['filter' => 'admin']);

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

// ============================================
// EXPORTS PDF
// ============================================
$routes->get('pdf/invoice/(:num)', 'PdfController::invoiceUser/$1', ['filter' => 'auth']);
$routes->get('pdf/receipt-regime/(:num)', 'PdfController::receiptRegime/$1', ['filter' => 'auth']);
$routes->get('pdf/report-admin', 'PdfController::reportAdmin', ['filter' => 'admin']);

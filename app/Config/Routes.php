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
// PAGE ACCUEIL (à faire plus tard)
// ============================================
$routes->get('/', 'Home::index');

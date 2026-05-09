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
// PAGE ACCUEIL (à faire plus tard)
// ============================================
$routes->get('/', 'Home::index');
$routes->get('dashboard', 'Home::dashboard', ['filter' => 'auth']);

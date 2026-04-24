<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/login', 'Home::index');
$routes->get('/logout', 'Home::logout');
$routes->get('/dashboard', 'Home::dashboard');
$routes->get('/reset-password/(:any)', 'Home::reset/$1');

// 🔹 Group untuk API v1
$routes->group('api/v1', function($routes) {
    // Auth (tanpa JWT filter)
    $routes->post('login', 'Api\AuthController::login');
    $routes->post('forgot-password', 'Api\AuthController::forgotPassword');
    $routes->post('update-password', 'Api\AuthController::updatePassword');

    // Users (dengan JWT + rate limit + log)
    $routes->group('users', ['filter' => ['jwt','ratelimit','log']], function($routes) {
        $routes->get('/', 'Api\UserController::index');
        $routes->post('create', 'Api\UserController::create');
        $routes->put('update/(:num)', 'Api\UserController::update/$1');
        $routes->delete('delete/(:num)', 'Api\UserController::delete/$1');
    });

    $routes->group('keys', ['filter' => ['jwt','ratelimit','log']], function($routes) {
        $routes->get('/', 'Api\ApiKeyController::index');
        $routes->post('create', 'Api\ApiKeyController::create');
        $routes->delete('delete/(:num)', 'Api\ApiKeyController::delete/$1');
    });

    $routes->group('bulk', ['filter' => ['jwt','ratelimit']], function($routes) {
        $routes->post('apikey-status', 'Api\ApiKeyController::bulkStatus');
        $routes->post('apikey-delete', 'Api\ApiKeyController::bulkDelete');
    });

    $routes->group('logs', ['filter' => ['jwt','ratelimit']], function($routes) {
        $routes->get('/', 'Api\LogController::index');
        $routes->get('daily-by-status', 'Api\LogController::dailyByStatus');
        $routes->get('daily-by-email', 'Api\LogController::dailyByEmail');
        $routes->get('export', 'ReportController::exportCsv');
        $routes->get('stats', 'Api\LogController::getStats');
        $routes->get('send-daily-report', 'ReportController::sendDailyReport');
    });

    $routes->get('export-excel', 'ReportController::exportExcel');
    $routes->get('export-pdf', 'ReportController::exportPdf');
    $routes->get('notif-slack', 'Api\LogController::checkErrorSpike');
});

/**
 * jika akan membuat api versi 2
 * buat controller baru contoh Api\v2\UserController
 * dan tambahkan route seperti di bawah ini
*/
// $routes->group('api/v2', function($routes) {
    // definisi controller baru untuk versi 2
// });

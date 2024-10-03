<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// USER WEBSITE
// LOGIN
$routes->get('/', 'LoginController::loadingpage');
$routes->get('login', 'LoginController::login');
$routes->post('login/processLogin', 'LoginController::processLogin');
$routes->post('dologin', 'LoginController::dologin');
$routes->get('forgot-password', 'LoginController::forgotPassword');
$routes->post('forgot-password', 'LoginController::sendResetLink');
$routes->get('reset-password', 'LoginController::resetPassword');
$routes->post('reset-password', 'LoginController::processResetPassword');

$routes->get('login', 'LoginController::login');
$routes->post('login/processLogin', 'LoginController::processLogin');
$routes->post('dologin', 'LoginController::dologin');
$routes->get('forgot-password', 'LoginController::forgotPassword');
$routes->post('forgot-password', 'LoginController::sendResetLink');
$routes->get('reset-password', 'LoginController::resetPassword');
$routes->post('reset-password', 'LoginController::processResetPassword');

// REGISTRATION
$routes->get('registration', 'RegistrationController::register');
$routes->post('registration/processForm', 'RegistrationController::processForm');
$routes->get('verify', 'LoginController::verify');
$routes->get('admin/verify', 'ARegistrationController::verify');

// NAVIGATION BAR
$routes->get('home', 'HomeController::home', ['filter' => 'user']);
$routes->get('contact-us', 'HomeController::contactUs', ['filter' => 'user']);
$routes->get('banner', 'HomeController::banner', ['filter' => 'user']);
$routes->get('logout', 'HomeController::logout', ['filter' => 'user']);
$routes->get('achievements', 'HomeController::achievements', ['filter' => 'user']);
$routes->get('contacts', 'HomeController::contacts', ['filter' => 'user']);
$routes->get('activities', 'HomeController::activities', ['filter' => 'user']);
$routes->get('/site', 'HomeController::site', ['filter' => 'user']);

// ALBUM
$routes->get('album', 'HomeController::album', ['filter' => 'user']);
$routes->get('intern', 'HomeController::intern', ['filter' => 'user']);
$routes->get('pfv', 'HomeController::pfv', ['filter' => 'user']);
$routes->get('fdas', 'HomeController::fdas', ['filter' => 'user']);
$routes->get('inspection', 'HomeController::inspection', ['filter' => 'user']);

// ADMIN DASHBOARD
// ADMIN LOGIN
$routes->get('admin', 'ALoginController::loadingpage');
$routes->get('admin-login', 'ALoginController::adminlogin');
$routes->get('admin/processlogin', 'ALoginController::adminprocessLogin');
$routes->post('adddologin', 'ALoginController::adddologin');

// ADMIN REGISTRATION
$routes->get('admin-registration', 'ARegistrationController::adminregister', ['filter' => 'admin']);
$routes->post('admin-registration/processForm', 'ARegistrationController::adminprocessForm', ['filter' => 'admin']);

// ADMIN DASHBOARD HEADER NAVIGATION BAR 
$routes->get('admin-home', 'ANavigationController::adminManage', ['filter' => 'admin']);
$routes->get('admin-logout', 'ANavigationController::adminLogout', ['filter' => 'admin']);
$routes->get('admin-dashboard', 'ANavigationController::adminHome', ['filter' => 'admin']);
$routes->get('admin-notif', 'AHomeController::adminNotif', ['filter' => 'admin']);

// OTHER FUNCTIONS
// NEWS
$routes->get('news', 'NewsController::news', ['filter' => 'user']);
$routes->get('new/(:segment)', 'NewsController::show/$1', ['filter' => 'user']);
$routes->get('newscreate', 'NewsController::newscreate', ['filter' => 'admin']);
$routes->post('news-store', 'NewsController::store', ['filter' => 'admin']);
$routes->post('news-edit', 'NewsController::edit', ['filter' => 'admin']);
$routes->post('news-update', 'NewsController::update', ['filter' => 'admin']);
$routes->get('delete/(:segment)', 'NewsController::delete/$1', ['filter' => 'admin']);

// CAROUSEL IMAGES
$routes->get('carousel', 'CarouselController::image', ['filter' => 'admin']);
$routes->post('carousel/store', 'CarouselController::store', ['filter' => 'admin']);
$routes->get('carousel/edit/(:num)', 'CarouselController::edit/$1', ['filter' => 'admin']);
$routes->post('carousel/update/(:num)', 'CarouselController::update/$1', ['filter' => 'admin']);
$routes->post('carousel/delete/(:num)', 'CarouselController::delete/$1', ['filter' => 'admin']);

// GRAPH
$routes->get('graph', 'GraphController::graph',['filter' => 'admin']);
$routes->get('graph/getReports', 'GraphController::getReports',['filter' => 'admin']);
$routes->get('graph/getReport', 'GraphController::getReport',['filter' => 'admin']);

//MAPPING
$routes->get('user-location', 'LocationController::showUserLocation', ['filter' => 'user']);
$routes->get('rescuemap', 'LocationController::map',);
$routes->get('fetchCommunityReports', 'LocationController::fetchCommunityReports', ['filter' => 'admin']);


// COMMUNITY REPORT
$routes->get('submitcall', 'CommunityReportController::submitcall', ['filter' => 'user']);
$routes->post('communityreport/submit', 'CommunityReportController::submitCommunityReport', ['filter' => 'user']);
$routes->post('getEmergencyCallCoordinates', 'CommunityReportController::getEmergencyCallCoordinates', ['filter' => 'admin']);
$routes->get('reports-recent', 'CommunityReportController::getRecentReports', ['filter' => 'admin']);
$routes->get('/community-report/latest-reports', 'CommunityReportController::getLatestReports');
$routes->get('/save-token', 'CommunityReportController::saveToken', );

$routes->get('getLatestReports', 'CommunityReportController::getLatestReports');
$routes->get('getReportByCommunityReportId/(:num)', 'CommunityReportController::getReportByCommunityReportId/$1');


// RESCUER REPORT
$routes->get('fire-report/create', 'FireReportController::firereportform',);
$routes->post('fire-report/store', 'FireReportController::storeRescuerReport');
$routes->get('rescuer-report/form', 'RescuerReportController::reportform');
$routes->post('rescuer-report/save', 'RescuerReportController::save');
$routes->get('rescuer-report/success', function () {
    return view('RESCUERREPORT/success');
});


$routes->get('testemail', 'TestEmailController::index',);

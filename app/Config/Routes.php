<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// USER WEBSITE
// LOGIN
$routes->get('/', 'LoginController::loginpage');
$routes->get('login', 'LoginController::login');
$routes->post('login/processLogin', 'LoginController::processLogin');
$routes->post('dologin', 'LoginController::dologin');

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
$routes->get('news/(:segment)', 'NewsController::show/$1', ['filter' => 'user']);
$routes->get('newscreate', 'NewsController::newscreate', ['filter' => 'admin']);
$routes->post('news/store', 'NewsController::store', ['filter' => 'user']);
$routes->post('news/edit', 'NewsController::edit', ['filter' => 'user']);
$routes->post('news/update', 'NewsController::update', ['filter' => 'user']);
$routes->get('delete/(:segment)', 'NewsController::delete/$1', ['filter' => 'user']);

// CAROUSEL IMAGES
$routes->get('carouselhome', 'CarouselController::carouselhome', ['filter' => 'user']);
$routes->get('carouselImages', 'CarouselController::addImages', ['filter' => 'user']);
$routes->post('carousel/store', 'CarouselController::store', ['filter' => 'user']);
$routes->post('carousel/edit', 'CarouselController::edit', ['filter' => 'user']);
$routes->post('carousel/update', 'CarouselController::update', ['filter' => 'user']);
$routes->get('delete/(:segment)', 'CarouselController::delete/$1', ['filter' => 'user']);

// GRAPH
$routes->get('graph', 'GraphController::graph', ['filter' => 'user']);

$routes->get('user-location', 'LocationController::showUserLocation', ['filter' => 'user']);
$routes->get('rescuemap', 'LocationController::map', ['filter' => 'user']);
$routes->get('fetchCommunityReports', 'LocationController::fetchCommunityReports', ['filter' => 'user']);

// RESCUER REPORT
$routes->get('emergency-call', 'RescuerReportController::emergencycall', ['filter' => 'user']);
$routes->post('emergency-call/submit', 'RescuerReportController::submitEmergencyCall', ['filter' => 'user']);
$routes->get('emergency', 'RescuerReportController::emergency', ['filter' => 'user']);
$routes->get('sitecall', 'RescuerReportController::sitecall', ['filter' => 'user']);

// COMMUNITY REPORT
$routes->get('submitcall', 'CommunityReportController::submitcall', ['filter' => 'user']);
$routes->post('communityreport/submit', 'CommunityReportController::submitCommunityReport', ['filter' => 'user']);
$routes->post('getEmergencyCallCoordinates', 'CommunityReportController::getEmergencyCallCoordinates', ['filter' => 'user']);
$routes->get('reports-recent', 'CommunityReportController::getRecentReports', ['filter' => 'user']);

// RESCUER REPORT
$routes->get('fire-report/create', 'RescuerReportController::firereportform', ['filter' => 'user']);
$routes->post('fire-report/store', 'RescuerReportController::store', ['filter' => 'user']);

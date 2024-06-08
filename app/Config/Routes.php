<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


// ---------------------USER WEBSITE------------------------------------

// LOGIN
$routes->get('/', 'LoginController::loginpage');
$routes->get('login', 'LoginController::login');
$routes->post('login/processLogin', 'LoginController::processLogin');
$routes->post('dologin', 'LoginController::dologin');

// REGISTRATION
$routes->get('registration', 'RegistrationController::register');
$routes->post('registration/processForm', 'RegistrationController::processForm');
$routes->get('verify', 'LoginController::verify');

// NAVIGATION BAR
$routes->get('home', 'HomeController::home');
$routes->get('contact-us', 'HomeController::contactUs');
$routes->get('banner', 'HomeController::banner');
$routes->get('logout', 'HomeController::logout');
$routes->get('achievements', 'HomeController::achievements');
$routes->get('contacts', 'HomeController::contacts');
$routes->get('activities', 'HomeController::activities');
$routes->get('/site', 'HomeController::site');

//ALBUM
$routes->get('album', 'HomeController::album');
$routes->get('intern', 'HomeController::intern');
$routes->get('pfv', 'HomeController::pfv');
$routes->get('fdas', 'HomeController::fdas');
$routes->get('inspection', 'HomeController::inspection');


// ---------------------ADMIN DASHBOARD------------------------------------
// ADMIN LOGIN
$routes->get('admin-login', 'ALoginController::adminlogin');
$routes->get('admin/processlogin', 'ALoginController::adminprocessLogin');
$routes->post('adddologin', 'ALoginController::adddologin');

// ADMIN REGISTRATION
$routes->get('admin-registration', 'ARegistrationController::adminregister');
$routes->post('admin-registration/processForm', 'ARegistrationController::adminprocessForm');

// ADMIN DASHBOARD HEADER NAVIGATION BAR 
$routes->get('admin-home', 'ANavigationController::adminManage');
$routes->get('admin-logout', 'ANavigationController::adminLogout');
$routes->get('admin-dashboard', 'ANavigationController::adminHome');

$routes->get('admin-notif', 'AHomeController::adminNotif');



// ---------------------OTHER FUNCTIONS------------------------------------



// NEWS
$routes->get('news', 'NewsController::news');
$routes->get('news/(:segment)', 'NewsController::show/$1');
$routes->get('newscreate', 'NewsController::newscreate');
$routes->post('news/store', 'NewsController::store');
$routes->post('news/edit', 'NewsController::edit');
$routes->post('news/update', 'NewsController::update');
$routes->get('delete/(:segment)', 'NewsController::delete/$1');

// CAROUSEL IMAGES
$routes->get('carouselhome', 'CarouselController::carouselhome');
$routes->get('carouselImages', 'CarouselController::addImages');
$routes->post('carousel/store', 'CarouselController::store');
$routes->post('carousel/edit', 'CarouselController::edit');
$routes->post('carousel/update', 'CarouselController::update');
$routes->get('delete/(:segment)', 'CarouselController::delete/$1');

//GRAPH
$routes->get('graph', 'GraphController::graph');


$routes->get('user-location', 'LocationController::showUserLocation');
// $routes->post('mapscr', 'LocationController::mapscript');
$routes->get('rescuemap', 'LocationController::map');


//RESCUER REPORT
$routes->get('emergency-call', 'RescuerReportController::emergencycall');
$routes->post('emergency-call/submit', 'RescuerReportController::submitEmergencyCall');
$routes->get('emergency', 'RescuerReportController::emergency');
$routes->get('sitecall', 'RescuerReportController::sitecall');

//COMMUNITY REPORT
$routes->get('submitcall', 'CommunityReportController::submitcall');
$routes->post('communityreport/submit', 'CommunityReportController::submitCommunityReport');
$routes->post('getEmergencyCallCoordinates', 'CommunityReportController::getEmergencyCallCoordinates');
$routes->get('reports-recent', 'CommunityReportController::getRecentReports');

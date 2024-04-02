<?php

use CodeIgniter\Router\RouteCollection;
/**
 * @var RouteCollection $routes
 */


// ---------------------USER WEBSITE------------------------------------

// LOGIN
$routes->get('login', 'LoginController::login');
$routes->post('login/processLogin', 'LoginController::processLogin');
$routes->post('dologin', 'LoginController::dologin');

// REGISTRATION
$routes->get('registration', 'RegistrationController::register');
$routes->post('registration/processForm', 'RegistrationController::processForm');

// NAVIGATION BAR
$routes->get('/home', 'HomeController::home');
$routes->get('/contact-us', 'HomeController::contactUs');
$routes->get('/banner', 'HomeController::banner');
$routes->get('/logout', 'HomeController::logout');
$routes->get('/achievements', 'HomeController::achievements');
$routes->get('/contacts', 'HomeController::contacts');
$routes->get('/activities', 'HomeController::activities');
$routes->get('/site', 'HomeController::home');

//ALBUM
$routes->get('/album', 'HomeController::album');
$routes->get('/intern', 'HomeController::intern');
$routes->get('/pfv', 'HomeController::pfv');
$routes->get('/fdas', 'HomeController::fdas');
$routes->get('/inspection', 'HomeController::inspection');





// ---------------------ADMIN DASHBOARD------------------------------------
// ADMIN LOGIN
$routes->get('admin-login', 'ALoginController::adminlogin');
$routes->get('admin/processlogin', 'ALoginController::adminprocessLogin');
$routes->post('adddologin', 'ALoginController::adddologin');

// ADMIN REGISTRATION
$routes->get('admin-registration', 'ARegistrationController::adminregister');
$routes->post('admin-registration/processForm', 'ARegistrationController::adminprocessForm');

// ADMIN DASHBOARD HEADER NAVIGATION BAR 
$routes->get('/admin-home', 'ANavigationController::adminManage');
$routes->get('/admin-logout', 'ANavigationController::adminLogout');

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
$routes->get('/graph', 'GraphController::graph');


// SOS
$routes->get('security', 'SecurityController::sos');
$routes->post('security/capture-fire-incident', 'SecurityController::captureFireIncident');
$routes->post('security/send-to-admin-dashboard', 'SecurityController::sendToAdminDashboard');


$routes->get('user-location', 'LocationController::showUserLocation');
$routes->post('location/update', 'LocationController::updateLocation');
$routes->get('mapping', 'LocationController::map');

//EMERGENCY CALL
//EMERGENCY CALL
$routes->get('emergency-call', 'EmergencyCallController::emergencycall');
$routes->post('emergency-call/submit', 'EmergencyCallController::submitEmergencyCall');
$routes->get('emergency', 'EmergencyCallController::emergency');
$routes->get('sitecall', 'EmergencyCallController::sitecall');
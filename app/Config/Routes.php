<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Login: RESTful resource (GET form, POST submit)
$routes->get('login', 'Login::index');
$routes->post('login', 'Login::attempt');
$routes->get('logout', 'Login::logout');

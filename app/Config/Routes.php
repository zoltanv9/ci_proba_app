<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Menu::index');
$routes->get('/login', 'Login::index');

// RESTful routes
$routes->post('/sessions', 'Login::login');
$routes->delete('/sessions', 'Login::logout');
$routes->get('/menu', 'Menu::list');
$routes->post('/menu', 'Menu::create');
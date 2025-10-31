// ============================================
// File: application/config/routes.php (Add these routes)
// ============================================
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'auth/login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['login'] = 'auth/login';
$route['logout'] = 'auth/logout';
$route['dashboard'] = 'dashboard/index';
$route['files/upload'] = 'files/upload';
$route['files/approve/(:num)'] = 'files/approve/$1';
$route['files/reject/(:num)'] = 'files/reject/$1';
$route['files/list'] = 'files/get_files';
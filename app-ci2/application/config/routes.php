<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'welcome';
$route['404_override']       = '';

$route['dashboard']     = 'dashboard/index';
$route['sso/callback']      = 'sso/callback';
$route['sso/logout']        = 'sso/logout';
$route['sso/local-logout']  = 'sso/local_logout';
$route['logout']            = 'sso/logout';

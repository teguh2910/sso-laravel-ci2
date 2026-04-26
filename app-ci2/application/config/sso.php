<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['sso_base_url']   = 'http://localhost:8000';
$config['sso_jwt_secret'] = 'please-change-me-shared-with-clients';
$config['sso_jwt_alg']    = 'HS256';
$config['sso_callback']   = 'http://localhost:8002/sso/callback';

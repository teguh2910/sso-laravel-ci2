<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sso extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('sso_client');
    }

    public function callback()
    {
        $this->sso_client->handle_callback();
        redirect('/dashboard');
    }

    public function logout()
    {
        $sso_logout = $this->sso_client->logout();
        redirect($sso_logout);
    }

    // Called by the SSO server (in an iframe) on global logout.
    public function local_logout()
    {
        $this->sso_client->logout();
        header('Content-Type: text/plain');
        echo 'OK';
    }
}

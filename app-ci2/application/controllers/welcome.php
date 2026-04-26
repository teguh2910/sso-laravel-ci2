<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller
{
    public function index()
    {
        $this->load->helper('url');
        echo '<!doctype html><html><head><meta charset="utf-8"><title>App CI2</title>'
           . '<style>body{font-family:system-ui;background:#0f172a;color:#e2e8f0;margin:0}'
           . '.w{max-width:520px;margin:6rem auto;padding:2rem;background:#1e293b;border-radius:12px}'
           . 'a button{padding:.7rem 1rem;border:0;border-radius:8px;background:#6366f1;color:#fff;cursor:pointer;font-weight:600}'
           . '</style></head><body><div class="w">'
           . '<h1>App CodeIgniter 2 (Client)</h1>'
           . '<p>Public page. The dashboard requires SSO sign-in.</p>'
           . '<a href="' . site_url('dashboard') . '"><button>Go to dashboard</button></a>'
           . '</div></body></html>';
    }
}

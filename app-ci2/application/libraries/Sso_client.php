<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'libraries/JWT.php';

class Sso_client
{
    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->config->load('sso');
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function cfg($key)
    {
        return $this->CI->config->item($key);
    }

    public function user()
    {
        return isset($_SESSION['sso_user']) ? $_SESSION['sso_user'] : null;
    }

    public function require_login()
    {
        if ($this->user()) return;
        $url = rtrim($this->cfg('sso_base_url'), '/') . '/sso/authorize?redirect=' . urlencode($this->cfg('sso_callback'));
        header('Location: ' . $url);
        exit;
    }

    public function handle_callback()
    {
        $token = isset($_GET['token']) ? $_GET['token'] : null;
        if (!$token) show_error('Missing token', 400);

        try {
            $claims = JWT::decode($token, $this->cfg('sso_jwt_secret'), $this->cfg('sso_jwt_alg'));
        } catch (Exception $e) {
            show_error('Invalid SSO token: ' . $e->getMessage(), 401);
        }

        $_SESSION['sso_user'] = array(
            'id'    => isset($claims['sub'])   ? $claims['sub']   : null,
            'email' => isset($claims['email']) ? $claims['email'] : null,
            'name'  => isset($claims['name'])  ? $claims['name']  : null,
            'exp'   => isset($claims['exp'])   ? $claims['exp']   : null,
        );
        $_SESSION['sso_token'] = $token;
    }

    public function logout()
    {
        unset($_SESSION['sso_user'], $_SESSION['sso_token']);
        session_destroy();
        return rtrim($this->cfg('sso_base_url'), '/') . '/logout';
    }
}

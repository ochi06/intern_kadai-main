<?php

namespace Controller;

class Auth extends \Controller_Template
{
    public function action_login()
    {
        $this->template = 'auth/login';
    }

    public function action_register()
    {
        $this->template = 'auth/register';
    }

    public function action_logout()
    {
        \Session::destroy();
        \Response::redirect('auth/login');
    }
}
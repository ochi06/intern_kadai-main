<?php

namespace Controller;

class Base extends \Fuel\Core\Controller_Template
{
    protected $current_user = null;

    public function before()
    {
        parent::before();
        if (\Fuel\Core\Session::get('user_id')) {
            $this->current_user = \Model\User::findById(\Fuel\Core\Session::get('user_id'));
        }
    }

    protected function requireLogin()
    {
        if (!$this->current_user) {
            \Fuel\Core\Response::redirect('auth/login');
        }
    }
}
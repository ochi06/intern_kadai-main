<?php

class Controller_Base extends Controller
{
    protected $current_user = null;

    public function before()
    {
        parent::before();
        if (\Session::get('user_id')) {
            $this->current_user = \Model_User::findById(\Session::get('user_id'));
        }
    }

    protected function requireLogin()
    {
        if (!$this->current_user) {
            \Response::redirect('auth/login');
        }
    }
}
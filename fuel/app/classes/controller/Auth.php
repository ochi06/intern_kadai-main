<?php

namespace Controller;

class Auth extends \Controller
{
    public function action_login()
    {
        $error = null;

        if (\Input::method() === 'POST')
        {
            $mail_address = \Input::post('mail_address');
            $password = \Input::post('password');

            $user = \Model_User::findByEmail($mail_address);

            if ($user && password_verify($password, $user['password']))
            {
                \Session::set('user_id', $user['id']);
                \Response::redirect('home/index');
            }
            else
            {
                $error = 'ログイン情報が正しくありません';
            }
        }

        return \View::forge('auth/login', array(
            'error' => $error,
        ));
    }

    public function action_register()
    {
        if (\Input::method() === 'POST')
        {
            $user_name = \Input::post('user_name');
            $mail_address = \Input::post('mail_address');
            $password = \Input::post('password');

            \Model_User::create($user_name, $mail_address, $password);
            \Response::redirect('auth/login');
        }

        return \View::forge('auth/register');
    }

    public function action_logout()
    {
        \Session::destroy();
        \Response::redirect('auth/login');
    }
}
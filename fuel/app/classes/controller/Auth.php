<?php

class Controller_Auth extends Controller
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
                // セッション固定攻撃対策: ログイン時にセッションIDを再生成
                \Session::instance()->rotate();
                
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

            // 入力バリデーション
            $val = \Validation::forge();
            
            $val->add('user_name', 'ユーザー名')
                ->add_rule('required')
                ->add_rule('max_length', 10);
            
            $val->add('mail_address', 'メールアドレス')
                ->add_rule('required')
                ->add_rule('valid_email')
                ->add_rule('max_length', 50);
            
            $val->add('password', 'パスワード')
                ->add_rule('required')
                ->add_rule('min_length', 8)
                ->add_rule('max_length', 20);
            
            if ($val->run())
            {
                // メールアドレスの重複チェック
                if (\Model_User::findByEmail($mail_address))
                {
                    return \View::forge('auth/register', array(
                        'error' => 'このメールアドレスは既に登録されています',
                    ));
                }
                
                \Model_User::create($user_name, $mail_address, $password);
                \Response::redirect('auth/login');
            }
            else
            {
                return \View::forge('auth/register', array(
                    'error' => $val->error(),
                ));
            }
        }

        return \View::forge('auth/register');
    }

    public function action_logout()
    {
        \Session::destroy();
        \Response::redirect('auth/login');
    }
}
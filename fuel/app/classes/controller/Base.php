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
            // Ajaxリクエストの場合はJSONエラーを返す
            if (\Input::is_ajax()) {
                return \Response::forge(json_encode(array(
                    'success' => false,
                    'message' => 'ログインが必要です'
                )), 401, array(
                    'Content-Type' => 'application/json'
                ));
            }
            // 通常のリクエストの場合はログインページにリダイレクト
            \Response::redirect('auth/login');
        }
    }
}